<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_the_public_frontend_but_cannot_add_a_schedule(): void
    {
        $this->get('/jadwal')
            ->assertOk()
            ->assertDontSee('Tambah Jadwal');

        $this->post('/jadwal', [
            'waktu' => '08:00',
            'sesi' => 'Pagi',
            'porsi' => 500,
        ])->assertRedirect(route('login'));

        $this->assertDatabaseCount('jadwal_pakans', 0);
    }

    public function test_registered_user_can_login_and_logout(): void
    {
        $user = User::factory()->create([
            'email' => 'pengelola@example.com',
            'password' => 'password-benar',
        ]);

        $this->get('/login')->assertOk();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password-benar',
        ])->assertRedirect(route('jadwal'));

        $this->assertAuthenticatedAs($user);

        $this->post('/logout')
            ->assertRedirect(route('jadwal'));

        $this->assertGuest();
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        User::factory()->create([
            'email' => 'pengelola@example.com',
            'password' => 'password-benar',
        ]);

        $this->from('/login')
            ->post('/login', [
                'email' => 'pengelola@example.com',
                'password' => 'password-salah',
            ])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
