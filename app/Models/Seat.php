<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Room;

class Seat extends Model
{
    protected $fillable = ['room_id', 'seat_number', 'price'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
