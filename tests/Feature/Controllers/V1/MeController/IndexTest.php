<?php

namespace Tests\Feature\Controllers\V1\MeController;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function shouldBeAbleToGetAuthenticatedUserInformation()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        $this->getJson(route('me'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at'
                ]
            ])
            ->assertJsonFragment([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at
            ]);

    }
}
