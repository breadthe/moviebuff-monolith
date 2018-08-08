<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Catalog;
use App\Movie;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatalogMovieController extends Controller
{
    /**
     * Detach a Movie from a Catalog (remove the record from movie_catalog)
     */
    public function destroy(Request $request)
    {
        $catalog_id = $request->catalog;
        $movie_id = $request->movie;

        // Detach the movie from the catalog - remove the record from movie_catalog
        Catalog::find($catalog_id)->movies()->detach($movie_id);
    }

    /**
     * Move/Copy a Movie to an existing Catalog
     */
    public function update(Request $request)
    {
        $old_catalog_id = $request->catalog_id;
        $new_catalog_id = $request->catalog;
        $movie_id = $request->movie;

        // Copy the movie to a the specified catalog
        $catalog = Catalog::find($new_catalog_id);
        $catalog->movies()->attach($movie_id);
        $catalog_name = $catalog->name;

        // If the action is move, delete it from the old catalog
        if ($request->action === 'move') {
            Catalog::find($old_catalog_id)->movies()->detach($movie_id);
        }

        return response(['catalog_name' => $catalog_name], 200);
    }

    /**
     * Move/Copy a Movie to a new Catalog
     */
    public function store(Request $request)
    {
        $old_catalog_id = $request->catalog_id;
        $catalog_name = $request->catalog_name;
        $movie_id = $request->movie_id;

        // TODO: Check for duplicate catalog name

        // Create the new catalog
        $new_catalog = Catalog::firstOrCreate([
            'user_id' => Auth::user()->id,
            'name' => $catalog_name
        ]);
        $new_catalog_id = $new_catalog->id;

        // Add movie_id, catalog_id to movie_catalog
        Catalog::find($new_catalog->id)->movies()->attach($movie_id);

        // If the action is move, delete it from the old catalog
        if ($request->action === 'move') {
            Catalog::find($old_catalog_id)->movies()->detach($movie_id);
        }

        return response(['catalog_name' => $catalog_name], 200);
    }
}
