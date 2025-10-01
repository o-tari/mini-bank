<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Loan extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'amount',
        'purpose',
        'duration_months',
        'monthly_payment',
        'interest_rate',
        'income',
        'status',
        'rejection_reason',
        'approval_comments',
        'approved_by',
        'approved_at',
        'disbursed_at',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'monthly_payment' => 'decimal:2',
            'interest_rate' => 'decimal:2',
            'income' => 'decimal:2',
            'approved_at' => 'datetime',
            'disbursed_at' => 'datetime',
            'due_date' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isDisbursed(): bool
    {
        return $this->status === 'disbursed';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isDefaulted(): bool
    {
        return $this->status === 'defaulted';
    }
}
