<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    /**
     * Primary key (id) is the imdbID
     */
    protected $keyType = 'string'; // Indicate that the primary key is not an integer
    public $incrementing = false; // Indicate that the primary key is non-numeric and non-incrementing

    /**
     * Many-to-many relationship between Movie and Catalog
     */
    public function catalogs()
    {
        return $this->belongsToMany(Catalog::class, 'movie_catalog', 'movie_id', 'catalog_id');
    }
}
