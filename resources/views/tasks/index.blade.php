<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Dashboard</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/tasks-dashboard.css') }}">

</head>

<body>
    <div class="page">
        <section class="shell">
            @php
                use App\Enums\TaskPriority;
                use App\Enums\TaskStatus;

                $pageTasks = collect($tasks->items());
                $activeCount = $pageTasks->filter(fn($task) => $task->status !== TaskStatus::COMPLETED)->count();
                $doneCount = $pageTasks->filter(fn($task) => $task->status === TaskStatus::COMPLETED)->count();
            @endphp

            <header class="hero">
                <div class="hero-top">
                    <div>
                        <div class="eyebrow">Qtec technical assessment</div>
                        <h1>Task management dashboard</h1>
                        <p>
                            A clean and intuitive interface to create, update, and remove tasks, with status tracking,
                            filters, and pagination. The page uses your existing service and repository layer through a
                            dedicated dashboard controller.
                        </p>
                    </div>

                    <div class="stats">
                        <div class="stat"><strong>{{ $tasks->total() }}</strong><span>Total matching</span></div>
                        <div class="stat"><strong>{{ $activeCount }}</strong><span>Open on page</span></div>
                        <div class="stat"><strong>{{ $doneCount }}</strong><span>Completed on page</span></div>
                    </div>
                </div>
            </header>

            <div class="content">
                <aside>
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">{{ $editingTask ? 'Update task' : 'Create task' }}</h2>
                            <p class="card-subtitle">Use the form below to manage your tasks.</p>
                        </div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="msg success show">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                                <div class="msg error show">{{ session('error') }}</div>
                            @endif

                            @if ($editingTask)
                                <div class="edit-banner">
                                    Editing task #{{ $editingTask->id }}. Update the values and save, or
                                    <a class="btn-link"
                                        href="{{ route('task-dashboard.index', request()->except('edit')) }}">cancel
                                        edit</a>.
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="msg error show">
                                    <strong>Please fix the highlighted validation issues.</strong>
                                    <ul class="validation-list">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST"
                                action="{{ $editingTask ? route('task-dashboard.update', $editingTask) : route('task-dashboard.store') }}">
                                @csrf
                                @if ($editingTask)
                                    @method('PUT')
                                @endif

                                <div class="field-grid">
                                    <div class="field">
                                        <label for="title">Title</label>
                                        <input id="title" name="title" type="text"
                                            value="{{ old('title', $editingTask->title ?? '') }}"
                                            placeholder="Prepare sprint planning">
                                    </div>

                                    <div class="field">
                                        <label for="description">Description</label>
                                        <textarea id="description" name="description" placeholder="Optional task details">{{ old('description', $editingTask->description ?? '') }}</textarea>
                                    </div>

                                    <div class="field-grid cols-2">
                                        <div class="field">
                                            <label for="status">Status</label>
                                            <select id="status" name="status">
                                                <option value="1" @selected(old('status', $editingTask?->status?->value) == 1)>Pending</option>
                                                <option value="2" @selected(old('status', $editingTask?->status?->value) == 2)>In Progress</option>
                                                <option value="3" @selected(old('status', $editingTask?->status?->value) == 3)>Completed</option>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label for="priority">Priority</label>
                                            <select id="priority" name="priority">
                                                <option value="1" @selected(old('priority', $editingTask?->priority?->value) == 1)>Low</option>
                                                <option value="2" @selected(old('priority', $editingTask?->priority?->value) == 2)>Medium</option>
                                                <option value="3" @selected(old('priority', $editingTask?->priority?->value) == 3)>High</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label for="dueDate">Due date</label>
                                        <input id="dueDate" name="dueDate" type="date"
                                            value="{{ old('dueDate', optional($editingTask?->due_date)->toDateString()) }}">
                                    </div>
                                </div>

                                <div class="actions">
                                    <button class="btn btn-primary"
                                        type="submit">{{ $editingTask ? 'Update task' : 'Save task' }}</button>
                                    <a class="btn btn-secondary" href="{{ route('task-dashboard.index') }}">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card card-spaced">
                        <div class="card-header">
                            <h2 class="card-title">Filters</h2>
                            <p class="card-subtitle">Refine the list by search, status, priority, dates, sort order, and
                                page size.</p>
                        </div>

                        <div class="card-body filters">
                            <form method="GET" action="{{ route('task-dashboard.index') }}">
                                <div class="filter-grid">
                                    <div class="field">
                                        <label for="search">Search</label>
                                        <input id="search" name="search" type="search"
                                            value="{{ $filters['search'] ?? '' }}"
                                            placeholder="Search title or description">
                                    </div>

                                    <div class="field">
                                        <label for="status-filter">Status</label>
                                        <select id="status-filter" name="status">
                                            <option value="">All</option>
                                            <option value="1" @selected(($filters['status'] ?? '') === '1')>Pending</option>
                                            <option value="2" @selected(($filters['status'] ?? '') === '2')>In Progress</option>
                                            <option value="3" @selected(($filters['status'] ?? '') === '3')>Completed</option>
                                        </select>
                                    </div>

                                    <div class="field">
                                        <label for="priority-filter">Priority</label>
                                        <select id="priority-filter" name="priority">
                                            <option value="">All</option>
                                            <option value="1" @selected(($filters['priority'] ?? '') === '1')>Low</option>
                                            <option value="2" @selected(($filters['priority'] ?? '') === '2')>Medium</option>
                                            <option value="3" @selected(($filters['priority'] ?? '') === '3')>High</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="filter-grid secondary spaced-top">
                                    <div class="field">
                                        <label for="dueDateFrom">Due from</label>
                                        <input id="dueDateFrom" name="dueDateFrom" type="date"
                                            value="{{ $filters['dueDateFrom'] ?? '' }}">
                                    </div>

                                    <div class="field">
                                        <label for="dueDateTo">Due to</label>
                                        <input id="dueDateTo" name="dueDateTo" type="date"
                                            value="{{ $filters['dueDateTo'] ?? '' }}">
                                    </div>

                                    <div class="field">
                                        <label for="sortBy">Sort by</label>
                                        <select id="sortBy" name="sortBy">
                                            <option value="createdAt" @selected(($filters['sortBy'] ?? 'createdAt') === 'createdAt')>Newest</option>
                                            <option value="updatedAt" @selected(($filters['sortBy'] ?? '') === 'updatedAt')>Recently updated
                                            </option>
                                            <option value="dueDate" @selected(($filters['sortBy'] ?? '') === 'dueDate')>Due date</option>
                                            <option value="title" @selected(($filters['sortBy'] ?? '') === 'title')>Title</option>
                                        </select>
                                    </div>

                                    <div class="field">
                                        <label for="sortDirection">Direction</label>
                                        <select id="sortDirection" name="sortDirection">
                                            <option value="desc" @selected(($filters['sortDirection'] ?? 'desc') === 'desc')>Descending</option>
                                            <option value="asc" @selected(($filters['sortDirection'] ?? '') === 'asc')>Ascending</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="filter-grid secondary spaced-top">
                                    <div class="field">
                                        <label for="limit">Page size</label>
                                        <select id="limit" name="limit">
                                            <option value="5" @selected(($filters['limit'] ?? '10') === '5')>5</option>
                                            <option value="10" @selected(($filters['limit'] ?? '10') === '10')>10</option>
                                            <option value="15" @selected(($filters['limit'] ?? '') === '15')>15</option>
                                            <option value="25" @selected(($filters['limit'] ?? '') === '25')>25</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="actions">
                                    <button class="btn btn-primary" type="submit">Apply filters</button>
                                    <a class="btn btn-secondary" href="{{ route('task-dashboard.index') }}">Clear</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </aside>

                <main>
                    <div class="card">
                        <div class="card-header">
                            <div class="toolbar">
                                <div>
                                    <h2 class="card-title">Task list</h2>
                                    <p class="card-subtitle">Manage tasks with a focused, readable overview.</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            @if ($tasks->count())
                                <div class="task-list">
                                    @foreach ($tasks as $task)
                                        @php
                                            $statusClass = match ($task->status) {
                                                TaskStatus::IN_PROGRESS => 'in-progress',
                                                TaskStatus::COMPLETED => 'completed',
                                                default => 'pending',
                                            };
                                            $priorityClass = match ($task->priority) {
                                                TaskPriority::LOW => 'low',
                                                TaskPriority::MEDIUM => 'medium',
                                                TaskPriority::HIGH => 'high',
                                            };
                                            $isOverdue =
                                                $task->due_date &&
                                                $task->due_date->isPast() &&
                                                $task->status !== TaskStatus::COMPLETED;
                                        @endphp

                                        <article class="task-card">
                                            <div class="task-top">
                                                <div>
                                                    <h3 class="task-title">{{ $task->title }}</h3>
                                                    <p class="task-desc">
                                                        {{ $task->description ?: 'No description provided.' }}</p>
                                                </div>

                                                <div class="task-actions task-actions-compact">
                                                    <a class="btn btn-secondary"
                                                        href="{{ route('task-dashboard.index', array_merge(request()->query(), ['edit' => $task->id])) }}">Edit</a>
                                                    <form method="POST"
                                                        action="{{ route('task-dashboard.destroy', $task) }}"
                                                        onsubmit="return confirm('Delete this task?');"
                                                        class="task-delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger" type="submit">Delete</button>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="badges">
                                                <span
                                                    class="badge {{ $statusClass }}">{{ $task->status->getLabel() }}</span>
                                                <span
                                                    class="badge {{ $priorityClass }}">{{ $task->priority->getLabel() }}</span>
                                                @if ($isOverdue)
                                                    <span class="badge overdue">Overdue</span>
                                                @endif
                                            </div>

                                            <div class="task-meta">
                                                <div><strong>Due
                                                        date</strong><br>{{ $task->due_date?->toDateString() ?? 'Not set' }}
                                                </div>
                                                <div>
                                                    <strong>Created</strong><br>{{ $task->created_at?->format('M d, Y') }}
                                                </div>
                                                <div>
                                                    <strong>Updated</strong><br>{{ $task->updated_at?->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty">
                                    <strong>No tasks found</strong>
                                    Adjust your filters or create your first task from the form on the left.
                                </div>
                            @endif

                            <div class="pagination">
                                <div class="page-info">
                                    Page {{ $tasks->currentPage() }} of {{ $tasks->lastPage() }} · Showing
                                    {{ $tasks->count() }} of {{ $tasks->total() }}
                                </div>
                                <div class="pagination-actions">
                                    @if ($tasks->previousPageUrl())
                                        <a class="btn btn-secondary"
                                            href="{{ $tasks->previousPageUrl() }}">Previous</a>
                                    @else
                                        <span class="btn btn-secondary btn-disabled">Previous</span>
                                    @endif

                                    @if ($tasks->nextPageUrl())
                                        <a class="btn btn-secondary" href="{{ $tasks->nextPageUrl() }}">Next</a>
                                    @else
                                        <span class="btn btn-secondary btn-disabled">Next</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </section>
    </div>
</body>

</html>
