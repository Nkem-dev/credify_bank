<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
      protected $fillable = [
        'user_id',
        'total_invested',
        'current_value',
        'total_profit_loss',
        'profit_loss_percentage',
        'total_dividends',
        'ytd_return',
    ];

    protected $casts = [
        'total_invested' => 'decimal:2',
        'current_value' => 'decimal:2',
        'total_profit_loss' => 'decimal:2',
        'profit_loss_percentage' => 'decimal:4',
        'total_dividends' => 'decimal:2',
        'ytd_return' => 'decimal:4',
    ];

    // Relationships with user
    public function user()
    { //this investment belongs to thuis user
        return $this->belongsTo(User::class);
    }

    // Check if portfolio is profitable
    public function isProfitable()
    { //returns true if there is profit and false if there is loss or zero
        return $this->total_profit_loss > 0;
    }

    // Calculate and update investment summary
    public function updateSummary()
    {
        $userStocks = $this->user->userStocks()->with('stock')->get(); //get all user's owned stocks

        $totalInvested = $userStocks->sum('total_invested'); //calculate total invested

        // calculate total value of all shares
        $currentValue = $userStocks->sum(function ($userStock) {
            return $userStock->quantity * $userStock->stock->current_price;
        });

        $totalProfitLoss = $currentValue - $totalInvested; //calculate profit or loss in naira
        $profitLossPercentage = $totalInvested > 0  //calculate profit/loss in percentage
            ? (($currentValue - $totalInvested) / $totalInvested) * 100 
            : 0;

            // update the record
        $this->update([
            'total_invested' => $totalInvested,
            'current_value' => $currentValue,
            'total_profit_loss' => $totalProfitLoss,
            'profit_loss_percentage' => $profitLossPercentage,
        ]);
    }

    // Get or create investment record for user
    public static function getOrCreateForUser($userId)
    {
        // Checks if the user already has an investment summary,If not it creates a new one with all fields set to 0
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'total_invested' => 0,
                'current_value' => 0,
                'total_profit_loss' => 0,
                'profit_loss_percentage' => 0,
                'total_dividends' => 0,
                'ytd_return' => 0,
            ]
        );
    }

    
}
