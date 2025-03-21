<?php

namespace App\Repositories;

use App\Models\Session;
use App\Repositories\contract\SessionRepositoryInterface;

class SessionRepository implements SessionRepositoryInterface
{
    public function getall()
    {
        return session::all();
    }

    public function store(array $data){
        return Session::create($data);
    }

    public function update(array $data, int $id){
        $session = Session::find($id);
        return $session->update($data);
    }

    public function destroy(int $id){
        $session = Session::find($id);
        return $session->delete();
    }
}
