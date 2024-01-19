<?php

namespace Tests\Feature\Controllers\V1\Auth\AuthController;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shouldBeAbleToLogout()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->postJson(route('logout'))
            ->assertNoContent();

    }

    /** @test */
    public function shouldNotBeAbleToLogoutIfNotAuthenticated()
    {
        $this->postJson(route('logout'))
            ->assertUnauthorized();
    }
}
