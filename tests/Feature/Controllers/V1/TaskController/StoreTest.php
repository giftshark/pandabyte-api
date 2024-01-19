<?php

namespace Tests\Feature\Controllers\V1\TaskController;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canCreateTaskIfAuthenticated()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $task = Task::factory()->make();

        $params = [
            'name' => $task->name,
            'description' => $task->description,
        ];

        $this->postJson(route('tasks.store'), $params)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_id',
                    'name',
                    'description',
                    'due_date',
                    'completed_at',
                    'photos',
                ],
            ]);
    }

    /** @test */
    public function cannotCreateTaskIfHasInvalidData()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->postJson(route('tasks.store'), [])
            ->assertJsonValidationErrors(['name' => 'required', 'description' => 'required']);
    }

    public function cannotCreateTaskIfUnauthenticated()
    {
        $task = Task::factory()->make();

        $params = [
            'name' => $task->name,
            'description' => $task->description,
        ];

        $this->postJson(route('tasks.store'), $params)
            ->assertUnauthorized();

    }

    /** @test */
    public function canCreateTaskWithPhotoIfAuthenticated()
    {
        Storage::fake('media');
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $task = Task::factory()->make();

        $params = [
            'name' => $task->name,
            'description' => $task->description,
            'photo' =>  UploadedFile::fake()->image('photo1.png'),
        ];

        $this->postJson(route('tasks.store'), $params)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_id',
                    'name',
                    'description',
                    'due_date',
                    'completed_at',
                    'photos',
                ],
            ]);
    }

}
