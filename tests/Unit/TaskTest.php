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

    /** @test */
    public function tasks_can_be_sorted_by_statuses(): void
    {
        collect([
                    ['Todo A', Task::TODO],
                    ['Todo B', Task::DOING],
                    ['Todo C', Task::DOING],
                    ['Todo Ç', Task::DONE],
                    ['Todo 05', Task::DONE],
                    ['Todo 06', Task::TODO,],
                    ['Todo 07', Task::TODO],
                    ['Todo *', Task::DONE],
                    ['Todo >', Task::DOING],
                    ['Todo #', Task::DOING],
                ])
            ->map(fn(array $task) => Task::factory()
                                        ->create([
                                                     'title'  => $task[0],
                                                     'status' => $task[1],
                                                 ]
                                        ));

        $expected = [
            'Todo B',   // DOING
            'Todo C',   // DOING
            'Todo #',   // DOING
            'Todo >',   // DOING
            'Todo A',   // TODO
            'Todo 06',  // TODO
            'Todo 07',  // TODO
            'Todo Ç',   // DONE
            'Todo 05',  // DONE
            'Todo *',   // DONE
        ];

        $this->assertEquals(
            Task::sortByStatus()->pluck('title')->toArray(),
            $expected
        );
    }
}
