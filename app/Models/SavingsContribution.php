<?php

namespace App\Models;

use Database\Factories\SavingsContributionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavingsContribution extends Model
{
    /** @use HasFactory<SavingsContributionFactory> */
    use HasFactory;

    protected $fillable = [
        'savings_goal_id', 'transaction_id', 'amount', 'note', 'date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'date' => 'date',
        ];
    }

    public function savingsGoal(): BelongsTo
    {
        return $this->belongsTo(SavingsGoal::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
