<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory,  Notifiable, SoftDeletes;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    //controls which fields can be mass assigned
    protected $fillable = [ 
        'name',
        'email',
        'password',
        'phone',
        'dob',
        'gender',
        'account_number',
        'email_verification_otp',
        'email_verification_token',
        'transaction_pin',         // hashed 4-digit PIN
        'pin_set_at',
        'last_login_at',
        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kyc_verified_at' => 'datetime',
            'kyc_tier' => 'integer',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'datetime',
            'otp_expires_at' => 'datetime',
            'pin_set_at' => 'datetime',
        ];
    }

    // Relationship with profile table
    public function profile()
    {  //this user has one profile
        return $this->hasOne(Profile::class);
    }

    // relationship with transfers table
    public function transfers()
{ //this user has many transfers
    return $this->hasMany(Transfer::class, 'user_id');
}

    // Accessors/helpers

    // accessor for date of birth(get the users dob)
   public function getDobFormattedAttribute()
   {
    return $this->dob?->format('M d, Y') ?? 'Not set';
    }

    // accessor for phone number(get users phone no)
public function getRegistrationPhoneAttribute()
{
    return $this->phone ?? 'Not provided';
}

    // Accessor to get display name if set(get user name)
    public function getDisplayNameAttribute()
    {
        return $this->profile?->display_name ?? $this->name;
    }

    // Accessor for profile picture 
    public function getProfilePictureUrlAttribute()
    {
        return $this->profile?->profile_picture
            ? Storage::url($this->profile->profile_picture)
            : asset('images/blog-details.png');
    }

    // Accessor for gender
    public function getGenderDisplayAttribute(): string
    {
        return match ($this->gender) {
            'male'   => 'Male',
            'female' => 'Female',
            'other'  => 'Other',
            default  => 'Not specified',
        };
    }
   
    // transaction pin
     public function hasSetPin(): bool
{
    return !is_null($this->transaction_pin) && !is_null($this->pin_set_at);
} 

    

    // Accessor for tier label
    public function getTierLabelAttribute()
    {
        return match ($this->kyc_tier) {
            1 => 'Tier 1',
            2 => 'Tier 2',
            3 => 'Tier 3',
            default => 'Tier 1',
        };
    }

    // Accessor for transaction limit 

    public function getTransactionLimitAttribute()
    {
        return match ($this->kyc_tier) {
            1 => 50000,
            2 => 500000,
            3 => 5000000,
            default => 50000,
        };
    }
    

  

public function getInvestmentValueAttribute() 
{
     return $this->attributes['investment_value'] ?? 0.00; 
    }


    // relationship for virtual card
   public function virtualCard()
{ //user has one virtual card
    return $this->hasOne(VirtualCard::class);
}

// relationship for loan
public function loans()
{ //this user has many loans
    return $this->hasMany(Loan::class);
}

// helperaccessor for successful transactions (calculates how much money the user has succesfully transacted)
public function totalSuccessfulTransactions() 
{ //get all transfers with the status of successful and exclude virtual card payments
    return $this->transfers()
        ->where('status', 'successful')
        ->where('type', '!=', 'virtual_card')
        ->sum('amount'); //add all the transaction amounts that are successful
}

// helper method to check if the user is eligible for a loan
public function isEligibleForLoan() 
{ //if the totalsuccesfultransactions is greater than or equals to 50000, the user is eligible for a loan (returns true or false)
    return $this->totalSuccessfulTransactions() >= 50000;
}

// helper method to get loan limit
public function getMaxLoanLimit() 
{
    // get the user's total successful transactions
    $total = $this->totalSuccessfulTransactions();
    
    if ($total < 50000) return 0; //if the successful transaction is less than 50000, no loan
    if ($total < 100000) return 100000; //if user has done transcations between 50000 to 99000, oan limit is 100000
    if ($total < 250000) return 250000; //If user has done transactions between ₦100,000 - ₦249,999, loan limit is 250000
    if ($total < 500000) return 500000; //If user has done transactions between ₦250,000 - ₦499,999, the loan limit is 500000
    if ($total < 1000000) return 1000000; //If user has done transactions between ₦500,000 - ₦999,999, loan limit is 1 million
    return $total * 2; // If you've transacted ₦1,000,000 or more, double your total transactions
}


// relationship with savings plan
public function savingsPlans()
{ //this user has any savings plan
    return $this->hasMany(SavingsPlan::class);
}


protected function savingsBalance(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->savingsPlans()->sum('current_balance')
        );
    }

    // relationship with settings
    public function settings()
    { //this user has one settings
        return $this->hasOne(UserSettings::class);
    }

     protected static function boot()
    {
        parent::boot();

        // Create default settings when user is created
        static::created(function ($user) {
            UserSettings::create([
                'user_id' => $user->id,
            ]);
        });
    }


    // helper to ger default settings for a user
     public function getSettings()
    {
        return $this->settings ?? UserSettings::create(['user_id' => $this->id]);
    }


    
}
