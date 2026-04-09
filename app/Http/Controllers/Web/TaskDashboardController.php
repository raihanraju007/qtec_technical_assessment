<?php

namespace App\Http\Controllers\Web;

use App\Enums\Messages;
use App\Enums\TaskEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskDashboardController extends Controller
{
    public function __construct(private readonly TaskService $taskService) {}

    public function index(Request $request): View
    {
        $filters = $request->only([
            'search',
            'status',
            'priority',
            'dueDateFrom',
            'dueDateTo',
            'sortBy',
            'sortDirection',
            'limit',
        ]);

        $limit = (int) $request->input('limit', TaskEnums::LIMIT_PER_PAGE);
        $tasks = $this->taskService->getTaskList($limit, $filters);
        $editingTaskId = $request->integer('edit');
        $editingTask = $editingTaskId ? $this->taskService->getTaskDetails($editingTaskId) : null;

        return view('tasks.index', compact('tasks', 'filters', 'editingTask'));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $task = $this->taskService->createTask($request->validated());

        if (! $task) {
            return back()->withInput()->with('error', Messages::FAILED_TO_CREATE_TASK);
        }

        return redirect()
            ->route('task-dashboard.index')
            ->with('success', Messages::SUCCESS_TO_CREATE_TASK);
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $updated = $this->taskService->updateTask($request->validated(), $task);

        if (! $updated) {
            return back()->withInput()->with('error', Messages::FAILED_TO_UPDATE_TASK);
        }

        return redirect()
            ->route('task-dashboard.index')
            ->with('success', Messages::SUCCESS_TO_UPDATE_TASK);
    }

    public function destroy(Task $task): RedirectResponse
    {
        $deleted = $this->taskService->deleteTask($task);

        if (! $deleted) {
            return back()->with('error', Messages::FAILED_TO_DELETE_TASK);
        }

        return redirect()
            ->route('task-dashboard.index')
            ->with('success', Messages::SUCCESS_TO_DELETE_TASK);
    }
}
