<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TaskManagement\TaskMaster;
use App\TaskManagement\Subtask;
use App\TaskManagement\TaskUser;
use App\TaskManagement\TaskComment;
use App\Employee;

use Carbon\Carbon;
use Illuminate\Support\Str;


class RepeatTasks extends Command
{
    protected $signature = 'tasks:repeat';
    protected $description = 'Create new instances of repeating tasks';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $tasks = TaskMaster::whereNotNull('repeat_interval')
            ->where('next_due_date', '<=', Carbon::now())
            ->get();

        foreach ($tasks as $task) {
            $hasSubtasks = Subtask::where('task_id', $task->id)->exists();
            $task_id = Str::uuid();

            if ($hasSubtasks) {
                // Duplicate the task
                $newTask = $task->replicate();
                $newTask->id = $task_id;
                $newTask->due_date = $task->next_due_date;
                $newTask->save();

                // Duplicate subtasks
                $subtasks = Subtask::where('task_id', $task->id)->get();
                foreach ($subtasks as $subtask) {
                    $newSubtask = $subtask->replicate();
                    $newSubtask->task_id = $task_id;
                    $newSubtask->save();
                }

                // Duplicate assigned users
                $assignedUsers = TaskUser::where('task_id', $task->id)->get();
                foreach ($assignedUsers as $user) {
                    TaskUser::create([
                        'task_id' => $task_id,
                        'nik' => $user->nik,
                    ]);
                }

                // Update next due date based on repeat interval
                switch ($task->repeat_interval) {
                    case 'daily':
                        $task->due_date = Carbon::parse($task->due_date)->addDay();
                        break;
                    case 'weekly':
                        $task->due_date = Carbon::parse($task->due_date)->addWeek();
                        break;
                    case 'monthly':
                        $task->due_date = Carbon::parse($task->due_date)->addMonth();
                        break;
                }

                $task->save();
            }
        }

        $this->info('Repeat tasks processed successfully.');
    }

}
