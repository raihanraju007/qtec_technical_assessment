# Task Management System (Qtec Technical Assessment)

This is a simple task management system built with Laravel.
It includes:
- REST API for task operations
- Web dashboard for task management
- Task status tracking (Pending, In Progress, Completed)
- Filtering, sorting, and pagination
- Feature tests for core flows

## Why I Built API First

I intentionally designed this project with a strong API layer.

Reason:
- Most modern applications use frontend frameworks like Next.js, Angular, and Vue.
- These frontends consume data from APIs.
- A clean API makes the system reusable for web, mobile, and future integrations.

Personal background:
- For the last 2 years, I have mostly worked on API-based backend development.
- Because of that experience, API design and structured backend logic are my strongest area.
- For this assessment, I used that strength so judges can easily evaluate my architecture, logic, and reliability approach.

## Tech Stack

- Laravel 13
- PHP 8.3
- MySQL/SQLite
- Blade (server-rendered dashboard)
- PHPUnit (feature tests)

## Project Structure

```text
qtec_technical_assessment/
|-- app/
|   |-- Enums/
|   |   |-- Messages.php
|   |   |-- TaskEnums.php
|   |   |-- TaskPriority.php
|   |   `-- TaskStatus.php
|   |-- Http/
|   |   |-- Controllers/
|   |   |   |-- Api/
|   |   |   |   `-- TaskController.php
|   |   |   `-- Web/
|   |   |       `-- TaskDashboardController.php
|   |   |-- Requests/
|   |   |   `-- Task/
|   |   |       |-- StoreTaskRequest.php
|   |   |       `-- UpdateTaskRequest.php
|   |   `-- Resources/
|   |       `-- TaskResource.php
|   |-- Models/
|   |   |-- Task.php
|   |   `-- User.php
|   |-- Repositories/
|   |   `-- TaskRepository.php
|   |-- Response/
|   |   `-- HandleResponse.php
|   |-- Services/
|   |   `-- TaskService.php
|   `-- Traits/
|       |-- Auditor.php
|       `-- SnakeCaseValidatedDataTrait.php
|-- database/
|   |-- factories/
|   |   |-- TaskFactory.php
|   |   `-- UserFactory.php
|   |-- migrations/
|   |   |-- 0001_01_01_000000_create_users_table.php
|   |   |-- 0001_01_01_000001_create_cache_table.php
|   |   |-- 0001_01_01_000002_create_jobs_table.php
|   |   `-- 2026_04_09_010000_create_tasks_table.php
|   `-- seeders/
|       |-- DatabaseSeeder.php
|       `-- TaskSeeder.php
|-- public/
|   |-- css/
|   |   `-- tasks-dashboard.css
|   |-- index.php
|   `-- robots.txt
|-- resources/
|   |-- css/
|   |   `-- app.css
|   |-- js/
|   |   |-- app.js
|   |   `-- bootstrap.js
|   `-- views/
|       `-- tasks/
|           `-- index.blade.php
|-- routes/
|   |-- api.php
|   |-- console.php
|   `-- web.php
|-- tests/
|   |-- Feature/
|   |   `-- TaskApiTest.php
|   |-- Unit/
|   |   `-- ExampleTest.php
|   `-- TestCase.php
|-- README.md
|-- artisan
|-- composer.json
|-- package.json
`-- phpunit.xml
```

## Setup

1. Install dependencies
```bash
composer install
npm install
```

2. Prepare environment
```bash
cp .env.example .env
php artisan key:generate
```

3. Run database
```bash
php artisan migrate
php artisan db:seed
```

4. Start app
```bash
php artisan serve
```

## Access

- Dashboard: http://127.0.0.1:8000/tasks
- API Base: http://127.0.0.1:8000/api/tasks

## API Endpoints

- GET /api/tasks
- POST /api/tasks
- GET /api/tasks/{id}
- PUT /api/tasks/{id}
- DELETE /api/tasks/{id}

### Sample API Response

```json
{
	"success": true,
	"message": "Successfully fetched data.",
	"data": {
		"id": 1,
		"title": "Create assessment API",
		"description": "Build task CRUD endpoints",
		"status": {
			"value": 2,
			"label": "In Progress",
			"labelBn": "চলমান"
		},
		"priority": {
			"value": 3,
			"label": "High",
			"labelBn": "উচ্চ"
		},
		"dueDate": "2026-04-15",
		"isOverdue": false,
		"createdAt": "2026-04-10T09:30:00Z",
		"updatedAt": "2026-04-10T10:45:00Z"
	}
}
```

## Testing

Run tests:
```bash
php artisan test
```

Main test file:
- tests/Feature/TaskApiTest.php

## Notes for Reviewers

- The system follows Controller -> Service -> Repository structure.
- Validation is separated for create and update requests.
- API and dashboard share the same core business logic.
- Audit fields (`created_by`, `updated_by`) are auto-managed by trait logic.

## Submission Links

- GitHub Repository: https://github.com/raihanraju007/qtec_technical_assessment
- Live Application: (add if deployed)
- Video Demo: (add Loom link)
