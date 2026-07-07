<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'amount',
        'status',
        'payment_type',
        'payment_details',
        'paid_at',
    ];

    protected $casts = [
        'payment_details' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark transaction as success
     */
    public function markAsSuccess($paymentDetails = null)
    {
        $this->status = 'success';
        $this->paid_at = now();
        
        if ($paymentDetails) {
            $this->payment_details = $paymentDetails;
        }
        
        $this->save();
        
        // Activate premium for user (30 days)
        $this->user->activatePremium(30);
        
        return $this;
    }

    /**
     * Mark transaction as failed
     */
    public function markAsFailed()
    {
        $this->status = 'failed';
        $this->save();
        
        return $this;
    }

    /**
     * Scope untuk transaction yang sukses
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }
}