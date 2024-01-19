<?php

namespace Tests\Feature\Controllers\V1\Auth\AuthController;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shouldBeAbleToRegisterSuccessfully()
    {
        $user = User::factory()->make();

        $this->postJson(route('register'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',

        ])
            ->assertOk()
            ->assertJsonStructure([
                'access_token',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                'token_type'
            ]);
    }


    /** @test */
    public function shouldNotBeAbleToRegisterIfNoCredentials()
    {
        $this->postJson(route('register'), [])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'email',
                    'password'
                ]
            ]);
    }

    /** @test */
    public function shouldNotBeAbleToRegisterIfEmailAlreadyExist()
    {
        $this->postJson(route('register'), [])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                ]
            ]);
    }
}
