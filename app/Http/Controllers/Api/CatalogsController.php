<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Catalog;
use App\Movie;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatalogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Auth::user()->catalogs()->with('movies')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Add movie to catalog - pass in movie_id and catalog_id
     * Create new catalog and add movie to it - pass in movie_id and catalog_name
     *
     * TODO: Move this to a MovieCatalogController and perform the Movie and Catalog creation separately in their own controllers
     */
    public function store(Request $request)
    {
        $movie = $request->movie;
        extract($movie);
        $catalog_id = $request->catalog_id;

        // Check if movie exists; create it if not
        Movie::where('id', $imdbID)->firstOrCreate([
            'id' => $imdbID,
            'title' => $Title,
            'poster' => $Poster,
            'year' => $Year,
            'type' => $Type,
        ]);

        /**
         * TODO: check if catalog_id is numeric
         * If not, then it's a new catalog - create a new record
         * Then use the new catalog_id to attach the movie to it
         */

        if (empty($catalog_id)) {
            $catalog_name = $request->catalog_name;

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
    public function detachMovie(Request $request)
    {
        $catalog_id = $request->catalog;
        $movie_id = $request->movie;

        // Detach the movie from the catalog - remove the record from movie_catalog
        Catalog::find($catalog_id)->movies()->detach($movie_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the Catalog name
     */
    public function update(Request $request, $id)
    {
        $catalog = Auth::user()->catalogs()->where('id', $id)->first();
        $catalog->name = $request->name;
        $catalog->save();

        return response(['catalog_name' => $catalog->name], 200);
    }

    /**
     * Delete a Catalog
     */
    public function destroy($id)
    {
        Catalog::destroy($id);
    }

    /**
     * Move/Copy a Movie to another Catalog
     */
    public function moveOrCopyMovie(Request $request)
    {
        $old_catalog_id = $request->catalog_id;
        $new_catalog_id = $request->catalog;
        $movie_id = $request->movie;

        // Copy the movie to a the specified catalog
        Catalog::find($new_catalog_id)->movies()->attach($movie_id);

        // If the action is move, delete it from the old catalog
        if ($request->action === 'move') {
            Catalog::find($old_catalog_id)->movies()->detach($movie_id);
        }
    }

    /**
     * Move/Copy a Movie to a new Catalog
     */
    public function moveOrCopyMovieToNewCatalog(Request $request)
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
    }
}
