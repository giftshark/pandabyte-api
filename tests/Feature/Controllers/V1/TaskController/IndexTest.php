<?php

namespace Tests\Feature\Controllers\V1\TaskController;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canGetTaskList()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        Task::factory(10)->create();

        $this->getJson(route('tasks.index'))
            ->assertOk()
            ->assertJsonCount('10', 'data');
    }

    /** @test */
    public function cannotGetListIfUnauthenticated()
    {
        Task::factory(10)->create();

        $this->getJson(route('tasks.index'))
            ->assertUnauthorized();
    }
}
