<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Movie;

class MoviesController extends Controller
{
    public function catalogs(Movie $movie)
    {
        return $movie->catalogs()->get();
    }
}
