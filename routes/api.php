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
     * TODO: this should be POST /movie/{movie}/catalog MovieCatalogController@create
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
    Route::delete('/movie/{movie}/catalog/{catalog}', 'Api\MovieCatalogController@destroy');

    /**
     * Move/Copy movie to an existing or new catalog
     * TODO: this should be PUT /movie/{movie}/catalog
     */
    Route::put('/movie/{movie}/catalog', 'Api\MovieCatalogController@store');

    /**
     * Retrieve all the catalogs for a given movie (tag the movie)
     */
    Route::get('/movies/{movie}/catalogs', 'Api\MoviesController@catalogs');
});
