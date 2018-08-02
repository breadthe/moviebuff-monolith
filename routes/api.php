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
    Route::post('/catalogs', 'Api\CatalogsController@store');

    Route::get('/movie/{movie}/catalogs', 'Api\MoviesController@catalogs')->name('movie_catalogs');
});
