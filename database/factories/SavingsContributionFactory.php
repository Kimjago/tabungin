<?php

namespace Database\Factories;

use App\Models\SavingsContribution;
use App\Models\SavingsGoal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SavingsContribution>
 */
class SavingsContributionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'savings_goal_id' => SavingsGoal::factory(),
            'transaction_id' => null,
            'amount' => fake()->randomFloat(2, 50_000, 1_500_000),
            'note' => fake()->sentence(3),
            'date' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
        ];
    }
}
