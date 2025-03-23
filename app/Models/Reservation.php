<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Session;
use App\Models\Seat;

class Reservation extends Model
{
    protected $fillable = ['user_id', 'session_id', 'seat_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}
