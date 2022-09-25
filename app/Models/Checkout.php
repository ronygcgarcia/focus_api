<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'checkout_date', 'user_id', 'status', 'book_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    protected static function boot(){
        parent::boot();
        static::created(function($checkout) {
            $checkout->book->decrement('stock', 1);
        });
    }
}
