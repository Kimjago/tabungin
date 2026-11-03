<?php

namespace App\Models;

use Database\Factories\SavingsGoalFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SavingsGoal extends Model
{
    /** @use HasFactory<SavingsGoalFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'description', 'target_amount',
        'saved_amount', 'deadline', 'status', 'color',
    ];

    protected function casts(): array
    {
        return [
            'target_amount' => 'decimal:2',
            'saved_amount' => 'decimal:2',
            'deadline' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(SavingsContribution::class);
    }

    /** Persentase progres 0-100, aman dari pembagian nol. */
    public function getProgressPercentAttribute(): float
    {
        if ((float) $this->target_amount <= 0) {
            return 0.0;
        }

        return min(100.0, round((float) $this->saved_amount / (float) $this->target_amount * 100, 1));
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0.0, (float) $this->target_amount - (float) $this->saved_amount);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
