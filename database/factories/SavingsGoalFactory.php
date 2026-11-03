<?php

namespace Database\Factories;

use App\Models\SavingsGoal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SavingsGoal>
 */
class SavingsGoalFactory extends Factory
{
    public function definition(): array
    {
        $target = fake()->randomFloat(2, 500_000, 20_000_000);

        return [
            'user_id' => User::factory(),
            'name' => fake()->randomElement([
                'Dana Darurat', 'Liburan Bali', 'Laptop Baru',
                'Motor Impian', 'Modal Usaha', 'Dana Pernikahan',
            ]),
            'description' => fake()->sentence(8),
            'target_amount' => $target,
            'saved_amount' => fake()->randomFloat(2, 0, $target),
            'deadline' => fake()->dateTimeBetween('+1 month', '+2 years')->format('Y-m-d'),
            'status' => 'active',
            'color' => fake()->randomElement(['#10b981', '#6366f1', '#f59e0b']),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attrs) => [
            'saved_amount' => $attrs['target_amount'],
            'status' => 'completed',
        ]);
    }
}
