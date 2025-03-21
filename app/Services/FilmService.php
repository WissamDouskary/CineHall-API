<?php

namespace App\Services;

use App\Models\Film;
use App\Repositories\contract\FilmRepositoryInterface;

class FilmService
{
    public function __construct(protected FilmRepositoryInterface $filmRepository){

    }
    public function create(array $fields) {
        return $this->filmRepository->store($fields);
    }
}
