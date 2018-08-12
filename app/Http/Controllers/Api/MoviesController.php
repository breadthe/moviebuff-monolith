<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MoviesController extends Controller
{
    /**
     * Retrieve all the catalogs (owned by the user) that a movie is tagged with
     */
    public function tags(Request $request)
    {
        return DB::table('catalogs')
            ->leftJoin('movie_catalog', 'catalogs.id', '=', 'movie_catalog.catalog_id')
            ->leftJoin('movies', 'movies.id', '=', 'movie_catalog.movie_id')
            ->where([
                'catalogs.user_id' => Auth::user()->id,
                'movies.id' => $request->movie
            ])
            ->select('catalogs.id', 'catalogs.name', 'catalogs.created_at')
            ->get();
    }
}
