<?php

namespace Tests\Feature;

use App\Models\SavingsGoal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavingsGoalDepositTest extends TestCase
{
    use RefreshDatabase;

    private function goalFor(User $user, array $attributes = []): SavingsGoal
    {
        return $user->savingsGoals()->create(array_merge([
            'name' => 'Dana Darurat',
            'target_amount' => 1_000_000,
            'saved_amount' => 0,
            'status' => 'active',
            'color' => '#10b981',
        ], $attributes));
    }

    public function test_pengguna_harus_masuk_untuk_menyetor(): void
    {
        $goal = $this->goalFor(User::factory()->create());

        $this->post(route('goals.deposit', $goal), [
            'amount' => '100000',
            'date' => now()->toDateString(),
        ])->assertRedirect(route('login'));

        $this->assertSame(0.0, (float) $goal->fresh()->saved_amount);
    }

    public function test_setoran_dengan_pemisah_ribuan_diterima(): void
    {
        $user = User::factory()->create();
        $goal = $this->goalFor($user);

        $this->actingAs($user)
            ->post(route('goals.deposit', $goal), [
                'amount' => '250.000',
                'note' => 'Setoran rutin',
                'date' => now()->toDateString(),
            ])
            ->assertRedirect();

        $this->assertSame(250000.0, (float) $goal->fresh()->saved_amount);
        $this->assertDatabaseHas('savings_contributions', [
            'savings_goal_id' => $goal->id,
            'amount' => 250000,
        ]);
    }

    public function test_setoran_tanpa_pemisah_juga_diterima(): void
    {
        $user = User::factory()->create();
        $goal = $this->goalFor($user);

        $this->actingAs($user)
            ->post(route('goals.deposit', $goal), [
                'amount' => '150000',
                'date' => now()->toDateString(),
            ])
            ->assertRedirect();

        $this->assertSame(150000.0, (float) $goal->fresh()->saved_amount);
    }

    public function test_saldo_adalah_total_semua_setoran(): void
    {
        $user = User::factory()->create();
        $goal = $this->goalFor($user);

        foreach (['100.000', '50.000', '25.000'] as $amount) {
            $this->actingAs($user)->post(route('goals.deposit', $goal), [
                'amount' => $amount,
                'date' => now()->toDateString(),
            ]);
        }

        $this->assertSame(175000.0, (float) $goal->fresh()->saved_amount);
        $this->assertSame(3, $goal->contributions()->count());
    }

    public function test_setoran_di_bawah_minimum_ditolak(): void
    {
        $user = User::factory()->create();
        $goal = $this->goalFor($user);

        $this->actingAs($user)
            ->post(route('goals.deposit', $goal), [
                'amount' => '500',
                'date' => now()->toDateString(),
            ])
            ->assertSessionHasErrors('amount');

        $this->assertSame(0.0, (float) $goal->fresh()->saved_amount);
    }

    public function test_setoran_kosong_ditolak(): void
    {
        $user = User::factory()->create();
        $goal = $this->goalFor($user);

        $this->actingAs($user)
            ->post(route('goals.deposit', $goal), [
                'amount' => '',
                'date' => now()->toDateString(),
            ])
            ->assertSessionHasErrors('amount');
    }

    public function test_target_otomatis_tercapai_saat_saldo_mencapai_target(): void
    {
        $user = User::factory()->create();
        $goal = $this->goalFor($user, ['target_amount' => 1_000_000]);

        $this->actingAs($user)->post(route('goals.deposit', $goal), [
            'amount' => '1.000.000',
            'date' => now()->toDateString(),
        ]);

        $goal->refresh();

        $this->assertSame(1000000.0, (float) $goal->saved_amount);
        $this->assertSame('completed', $goal->status);
    }

    public function test_pengguna_lain_tidak_boleh_menyetor_ke_target_orang(): void
    {
        $pemilik = User::factory()->create();
        $penyusup = User::factory()->create();
        $goal = $this->goalFor($pemilik);

        $this->actingAs($penyusup)
            ->post(route('goals.deposit', $goal), [
                'amount' => '100000',
                'date' => now()->toDateString(),
            ])
            ->assertForbidden();

        $this->assertSame(0.0, (float) $goal->fresh()->saved_amount);
    }
}
