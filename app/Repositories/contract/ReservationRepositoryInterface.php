<?php

namespace App\Repositories\contract;

interface ReservationRepositoryInterface
{
    public function reserveSeat(int $userId, int $sessionId, int $seatId, int $seatsCount);
}
