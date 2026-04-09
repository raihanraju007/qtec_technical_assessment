<?php

namespace Tests\Feature;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_task(): void
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'Create task for test',
            'description' => 'Check create endpoint',
            'status' => TaskStatus::PENDING->value,
            'priority' => TaskPriority::HIGH->value,
            'dueDate' => now()->addDays(2)->toDateString(),
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('tasks', ['title' => 'Create task for test']);
    }

    public function test_it_can_list_tasks_with_filters(): void
    {
        Task::create([
            'title' => 'Pending task',
            'description' => 'Filter me',
            'status' => TaskStatus::PENDING->value,
            'priority' => TaskPriority::MEDIUM->value,
            'due_date' => now()->addDay()->toDateString(),
        ]);

        Task::create([
            'title' => 'Completed task',
            'description' => 'Do not include',
            'status' => TaskStatus::COMPLETED->value,
            'priority' => TaskPriority::LOW->value,
            'due_date' => now()->subDay()->toDateString(),
        ]);

        $response = $this->getJson('/api/tasks?status='.TaskStatus::PENDING->value.'&search=Filter');

        $response->assertOk();
        $response->assertJsonFragment(['title' => 'Pending task']);
        $response->assertJsonMissing(['title' => 'Completed task']);
    }

    public function test_it_can_update_task(): void
    {
        $task = Task::create([
            'title' => 'Need update',
            'description' => 'Old',
            'status' => TaskStatus::PENDING->value,
            'priority' => TaskPriority::LOW->value,
        ]);

        $response = $this->putJson('/api/tasks/'.$task->id, [
            'title' => 'Need update',
            'description' => 'New',
            'status' => TaskStatus::IN_PROGRESS->value,
            'priority' => TaskPriority::HIGH->value,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'description' => 'New',
            'status' => TaskStatus::IN_PROGRESS->value,
            'priority' => TaskPriority::HIGH->value,
        ]);
    }

    public function test_it_can_delete_task(): void
    {
        $task = Task::create([
            'title' => 'Delete me',
            'description' => 'Soft delete',
            'status' => TaskStatus::PENDING->value,
            'priority' => TaskPriority::MEDIUM->value,
        ]);

        $response = $this->deleteJson('/api/tasks/'.$task->id);

        $response->assertOk();
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }
}
