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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
            $new_catalog = Catalog::create([
                'user_id' => Auth::user()->id,
                'name' => $catalog_name
            ]);
            $catalog_id = $new_catalog->id;
        }

        // Add movie_id, catalog_id to movie_catalog
        Catalog::find($catalog_id)->movies()->attach($imdbID);
    }

    public function destroyMovie(Request $request)
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $catalog = Auth::user()->catalogs()->where('id', $id)->first();
        $catalog->name = $request->name;
        $catalog->save();

        return response(['catalog_name' => $catalog->name], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
}
