<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    protected $fillable = ['user_id', 'name'];

    /**
     * Relationship between Catalog and User
     * Catalog belongs to a User
     * $catalog->user->name
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Many-to-many relationship between Catalog and Movie
     */
    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_catalog', 'catalog_id', 'movie_id');
    }
}
