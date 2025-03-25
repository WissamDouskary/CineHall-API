<?php

namespace App\Http\Controllers;

use App\Repositories\ReservationRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\contract\ReservationRepositoryInterface;
use App\Models\Reservation;

class ReservationController extends Controller
{
    protected $reservationRepository;

    public function __construct(ReservationRepositoryInterface $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'session_id' => 'required|integer|exists:sessions,id',
            'seats' => 'array|required',
            'seats.*' => 'integer|exists:seats,id'
        ]);

        $userId = Auth::id();

        $reservedSeats = Reservation::where('session_id', $fields['session_id'])
            ->whereIn('seat_id', $fields['seats'])
            ->pluck('seat_id')
            ->toArray();

        $availableSeats = array_diff($fields['seats'], $reservedSeats);

        if(empty($availableSeats)){
            return response()->json(['message' => 'you have choose a reserved seats!'], 400);
        }

        $reservations = [];
        foreach ($fields['seats'] as $seat) {
            $reservation = $this->reservationRepository->reserveSeat($userId, $fields['session_id'], $seat, count($fields['seats']));
            if($reservation){
                $reservations[] = $reservation;
            }
        }

        $this->checkExpiredReservations();

        return response()->json([
            'message' => !empty($reservations) > 0 ? 'reservation success' : 'reservation failed',
            'reserved_seats' => $reservations,
        ]);
    }

    public function checkExpiredReservations(){
        $reservations = Reservation::where('status', 'waiting')
            ->where('created_at', '<=', Carbon::now()->subMinutes(15))
            ->get();

        foreach ($reservations as $reservation) {
            $reservation->status = 'cancelled';
            $reservation->save();
        }
    }
}
