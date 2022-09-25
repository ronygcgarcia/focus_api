<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'link_image', 'author', 'genre_id', 'publish_year', 'stock'
    ];

    public function checkouts()
    {
        return $this->hasMany(Checkout::class, 'book_id');
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
