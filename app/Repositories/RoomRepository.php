<?php

namespace App\Repositories;

use App\Models\Room;
use App\Repositories\contract\RoomRepositoryInterface;

class RoomRepository implements RoomRepositoryInterface
{
    public function create($data){
        return Room::create($data);
    }
    public function getall(){
        return Room::all();
    }
}
