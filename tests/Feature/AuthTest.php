<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_tampil(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_pengguna_bisa_mendaftar(): void
    {
        $this->post(route('register'), [
            'name' => 'Pengguna Baru',
            'email' => 'baru@tabungin.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'baru@tabungin.test']);
    }

    public function test_pendaftaran_menolak_email_duplikat(): void
    {
        User::factory()->create(['email' => 'sama@tabungin.test']);

        $this->from(route('register'))
            ->post(route('register'), [
                'name' => 'Pengguna Lain',
                'email' => 'sama@tabungin.test',
                'password' => 'rahasia123',
                'password_confirmation' => 'rahasia123',
            ])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_pengguna_bisa_masuk_dan_keluar(): void
    {
        $user = User::factory()->create(['password' => 'rahasia123']);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'rahasia123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('landing'));
        $this->assertGuest();
    }

    public function test_kata_sandi_salah_ditolak(): void
    {
        $user = User::factory()->create(['password' => 'rahasia123']);

        $this->from(route('login'))
            ->post(route('login'), [
                'email' => $user->email,
                'password' => 'salah',
            ])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_dashboard_butuh_login(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }
}
