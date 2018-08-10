<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Catalog;
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
     * Update the Catalog name
     */
    public function update(Request $request, $id)
    {
        $catalog = Auth::user()->catalogs()->where('id', $id)->first();
        $catalog->name = $request->catalog_name;
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
}
