<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingsTransaction extends Model
{
    protected $fillable =
     [
        'savings_plan_id', 
        'amount', 
        'type', 
        'description'
    ];


    protected $casts =
     [
        'amount' => 'decimal:2'
    ];

    // relationship
    public function plan()
    { //this savings plan belongs to this savings transaction
        return $this->belongsTo(SavingsPlan::class);
    }
}
