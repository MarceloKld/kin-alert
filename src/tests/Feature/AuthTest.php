<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login()
    {
        $user = Utilisateur::factory()->create([
            'mot_de_passe' => bcrypt('123456789')
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => '123456789'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'token',
                     'user'
                 ]);
    }
}