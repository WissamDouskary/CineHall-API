<?php

namespace App\Repositories;

use App\Models\Film;
use App\Repositories\contract\FilmRepositoryInterface;

class FilmRepository implements FilmRepositoryInterface
{

    public function getall() : Film{
        return Film::all();
    }
    public function store(array $data) : Film {
        return Film::create($data);
    }

    public function update(array $data, int $id) : int {
        $film = Film::findOrFail($id);

        return $film->update($data);
    }

    public function destroy(int $id) : int {
        $film = Film::findOrFail($id);

        return $film->delete();
    }

}
