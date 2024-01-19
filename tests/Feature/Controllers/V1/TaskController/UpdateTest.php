<?php

namespace Tests\Feature\Controllers\V1\TaskController;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canUpdateTaskIfAuthenticated()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $task = Task::factory()->create(['user_id' => $user->getKey()]);

        $params = [
            'name' => 'UPDATED',
            'description' => 'UPDATED',
        ];

        $this->putJson(route('tasks.update', $task->id), $params)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_id',
                    'name',
                    'description',
                    'due_date',
                    'completed_at',
                ],
            ]);
    }

    /** @test */
    public function cannotUpdateTaskIfHasInvalidData()
    {
       $user = Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $task = Task::factory()->create(['user_id' => $user->getKey()]);

        $this->putJson(route('tasks.update', $task->id), [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name' => 'required', 'description' => 'required']);
    }

    /** @test */
    public function cannotUpdateTaskIfUnauthenticated()
    {
        $task = Task::factory()->create();

        $params = [
            'name' => $task->name,
            'description' => $task->description,
        ];

        $this->putJson(route('tasks.update', $task->id), $params)
            ->assertUnauthorized();

    }
}
