<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
     protected $fillable = [
        'user_id',
        'stock_id',
        'type',
        'quantity',
        'price_per_share',
        'total_amount',
        'transaction_fee',
        'net_amount',
        'status',
        'reference',
        'description',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_per_share' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'transaction_fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    // Check transaction type
    public function isBuy()
    {
        return $this->type === 'buy';
    }

    public function isSell()
    {
        return $this->type === 'sell';
    }
}
