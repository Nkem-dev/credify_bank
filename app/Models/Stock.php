<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
     protected $fillable = [
        'symbol',
        'name',
        'logo',
        'category',
        'current_price',
        'opening_price',
        'day_high',
        'day_low',
        'week_high',
        'week_low',
        'market_cap',
        'volume',
        'price_change',
        'price_change_percentage',
        'description',
        'is_active'
    ];

    protected $casts = [
        'current_price' => 'decimal:2',
        'opening_price' => 'decimal:2',
        'day_high' => 'decimal:2',
        'day_low' => 'decimal:2',
        'week_high' => 'decimal:2',
        'week_low' => 'decimal:2',
        'market_cap' => 'decimal:2',
        'price_change' => 'decimal:2',
        'price_change_percentage' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    // Relationship  with userstock
    public function userStocks()
    { //this stock has many userstocks/user portfolios
        return $this->hasMany(UserStock::class);
    }

    // relationship for stock transaction
    public function transactions()
    { //this stock has many stock transaction
        return $this->hasMany(StockTransaction::class);
    }

    // relationship with watchlist
    public function watchlists()
    { //this stock has many wacthlists
        return $this->hasMany(Watchlist::class);
    }

    // helper to Check if price is up 
    public function isPriceUp()
    {
        return $this->price_change > 0;
    }

     // helper to Check if price is down
    public function isPriceDown()
    {
        return $this->price_change < 0;
    }

    // Format category for display(make category readable)
    public function getCategoryNameAttribute()
    {
        return ucwords(str_replace('_', ' & ', $this->category));
    }
}
