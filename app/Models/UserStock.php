<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStock extends Model
{
    protected $fillable = [
        'user_id',
        'stock_id',
        'quantity',
        'average_buy_price',
        'total_invested',
        'profit_loss',
        'profit_loss_percentage'
    ];

    protected $appends = ['current_value']; // Make it available in JSON/arrays

    // Relationship with user
    public function user()
    { //this user stock belongs to a user
        return $this->belongsTo(User::class);
    }

    // relationship with stock
    public function stock()
    { //this user stock belong a stock
        return $this->belongsTo(Stock::class);
    }

    // Calculate current value: quantity × stock's current price
    public function getCurrentValueAttribute()
    {
        return $this->quantity * ($this->stock->current_price ?? 0);
    }

    // Calculate profit/loss
    public function getProfitLossAttribute()
    {
        return $this->current_value - $this->total_invested;
    }

    // Calculate profit/loss percentage
    public function getProfitLossPercentageAttribute()
    {
        if ($this->total_invested == 0) return 0;
        return (($this->current_value - $this->total_invested) / $this->total_invested) * 100;
    }
}