<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use DatabaseTransactions;

    public function test_register_and_login(): void
    {
        $register = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password@8498',
            'password_confirmation' => 'Password@8498',
        ]);

        $register->assertCreated();
        $token = $register->json('data.token');
        $this->assertNotEmpty($token);

        $this->withToken($token)
            ->getJson('/api/me')
            ->assertOk()
            ->assertJsonPath('data.email', 'test@example.com');
    }

    public function test_profile_update_requires_auth(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->patchJson('/api/profile', ['name' => 'Updated', 'email' => $user->email])
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated');
    }
}
