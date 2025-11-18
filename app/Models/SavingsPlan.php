<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SavingsPlan extends Model
{
    protected $fillable = [
        'user_id', 
        'name', 
        'slug', 
        'description',
        'target_amount',
        'current_balance',
        'daily_interest_rate', 
        'last_interest_applied_at', 
        'is_locked'
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'daily_interest_rate' => 'decimal:4',
        'last_interest_applied_at' => 'datetime',
    ];

    // relationship
    public function user()
    { //this savings plan belongs to this user
        return $this->belongsTo(User::class);
    }

    public function transactions()
    { //this savings plan has many savings transaction
        return $this->hasMany(SavingsTransaction::class);
    }

    
    // Apply daily interest to savings account
    public function applyDailyInterest()
    {
        $now = Carbon::now(); //gets toda's date and time

        // if there is a record of when the last interest was guven, use the date but if not- use the day the savings was created
        $last = $this->last_interest_applied_at ?? $this->created_at;

        // checks how many days have passed since interest was last given
        $days = $last->diffInDays($now);

        // if no days have passed (same day), no interest added yet
        if ($days === 0) return;

        $interest = 0; //start counting interest from 0
        $balance = $this->current_balance; //how much money saved

        // loop for each day that has passed
        for ($i = 0; $i < $days; $i++) {
            $dailyInterest = $balance * $this->daily_interest_rate;
            $interest += $dailyInterest;
            $balance += $dailyInterest;
        }

        // if interest is greater than 0, add to the user's savings balance 
        if ($interest > 0) {
            $this->increment('current_balance', $interest); //increase the user's savings balance
            $this->update(['last_interest_applied_at' => $now]); //updates the date the interest was added

            // create a savings transaction record
            SavingsTransaction::create([
                'savings_plan_id' => $this->id, //savings plan that got interest
                'amount' => round($interest, 2), //how much interest
                'type' => 'interest', 
                'description' => "Daily interest for $days day(s)",
            ]);
        }
    }

    // Fund savings from wallet
    public function fund($amount, $user) //amount to save and usr
    {
        // checks if the user has enough money in their account balance. if the user's balance is less than the money to be saved , show error message
        if ($user->balance < $amount) { 
            throw new Exception('Insufficient wallet balance.');
        }

        // start task
        DB::transaction(function () use ($amount, $user) {
            $this->applyDailyInterest(); //apply daily interest

            $this->increment('current_balance', $amount); //add amount into savings balance
            $user->decrement('balance', $amount); //decrease user's balance

            // create savings transaction record
            SavingsTransaction::create([
                'savings_plan_id' => $this->id,
                'amount' => $amount,
                'type' => 'deposit',
                'description' => 'Funded from wallet',
            ]);

            // Record wallet transaction
            Transfer::create([
                'user_id' => $user->id,
                'reference' => 'SAVE-' . strtoupper(Str::random(8)),
                'type' => 'savings_deposit',
                'amount' => $amount,
                'description' => "Saved to {$this->name}",
                'status' => 'successful',
                'completed_at' => now(),
            ]);
        });
    }
}
