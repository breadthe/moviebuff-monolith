<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = ['id', 'title', 'poster', 'year', 'type'];

    protected $hidden = ['pivot']; // hide the movie_catalog pivot table when joining with catalogs

    /**
     * Primary key (id) is the imdbID
     */
    protected $keyType = 'string'; // Indicate that the primary key is not an integer
    public $incrementing = false; // Indicate that the primary key is non-incrementing

    /**
     * Many-to-many relationship between Movie and Catalog
     */
    public function catalogs()
    {
        return $this->belongsToMany(Catalog::class, 'movie_catalog', 'movie_id', 'catalog_id');
    }
}
