<?php

namespace App\Http\Controllers;

use App\Services\FilmService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FilmController extends Controller
{
    public function __construct(protected FilmService $filmService)
    {
    }
    public function store(Request $request){
        $fields = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|string|string',
            'duration' => 'required',
            'minimum_age' => 'required|integer',
            'trailer_url' => 'required|string',
            'genre' => 'required|string',
            'user_id' => 'required|integer'
        ]);

        $fields['user_id'] = Auth::id();

        if(!User::where('is_admin', true)->count() > 0){
            return response()->json(["message" => "You can't create a film!"], 401);
        }

        $film = $this->filmService->create($fields);

        return response()->json([
            "message" => "Film created!",
            "film" => $film
        ], 201);
    }
}
