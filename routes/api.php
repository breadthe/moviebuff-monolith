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
    // User details
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Retrieve all the Catalogs for a given <ovie
    Route::get('/movies/{movie}/catalogs', 'Api\MoviesController@catalogs');

    // Get all the user's Catalogs
    Route::get('/catalogs', 'Api\CatalogsController@index')->name('catalogs');

    // Edit the Catalog name
    Route::put('/catalog/{id}', 'Api\CatalogsController@update');

    // Delete a Catalog
    Route::delete('/catalog/{id}', 'Api\CatalogsController@destroy');

    // Add Movie to existing or new Catalog
    Route::post('/movie/catalog', 'Api\MovieCatalogController@create');

    // Remove the Catalog-Movie association (un-tag a movie)
    Route::delete('/movie/{movie}/catalog/{catalog}', 'Api\MovieCatalogController@destroy');

    // Move/Copy Movie to an existing or new Catalog
    Route::put('/movie/{movie}/catalog', 'Api\MovieCatalogController@store');
});
