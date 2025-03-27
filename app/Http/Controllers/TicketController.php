<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Ticket; // Modèle de ton ticket

class TicketController extends Controller
{
    public function generateTicket(Request $request)
    {
        $ticketId = '12345';
        $cinemaName = 'Cinema XYZ';
        $movieName = 'Avengers: Endgame';
        $showTime = '2025-03-26 19:00';
        $seat = 'A10';

        $qrCodeContent = route('validate.ticket', ['ticket_id' => $ticketId]);

        $qrCode = QrCode::format('png')->size(200)->generate($qrCodeContent);

        $ticket = new Ticket();
        $ticket->ticket_id = $ticketId;
        $ticket->cinema_name = $cinemaName;
        $ticket->movie = $movieName;
        $ticket->show_time = $showTime;
        $ticket->seat = $seat;
        $ticket->qr_code = base64_encode($qrCode);  // Sauvegarder le QR code en base64
        $ticket->save();

        // Retourner la réponse avec les détails du billet et le QR code
        return response()->json([
            'ticket_id' => $ticketId,
            'cinema_name' => $cinemaName,
            'movie' => $movieName,
            'show_time' => $showTime,
            'seat' => $seat,
            'qr_code' => base64_encode($qrCode)
        ]);
    }
}
