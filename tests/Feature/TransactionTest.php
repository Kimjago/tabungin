<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_transaksi_butuh_login(): void
    {
        $this->get(route('transactions.index'))->assertRedirect(route('login'));
    }

    public function test_pengguna_bisa_mencatat_transaksi_dengan_pemisah_ribuan(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->expense()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post(route('transactions.store'), [
                'type' => 'expense',
                'amount' => '25.000',
                'category_id' => $category->id,
                'note' => 'Makan siang',
                'date' => now()->toDateString(),
            ])
            ->assertRedirect(route('transactions.index'));

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'amount' => 25000,
            'note' => 'Makan siang',
        ]);
    }

    public function test_nominal_nol_ditolak(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('transactions.store'), [
                'type' => 'expense',
                'amount' => '0',
                'date' => now()->toDateString(),
            ])
            ->assertSessionHasErrors('amount');
    }

    public function test_pengguna_tidak_bisa_mengubah_transaksi_orang_lain(): void
    {
        $pemilik = User::factory()->create();
        $penyusup = User::factory()->create();
        $trx = Transaction::factory()->expense()->create(['user_id' => $pemilik->id]);

        $this->actingAs($penyusup)
            ->get(route('transactions.edit', $trx))
            ->assertForbidden();
    }

    public function test_pengguna_bisa_menghapus_transaksinya_sendiri(): void
    {
        $user = User::factory()->create();
        $trx = Transaction::factory()->expense()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->delete(route('transactions.destroy', $trx))
            ->assertRedirect(route('transactions.index'));

        $this->assertDatabaseMissing('transactions', ['id' => $trx->id]);
    }

    public function test_daftar_transaksi_hanya_menampilkan_milik_sendiri(): void
    {
        $user = User::factory()->create();
        $lain = User::factory()->create();

        Transaction::factory()->expense()->create([
            'user_id' => $user->id,
            'note' => 'Punya saya',
        ]);
        Transaction::factory()->expense()->create([
            'user_id' => $lain->id,
            'note' => 'Punya orang lain',
        ]);

        $this->actingAs($user)
            ->get(route('transactions.index'))
            ->assertOk()
            ->assertSee('Punya saya')
            ->assertDontSee('Punya orang lain');
    }
}
