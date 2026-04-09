<?php

namespace App\Http\Controllers;

use App\Enums\Messages;
use App\Enums\TaskEnums;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Response\HandleResponse;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService) {}

    public function index(Request $request): JsonResource|Response
    {
        $filters = $request->only([
            'search',
            'status',
            'priority',
            'dueDateFrom',
            'dueDateTo',
            'sortBy',
            'sortDirection',
        ]);

        $limit = (int) $request->input('limit', TaskEnums::LIMIT_PER_PAGE);

        $tasks = $this->taskService->getTaskList($limit, $filters);

        return $tasks
            ? HandleResponse::success(TaskResource::collection($tasks))
            : HandleResponse::error(Messages::FAILED_TO_FETCH_TASKS);
    }

    public function store(StoreTaskRequest $request): JsonResource|JsonResponse
    {
        $task = $this->taskService->createTask($request->validated());

        return $task
            ? HandleResponse::success(new TaskResource($task), code: Response::HTTP_CREATED)
            : HandleResponse::error(Messages::FAILED_TO_CREATE_TASK);
    }

    public function show(Task $task): JsonResource|JsonResponse
    {
        $taskDetails = $this->taskService->getTaskDetails($task->id);

        return $taskDetails
            ? HandleResponse::success(new TaskResource($taskDetails))
            : HandleResponse::error(Messages::FAILED_TO_FETCH_TASKS);
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $isUpdated = $this->taskService->updateTask($request->validated(), $task);

        return $isUpdated
            ? HandleResponse::success(message: Messages::SUCCESS_TO_UPDATE_TASK)
            : HandleResponse::error(Messages::FAILED_TO_UPDATE_TASK);
    }

    public function destroy(Task $task): JsonResponse
    {
        $isDeleted = $this->taskService->deleteTask($task);

        return $isDeleted
            ? HandleResponse::success(message: Messages::SUCCESS_TO_DELETE_TASK)
            : HandleResponse::error(Messages::FAILED_TO_DELETE_TASK);
    }
}
