<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/catalogs', 'Api\CatalogsController@index')->name('catalogs');

    /**
     * Add movie to catalog - pass in movie_id and catalog_id
     * Create new catalog and add movie to it - pass in movie_id and catalog_name
    */
    Route::post('/catalog', 'Api\CatalogsController@store');

    /**
     * Edit the Catalog name
     */
    Route::put('/catalog/{id}', 'Api\CatalogsController@update');

    /**
     * Delete a Catalog
     */
    Route::delete('/catalog/{id}', 'Api\CatalogsController@destroy');

    /**
     * Remove the Catalog-Movie association (un-tag a movie)
     */
    Route::delete('/catalog/{catalog}/movie/{movie}', 'Api\CatalogMovieController@destroy');

    /**
     * Move/Copy a movie to an existing Catalog
     */
    Route::put('/catalog/{catalog}/movie/{movie}', 'Api\CatalogMovieController@update');

    /**
     * Move/Copy movie to a new catalog
    */
    Route::put('/catalog', 'Api\CatalogMovieController@store');

    /**
     * Retrieve all the catalogs for a given movie (tag the movie)
     */
    Route::get('/movie/{movie}/catalogs', 'Api\MoviesController@catalogs')->name('movie_catalogs');
});
