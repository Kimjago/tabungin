<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(['income', 'expense']);

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory()->state(['type' => $type]),
            'type' => $type,
            'amount' => fake()->randomFloat(2, 10_000, 2_000_000),
            'note' => fake()->sentence(4),
            'date' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
        ];
    }

    public function expense(): static
    {
        return $this->state(fn () => [
            'type' => 'expense',
            'category_id' => Category::factory()->expense(),
        ]);
    }

    public function income(): static
    {
        return $this->state(fn () => [
            'type' => 'income',
            'category_id' => Category::factory()->income(),
        ]);
    }
}
