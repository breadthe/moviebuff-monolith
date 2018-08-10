<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Movie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MoviesController extends Controller
{
    /**
     * Retrieve all the catalogs (owned by the user) that a movie is tagged with
     */
    public function catalogs(Movie $movie)
    {
        /**
         * TODO: See if this can be done better with Eloquent
         */
        return DB::table('catalogs')
            ->leftJoin('movie_catalog', 'catalogs.id', '=', 'movie_catalog.catalog_id')
            ->leftJoin('movies', 'movies.id', '=', 'movie_catalog.movie_id')
            ->where([
                'catalogs.user_id' => Auth::user()->id,
                'movies.id' => $movie->id
            ])
            ->select('catalogs.id', 'catalogs.name', 'catalogs.created_at')
            ->get();
    }
}
