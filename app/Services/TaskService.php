<?php

namespace App\Services;

use App\Enums\Messages;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Throwable;

class TaskService
{
    public function __construct(private readonly TaskRepository $taskRepository) {}

    public function getTaskList(int $limit, array $filters = []): ?Paginator
    {
        try {
            return $this->taskRepository->getTaskList($limit, $filters);
        } catch (Throwable $exception) {
            Log::error(Messages::FAILED_TO_FETCH_TASKS, ['exception' => $exception]);

            return null;
        }
    }

    public function createTask(array $data): ?Task
    {
        try {
            return $this->taskRepository->createTask($data);
        } catch (Throwable $exception) {
            Log::error(Messages::FAILED_TO_CREATE_TASK, ['exception' => $exception]);

            return null;
        }
    }

    public function getTaskDetails(int $id): ?Task
    {
        try {
            return $this->taskRepository->getTaskById($id);
        } catch (Throwable $exception) {
            Log::error(Messages::FAILED_TO_FETCH_TASKS, ['exception' => $exception]);

            return null;
        }
    }

    public function updateTask(array $data, Task $task): bool
    {
        try {
            return $this->taskRepository->updateTask($data, $task);
        } catch (Throwable $exception) {
            Log::error(Messages::FAILED_TO_UPDATE_TASK, ['exception' => $exception]);

            return false;
        }
    }

    public function deleteTask(Task $task): bool
    {
        try {
            return $this->taskRepository->deleteTask($task);
        } catch (Throwable $exception) {
            Log::error(Messages::FAILED_TO_DELETE_TASK, ['exception' => $exception]);

            return false;
        }
    }
}
