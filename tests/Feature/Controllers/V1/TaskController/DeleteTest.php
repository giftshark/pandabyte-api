<?php

namespace Tests\Feature\Controllers\V1\TaskController;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canDeleteTaskIfAuthenticated()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $task = Task::factory()->create(['user_id' => $user]);

        $this->deleteJson(route('tasks.destroy', $task->id))
            ->assertNoContent();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function cannotDeleteTaskIfUnauthenticated()
    {
        $task = Task::factory()->create();

        $this->deleteJson(route('tasks.destroy', $task->id))
            ->assertUnauthorized();

    }

}
