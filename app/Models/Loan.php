<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id', 
        'amount', 
        'interest_rate', 
        'term_months',
        'monthly_payment', 
        'status', 
        'approved_at', 
        'disbursed_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'disbursed_at' => 'datetime',
    ];

    // relationship with user 
    public function user()
    { //this loan belongs to a particular user
        return $this->belongsTo(User::class);
    }

    // calculate monthly payment of loan 
    // (principal- money user borrowed, rate- extra money to pay per year(loan interest) , monthly- How many months to pay back loan)
    public static function calculateMonthlyPayment($principal, $rate, $months)
    {
        // take the yearly extra money(loan interest) and makes it monthly
        $r = $rate / 100 / 12;
        
        // calculates the amount loaned , interest rate and the months you have to pay back loan
        return $principal * ($r * pow(1 + $r, $months)) / (pow(1 + $r, $months) - 1);
    }
}
