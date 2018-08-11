<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Catalog;
use App\Movie;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovieCatalogController extends Controller
{
    /**
     * Attach Catalog to Movie (tag it)
     * Add existing catalog if catalog_id
     * Add new catalog if catalog_name
     *
     * $request {
     *      movie (OMDB object)
     *      catalog_id
     *      catalog_name
     * }
     */
    public function create(Request $request)
    {
        $movie = $request->movie;
        extract($movie);
        $catalog_id = $request->catalog_id;
        $catalog_name = $request->catalog_name;

        // Check if movie exists; create it if not
        Movie::where('id', $imdbID)->firstOrCreate([
            'id' => $imdbID,
            'title' => $Title,
            'poster' => $Poster,
            'year' => $Year,
            'type' => $Type,
        ]);

        // New catalog
        if (!empty($catalog_name)) {
            /**
             * Soft-ignore existing Catalog name -
             * - if the same Catalog name is given,
             * the Movie is added to the existing Catalog
             */
            $new_catalog = Catalog::firstOrCreate([
                'user_id' => Auth::user()->id,
                'name' => $catalog_name
            ]);
            $catalog_id = $new_catalog->id;
        }

        // Add movie_id, catalog_id to movie_catalog
        Catalog::find($catalog_id)->movies()->attach($imdbID);
    }

    /**
     * Detach a Movie from a Catalog (remove the record from movie_catalog)
     */
    public function destroy(Request $request)
    {
        $movie_id = $request->movie;
        $catalog_id = $request->catalog;

        // Detach the movie from the catalog - remove the record from movie_catalog
        Catalog::find($catalog_id)->movies()->detach($movie_id);
    }

    /**
     * Move/Copy a Movie to an existing or new Catalog
     */
    public function update(Request $request)
    {
        $movie_id = $request->movie;
        $source_catalog_id = $request->catalog;
        $action = $request->action;
        $destination_catalog_id = $request->destination_catalog_id;
        $catalog_name = $request->catalog_name;

        // Existing catalog
        if (!empty($destination_catalog_id)) {
            // Copy the movie to a the specified catalog
            $catalog = Catalog::find($destination_catalog_id);
            $catalog->movies()->attach($movie_id);
            $catalog_name = $catalog->name;
        }

        // New catalog
        elseif (!empty($catalog_name)) {
            // Create the new catalog
            $new_catalog = Catalog::firstOrCreate([
                'user_id' => Auth::user()->id,
                'name' => $catalog_name
            ]);
            $destination_catalog_id = $new_catalog->id;

            // Add movie_id, catalog_id to movie_catalog
            Catalog::find($destination_catalog_id)->movies()->attach($movie_id);
        }

        // If the action is move, delete it from the old catalog
        if ($action === 'move') {
            Catalog::find($source_catalog_id)->movies()->detach($movie_id);
        }

        return response(['catalog_name' => $catalog_name], 200);
    }
}
