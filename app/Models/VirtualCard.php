<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VirtualCard extends Model
{
    protected $fillable = [
        'user_id',
        'card_number',
        'expiry_month',
        'expiry_year',
        'cvv',
        'balance',
        'status',
        'expires_at',
        'description',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    // relationship between user with virtual card
    public function user()
{ //this virtual card , belongs to one user
    return $this->belongsTo(User::class);
}

public function transfers()
{
    return $this->hasMany(Transfer::class);
}


// helper function for visa card
public static function generateVisaCard()
{
    $bin = '4532'; // Visa BIN
    $account = str_pad(rand(0, 999999999999), 12, '0', STR_PAD_LEFT); // 12 digits
    $number = $bin . $account; // 16 digits (we'll fix last one)

    // Luhn Algorithm: Calculate check digit using first 15 digits
    $sum = 0;
    for ($i = 0; $i < 15; $i++) {
        $digit = (int)$number[14 - $i]; // Start from right, skip check digit
        if ($i % 2 == 1) { // Every 2nd digit from right
            $digit *= 2;
            if ($digit > 9) $digit -= 9;
        }
        $sum += $digit;
    }

    $checkDigit = (10 - ($sum % 10)) % 10;

    // Replace the 16th digit (not append)
    $cardNumber = substr($number, 0, 15) . $checkDigit;

    return [
        'card_number' => $cardNumber, // Always 16 digits
        'expiry_month' => str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT),
        'expiry_year' => (string)(date('Y') + rand(2, 5)),
        'cvv' => str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT),
    ];
}

protected static function boot()
{
    parent::boot();

    // everytime a new virtualcard record is about to be created, Laravel automatically generates a random reference code and saves it to the reference column of the card
    static::creating(function ($card) {
        $card->reference = 'VC-' . strtoupper(Str::random(12));
    });
}

public function getRouteKeyName()
{
    return 'reference';
}


}
