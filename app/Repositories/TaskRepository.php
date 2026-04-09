<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Contracts\Pagination\Paginator;

class TaskRepository
{
    public function __construct(private readonly Task $task) {}

    public function getTaskList(int $limit, array $filters = []): Paginator
    {
        $query = $this->task->newQuery();

        if (! empty($filters['search'])) {
            $query->where(function ($builder) use ($filters) {
                $builder->where('title', 'like', '%'.$filters['search'].'%')
                    ->orWhere('description', 'like', '%'.$filters['search'].'%');
            });
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (! empty($filters['dueDateFrom'])) {
            $query->whereDate('due_date', '>=', $filters['dueDateFrom']);
        }

        if (! empty($filters['dueDateTo'])) {
            $query->whereDate('due_date', '<=', $filters['dueDateTo']);
        }

        $sortableFields = [
            'title' => 'title',
            'status' => 'status',
            'priority' => 'priority',
            'dueDate' => 'due_date',
            'createdAt' => 'created_at',
            'updatedAt' => 'updated_at',
        ];

        $sortBy = $filters['sortBy'] ?? 'created_at';
        $sortDirection = strtolower($filters['sortDirection'] ?? 'desc');
        $sortColumn = $sortableFields[$sortBy] ?? 'created_at';

        $query->orderBy($sortColumn, in_array($sortDirection, ['asc', 'desc']) ? $sortDirection : 'desc');

        return $query->paginate($limit)->withQueryString();
    }

    public function createTask(array $data): Task
    {
        return $this->task->create($data);
    }

    public function getTaskById(int $id): ?Task
    {
        return $this->task->find($id);
    }

    public function updateTask(array $data, Task $task): bool
    {
        return $task->update($data);
    }

    public function deleteTask(Task $task): bool
    {
        return (bool) $task->delete();
    }
}
