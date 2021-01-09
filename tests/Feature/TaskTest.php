<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_create_a_task(): void
    {
        $user = User::factory()->create();

        Passport::actingAs($user);

        $attributes = Task::factory()->raw();

        $response = $this->postJson('api/tasks', $attributes);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                                      'data' => [
                                          'title',
                                          'description',
                                          'status',
                                          'user_id',
                                      ],
                                  ]);

        $this->assertDatabaseHas('tasks', [
            'title'       => $attributes['title'],
            'description' => $attributes['description'],
        ]);
    }

    /** @test */
    public function a_task_requires_a_title(): void
    {
        $user = User::factory()->create();

        Passport::actingAs($user);

        $attributes = Task::factory()
                          ->raw([
                                    'title' => '',
                                ]);

        $response = $this->postJson('api/tasks', $attributes);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title']);

        $this->assertDatabaseMissing('tasks', [
            'title' => $attributes['title'],
        ]);
    }

    /** @test */
    public function an_authenticated_user_can_get_a_task(): void
    {
        $user = User::factory()->create();

        Passport::actingAs($user);

        $task = Task::factory()->create();

        $response = $this->getJson("api/tasks/{$task->id}");

        $response
            ->assertOk()
            ->assertJsonStructure([
                                      'data' => [
                                          'title',
                                          'description',
                                          'status',
                                          'user_id',
                                      ],
                                  ]);
    }
}
