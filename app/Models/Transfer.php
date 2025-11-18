<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transfer extends Model
{
    protected $fillable = [
        'user_id',
        'reference',
        'recipient_id',
        'recipient_account_number',
        'recipient_phone',
        'recipient_name',
        'recipient_bank_name',
        'recipient_bank_code',
        'type',
        'amount',
        'fee',
        'total_amount',
        'description',
        'status',
        'completed_at'

    ];

     protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    //  Get the user who initiated the transfer (sender)
    //  all transfers belongs to one user
    public function user(): BelongsTo  //Relationship
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the recipient user (for internal transfers)
     */ //recipient belongs to another user
    public function recipient(): BelongsTo
    { 
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Scope to filter internal transfers
     */
    public function scopeInternal($query)
    { //get all internal transfers
        return $query->where('type', 'internal');
    }

    /**
     * Scope to filter external transfers
     */
    public function scopeExternal($query)
    {
        // get all external transfers
        return $query->where('type', 'external');
    }

    /**
     * Scope to filter successful transfers
     */
    public function scopeSuccessful($query)
    {
        //get successful transfers
        return $query->where('status', 'successful');
    }

    /**
     * Scope to filter pending transfers
     */
    public function scopePending($query)
    {
        // get pending transfers
        return $query->where('status', 'pending');
    }

    /**
     * Scope to filter failed transfers
     */
    public function scopeFailed($query)
    {
        // get failed transfers
        return $query->where('status', 'failed');
    }

    /**
     * Check if transfer is internal
     */
    public function isInternal(): bool
    {
        return $this->type === 'internal';
    }

    /**
     * Check if transfer is external
     */
    public function isExternal(): bool
    {
        return $this->type === 'external';
    }

    /**
     * Check if transfer is successful
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'successful';
    }

    /**
     * Check if transfer is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if transfer is failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'successful' => 'green',
            'pending' => 'yellow',
            'failed' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return '₦' . number_format($this->amount, 2);
    }

    /**
     * Get formatted fee
     */
    public function getFormattedFeeAttribute(): string
    {
        return '₦' . number_format($this->fee, 2);
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAttribute(): string
    {
        return '₦' . number_format($this->total_amount, 2);
    }

}
