<?php

namespace Tests\Feature\Controllers\V1\TaskController;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canShowTaskIfAuthenticated()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $task = Task::factory()->create();

        $this->getJson(route('tasks.show', $task->id))
            ->assertOk()
            ->assertJsonStructure([
               'data' => [
                   'id',
                   'user_id',
                   'name',
                   'description',
                   'due_date',
                   'completed_at'
               ]
            ]);
    }

    /** @test */
    public function cannotShowTaskIfUnauthenticated()
    {
        $task = Task::factory()->create();

        $this->getJson(route('tasks.show', $task->id))
            ->assertUnauthorized();
    }
}
