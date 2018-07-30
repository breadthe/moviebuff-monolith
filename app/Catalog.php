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

    /* public function movies()
    {
        return $this->hasMany(Movie::class);
    } */
}
