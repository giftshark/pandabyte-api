<?php

namespace Tests\Feature\Controllers\V1\Auth\AuthController;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shouldBeAbleToLoginSuccessfully()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertOk()
            ->assertJsonStructure([
                'access_token',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ],
                'token_type'
            ]);
    }

    /** @test */
    public function shouldNotBeAbleToLoginIfInvalidCredential()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password@@@',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonFragment([
                'message' => 'Invalid Credentials'
            ]);
    }

    /** @test */
    public function shouldNotBeAbleToLoginIfNoCredentials()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $this->postJson(route('login'), [])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                    'password'
                ]
            ]);
    }
}
