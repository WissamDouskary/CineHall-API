<?php

namespace App\Services;

use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Log;
use App\Models\Reservation;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\TicketController;

class PayPalService
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new PayPalClient();
        $this->provider->setApiCredentials(config('paypal'));
    }

    public function createOrder($amount)
    {
        $this->provider->getAccessToken();

        $amount = number_format($amount, 2, '.', '');

            $order = $this->provider->createOrder([
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => config('paypal.currency'),
                            "value" => $amount
                        ]
                    ]
                ]
            ]);

            return $order;
    }

    public function captureOrder($orderId)
    {
        $this->provider->getAccessToken();
        $orderDetails = $this->provider->showOrderDetails($orderId);

        if (!isset($orderDetails['status'])) {
            return ['error' => 'Order status not found'];
        }

        if ($orderDetails['status'] === 'COMPLETED') {
            return ['message' => 'Payment already captured', 'order_status' => $orderDetails['status']];
        }

        if ($orderDetails['status'] !== 'APPROVED') {
            return ['error' => 'Order is not approved yet', 'order_status' => $orderDetails['status']];
        }

        $reserv = Reservation::where('paypal_order_id', $orderId)->first();
        if (!$reserv) {
            return ['error' => 'Order not found'];
        }

        $reserv->status = "payed";
        $reserv->save();

        $captureResponse = $this->provider->capturePaymentOrder($orderId);

        if (isset($captureResponse['status']) && $captureResponse['status'] === 'COMPLETED') {
            $this->generateTicket($reserv);
        }

        return $captureResponse;
    }

    protected function generateTicket($reservation)
    {
        $ticket = new Ticket();

        $session = $reservation->session;

        if ($session) {
            $film = $session->film;

            if ($film) {
                $ticket->film = $film->title;
            } else {
                $ticket->film = 'Unknown Film';
            }

            $ticket->reservation_id = $reservation->id;
            $ticket->start_date = $session->start_date;
            $ticket->end_date = $session->end_date;
            $ticket->seat = $reservation->seat;
        } else {
            return response()->json(
                ['error' => 'session not found']
                , 404);
        }

        $qrCode = QrCode::format('png')->size(200)->generate('http://127.0.0.1:8000/validate-ticket/' . $ticket->id);
        $ticket->qr_code = base64_encode($qrCode);

        $ticket->save();

        return $ticket;
    }

}

