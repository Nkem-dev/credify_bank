<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    protected $fillable = [
        'user_id',
        'stock_id',
    ];

    // Relationship with user
    public function user()
    { //this watchlisr belongs to a user
        return $this->belongsTo(User::class);
    }

    // relationship with stock
    public function stock()
    { //this watchlist belongs to a stock
        return $this->belongsTo(Stock::class);
    }
}
