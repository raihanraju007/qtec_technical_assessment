<?php

namespace App\Enums;

enum Messages: string
{
    const string FAILED_TO_FETCH_TASKS = 'Failed to fetch tasks.';

    const string FAILED_TO_CREATE_TASK = 'Failed to create task.';

    const string FAILED_TO_UPDATE_TASK = 'Failed to update task.';

    const string FAILED_TO_DELETE_TASK = 'Failed to delete task.';

    const string SUCCESS_TO_CREATE_TASK = 'Successfully created the task.';

    const string SUCCESS_TO_UPDATE_TASK = 'Successfully updated the task.';

    const string SUCCESS_TO_DELETE_TASK = 'Successfully deleted the task.';
}
