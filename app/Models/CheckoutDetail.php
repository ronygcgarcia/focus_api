<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CheckoutDetail extends Pivot
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'book_id', 'checkout_id', 'status',
    ];

    protected $table = 'checkout_details';
    /**
     * @var bool
     */
    public $incrementing = true;

    public function book(){
        return $this->belongsTo(Book::class);
    }
}
