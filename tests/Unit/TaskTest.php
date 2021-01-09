<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_task_has_an_assigned_user(): void
    {
        $task = Task::factory()->create();

        $this->assertInstanceOf(User::class, $task->assignedUser);
    }
}
