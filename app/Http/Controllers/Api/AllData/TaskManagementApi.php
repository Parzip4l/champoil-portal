<?php

namespace App\Http\Controllers\Api\AllData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

// Model
use App\TaskManagement\TaskMaster;
use App\TaskManagement\Subtask;
use App\TaskManagement\TaskUser;
use App\TaskManagement\TaskComment;
use App\Employee;

class TaskManagementApi extends Controller
{
    public function index(Request $request)
    {
        try {
            // Retrieve the token from the request and authenticate the user based on the token
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $employeeCode = $user->name;
            $employee = Employee::where('nik', $employeeCode)->first();

            if (!$employee) {
                return response()->json(['error' => 'Employee not found'], 404);
            }

            $unitBisnis = $employee->unit_bisnis;
            // Get the IDs of tasks assigned to the current user
            $assignedTaskIds = TaskUser::where('nik', $employeeCode)->pluck('task_id');

            $taskQuery = TaskMaster::whereIn('id', $assignedTaskIds)
                            ->where('company', $unitBisnis);

            // Apply filters
            if ($request->has('priority')) {
                $taskQuery->where('priority', $request->input('priority'));
            }

            if ($request->has('progress')) {
                $taskQuery->where('status', $request->input('progress'));
            }

            if ($request->has('query')) {
                $query = $request->input('query');
                $taskQuery->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('title', 'LIKE', "%{$query}%")
                                ->orWhere('description', 'LIKE', "%{$query}%");
                });
            }

            // Fetch the filtered data
            $taskData = $taskQuery->get();

            foreach ($taskData as $task) {
                $task->subtasks = Subtask::where('task_id', $task->id)->get();
                $task->total_subtasks = $task->subtasks->count();
                $task->completed_subtasks = $task->subtasks->where('status', 'Completed')->count();

                // Calculate progress percentage
                $task->progress = $task->total_subtasks > 0
                    ? ($task->completed_subtasks / $task->total_subtasks) * 100
                    : 0;

                // Get assigned users for the task
                $task->assignedUsers = TaskUser::where('task_id', $task->id)
                                                ->join('karyawan', 'task_user.nik', '=', 'karyawan.nik')
                                                ->get(['karyawan.nama', 'karyawan.gambar', 'karyawan.nik']);
                
                // Get comments
                $task->comments = TaskComment::where('task_id', $task->id)
                                ->join('karyawan', 'task_comment.nik', '=', 'karyawan.nik')
                                ->get(['task_comment.*', 'karyawan.nama as commenter_name', 'karyawan.gambar as commenter_photo']);
                // Comment count
                $task->commentCount = TaskComment::where('task_id', $task->id)->count();
                $task->remaining_days = Carbon::now()->diffInDays(Carbon::parse($task->due_date), false);

                // Tracking Start
                $activeTrackingSubtasks = $task->subtasks->filter(function ($subtask) {
                    return $subtask->time_start && $subtask->latitude_start && is_null($subtask->time_end);
                });
        
                if ($activeTrackingSubtasks->count() > 1) {
                    $task->activeTrackingError = 'There are multiple active tracking subtasks. Only one should be active.';
                } elseif ($activeTrackingSubtasks->count() == 0) {
                    $task->activeTrackingError = 'No active tracking subtasks found.';
                } else {
                    // Get the single active tracking subtask
                    $activeTrackingSubtask = $activeTrackingSubtasks->first();
        
                    // Calculate the duration for which the tracking has been active
                    $activeTrackingSubtask->tracking_duration = Carbon::parse($activeTrackingSubtask->time_start)->diffForHumans();
        
                    // Include the active tracking subtask in the task data
                    $task->activeTrackingSubtask = $activeTrackingSubtask;
                }
            }

            $groupedTasks = $taskData->groupBy('status');
            $subtask = Subtask::all();
            $mentionUsers = Employee::select('nik', 'nama')->get();

            // Only calculate and fetch tasks for current and previous periods
            $currentDate = Carbon::now();
            
            // Calculate statistics for assigned tasks only
            $currentTaskData = TaskMaster::whereIn('id', $assignedTaskIds)
                                        ->where('company', $unitBisnis)
                                        ->whereMonth('created_at', $currentDate->month)
                                        ->whereYear('created_at', $currentDate->year)
                                        ->get();

            // Calculate totals for current period
            $totalTasks = $currentTaskData->count();
            $completedTasks = $currentTaskData->where('status', 'Completed')->count();
            $inProgressTasks = $currentTaskData->where('status', 'In Progress')->count();
            $overdueTasks = $currentTaskData->filter(function ($task) {
                return Carbon::parse($task->due_date)->isPast() && $task->status !== 'Completed';
            })->count();

            // Fetch tasks for previous period
            $previousDate = $currentDate->copy()->subMonth();
            $previousTaskData = TaskMaster::whereIn('id', $assignedTaskIds)
                                        ->where('company', $unitBisnis)
                                        ->whereMonth('created_at', $previousDate->month)
                                        ->whereYear('created_at', $previousDate->year)
                                        ->get();

            // Calculate totals for previous period
            $previousTotalTasks = $previousTaskData->count();
            $previousCompletedTasks = $previousTaskData->where('status', 'Completed')->count();
            $previousInProgressTasks = $previousTaskData->where('status', 'In Progress')->count();
            $previousOverdueTasks = $previousTaskData->filter(function ($task) {
                return Carbon::parse($task->due_date)->isPast() && $task->status !== 'Completed';
            })->count();
            
            return response()->json([
                'taskData' => $taskData,
                'subtask' => $subtask,
                'groupedTasks' => $groupedTasks,
                'totalTasks' => $totalTasks,
                'completedTasks' => $completedTasks,
                'inProgressTasks' => $inProgressTasks,
                'overdueTasks' => $overdueTasks,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function allTask(Request $request)
    {
        try {
            // Retrieve the token from the request and authenticate the user based on the token
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $employeeCode = $user->name;
            $employee = Employee::where('nik', $employeeCode)->first();

            if (!$employee) {
                return response()->json(['error' => 'Employee not found'], 404);
            }

            $unitBisnis = $employee->unit_bisnis;
            // Get the IDs of tasks assigned to the current user
            $assignedTaskIds = TaskUser::where('nik', $employeeCode)->pluck('task_id');

            $taskQuery = TaskMaster::whereIn('id', $assignedTaskIds)
                            ->where('company', $unitBisnis);

            // Apply filters
            if ($request->has('priority')) {
                $taskQuery->where('priority', $request->input('priority'));
            }

            if ($request->has('progress')) {
                $taskQuery->where('status', $request->input('progress'));
            }

            if ($request->has('query')) {
                $query = $request->input('query');
                $taskQuery->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('title', 'LIKE', "%{$query}%")
                                ->orWhere('description', 'LIKE', "%{$query}%");
                });
            }

            // Fetch the filtered data
            $taskData = $taskQuery->get();

            foreach ($taskData as $task) {
                $task->subtasks = Subtask::where('task_id', $task->id)->get();
                $task->total_subtasks = $task->subtasks->count();
                $task->completed_subtasks = $task->subtasks->where('status', 'Completed')->count();

                // Calculate progress percentage
                $task->progress = $task->total_subtasks > 0
                    ? ($task->completed_subtasks / $task->total_subtasks) * 100
                    : 0;

                // Get assigned users for the task
                $task->assignedUsers = TaskUser::where('task_id', $task->id)
                                                ->join('karyawan', 'task_user.nik', '=', 'karyawan.nik')
                                                ->get(['karyawan.nama', 'karyawan.gambar', 'karyawan.nik']);
                
                // Get comments
                $task->comments = TaskComment::where('task_id', $task->id)
                                ->join('karyawan', 'task_comment.nik', '=', 'karyawan.nik')
                                ->get(['task_comment.*', 'karyawan.nama as commenter_name', 'karyawan.gambar as commenter_photo']);
                // Comment count
                $task->commentCount = TaskComment::where('task_id', $task->id)->count();
                $task->remaining_days = Carbon::now()->diffInDays(Carbon::parse($task->due_date), false);

                // Tracking Start
                $activeTrackingSubtasks = $task->subtasks->filter(function ($subtask) {
                    return $subtask->time_start && $subtask->latitude_start && is_null($subtask->time_end);
                });
        
                if ($activeTrackingSubtasks->count() > 1) {
                    $task->activeTrackingError = 'There are multiple active tracking subtasks. Only one should be active.';
                } elseif ($activeTrackingSubtasks->count() == 0) {
                    $task->activeTrackingError = 'No active tracking subtasks found.';
                } else {
                    // Get the single active tracking subtask
                    $activeTrackingSubtask = $activeTrackingSubtasks->first();
        
                    // Calculate the duration for which the tracking has been active
                    $activeTrackingSubtask->tracking_duration = Carbon::parse($activeTrackingSubtask->time_start)->diffForHumans();
        
                    // Include the active tracking subtask in the task data
                    $task->activeTrackingSubtask = $activeTrackingSubtask;
                }
            }

            $groupedTasks = $taskData->groupBy('status');
            $subtask = Subtask::all();
            $mentionUsers = Employee::select('nik', 'nama')->get();

            // Only calculate and fetch tasks for current and previous periods
            $currentDate = Carbon::now();
            
            // Calculate statistics for assigned tasks only
            $currentTaskData = TaskMaster::whereIn('id', $assignedTaskIds)
                                        ->where('company', $unitBisnis)
                                        ->whereMonth('created_at', $currentDate->month)
                                        ->whereYear('created_at', $currentDate->year)
                                        ->get();

            // Calculate totals for current period
            $totalTasks = $currentTaskData->count();
            $completedTasks = $currentTaskData->where('status', 'Completed')->count();
            $inProgressTasks = $currentTaskData->where('status', 'In Progress')->count();
            $overdueTasks = $currentTaskData->filter(function ($task) {
                return Carbon::parse($task->due_date)->isPast() && $task->status !== 'Completed';
            })->count();

            // Fetch tasks for previous period
            $previousDate = $currentDate->copy()->subMonth();
            $previousTaskData = TaskMaster::whereIn('id', $assignedTaskIds)
                                        ->where('company', $unitBisnis)
                                        ->whereMonth('created_at', $previousDate->month)
                                        ->whereYear('created_at', $previousDate->year)
                                        ->get();

            // Calculate totals for previous period
            $previousTotalTasks = $previousTaskData->count();
            $previousCompletedTasks = $previousTaskData->where('status', 'Completed')->count();
            $previousInProgressTasks = $previousTaskData->where('status', 'In Progress')->count();
            $previousOverdueTasks = $previousTaskData->filter(function ($task) {
                return Carbon::parse($task->due_date)->isPast() && $task->status !== 'Completed';
            })->count();

            // Calculate percentages
            $totalTasksPercentage = $previousTotalTasks > 0 ? (($totalTasks - $previousTotalTasks) / $previousTotalTasks) * 100 : 0;
            $completedTasksPercentage = $previousCompletedTasks > 0 ? (($completedTasks - $previousCompletedTasks) / $previousCompletedTasks) * 100 : 0;
            $inProgressTasksPercentage = $previousInProgressTasks > 0 ? (($inProgressTasks - $previousInProgressTasks) / $previousInProgressTasks) * 100 : 0;
            $overdueTasksPercentage = $previousOverdueTasks > 0 ? (($overdueTasks - $previousOverdueTasks) / $previousOverdueTasks) * 100 : 0;
            
            return response()->json([
                'taskData' => $taskData,
                'groupedTasks' => $groupedTasks,
                'totalTasks' => $totalTasks,
                'completedTasks' => $completedTasks,
                'inProgressTasks' => $inProgressTasks,
                'overdueTasks' => $overdueTasks,
                'totalTasksPercentage' => $totalTasksPercentage,
                'completedTasksPercentage' => $completedTasksPercentage,
                'inProgressTasksPercentage' => $inProgressTasksPercentage,
                'overdueTasksPercentage' => $overdueTasksPercentage,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }


    // Detail
    public function show($taskId)
    {
        try {
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $employeeCode = $user->name;
            $employee = Employee::where('nik', $employeeCode)->first();

            if (!$employee) {
                return response()->json(['error' => 'Employee not found'], 404);
            }

            $unitBisnis = $employee->unit_bisnis;

            // Fetch the task details
            $task = TaskMaster::where('id', $taskId)
                            ->where('company', $unitBisnis)
                            ->first();

            if (!$task) {
                return response()->json(['error' => 'Task not found'], 404);
            }

            // Fetch the subtasks related to the task
            $task->subtasks = Subtask::where('task_id', $task->id)->get();
            $task->total_subtasks = $task->subtasks->count();
            $task->completed_subtasks = $task->subtasks->where('status', 'Completed')->count();

            // Calculate progress percentage
            $task->progress = $task->total_subtasks > 0
                ? ($task->completed_subtasks / $task->total_subtasks) * 100
                : 0;

            // Get assigned users for the task
            $task->assignedUsers = TaskUser::where('task_id', $task->id)
                                            ->join('karyawan', 'task_user.nik', '=', 'karyawan.nik')
                                            ->get(['karyawan.nama', 'karyawan.gambar', 'karyawan.nik']);

            // Get comments
            $task->comments = TaskComment::where('task_id', $task->id)
                            ->join('karyawan', 'task_comment.nik', '=', 'karyawan.nik')
                            ->get(['task_comment.*', 'karyawan.nama as commenter_name', 'karyawan.gambar as commenter_photo']);
            // Comment count
            $task->commentCount = TaskComment::where('task_id', $task->id)->count();
            $task->remaining_days = Carbon::now()->diffInDays(Carbon::parse($task->due_date), false);

            return response()->json($task);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Create Master Task
    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();
        $code = $user->employee_code;
        $company = Employee::where('nik', $code)->first();

        if (!$company) {
            return response()->json(['error' => 'Company not found for the current user.'], 404);
        }
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Simpan Task
            $task_id = Str::uuid();

            $task = new TaskMaster;
            $task->id = $task_id;
            $task->title = $request->title;
            $task->description = $request->description;
            $task->due_date = $request->due_date;
            $task->priority = $request->priority;
            $task->kategori = $request->kategori;
            $task->company = $company->unit_bisnis;

            if ($request->hasFile('attachments')) {
                $file = $request->file('attachments');
                
                // Mendapatkan ekstensi file
                $extension = $file->getClientOriginalExtension();
            
            
                // Jika file adalah PDF atau JPG maka simpan
                $path = $file->store('public/files');
                $task->attachments = $path;
            }

            $task->save();

            foreach ($request->user as $userNik) {
                TaskUser::create([
                    'task_id' => $task_id,
                    'nik' => $userNik,
                ]);
            }

            DB::commit();
            return response()->json(['success' => 'Task berhasil dibuat', 'task' => $task], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {

        // Authenticate user
        $user = Auth::guard('api')->user();
        $code = $user->employee_code;
        $company = Employee::where('nik', $code)->first();
        
        // Validate request data
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        // Find the task to be updated
        $task = TaskMaster::find($id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        DB::beginTransaction();
        try {
            // Update task details
            $task->title = $request->input('title');
            $task->description = $request->input('description');
            $task->due_date = $request->input('due_date');
            $task->priority = $request->input('priority');
            $task->kategori = $request->input('kategori');

            // Handle file upload
            if ($request->hasFile('attachments')) {
                $file = $request->file('attachments');
                $extension = $file->getClientOriginalExtension();

                if (!in_array($extension, ['pdf', 'jpg', 'zip', 'rar'])) {
                    return response()->json(['error' => 'Only PDF, JPG, ZIP, and RAR files are allowed.'], 400);
                }

                $filename = time() . '-' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $filename, 'public');
                $task->attachments = $filename;
            }

            $task->save();

            // Update assigned users
            $assignedUsers = $request->input('user');

            // Delete existing assignments
            TaskUser::where('task_id', $id)->delete();

            // Add new assignments
            foreach ($assignedUsers as $nik) {
                TaskUser::create([
                    'task_id' => $task->id,
                    'nik' => $nik,
                ]);
            }

            // Check if there are subtasks and if repeat interval is provided
            $hasSubtasks = Subtask::where('task_id', $task->id)->exists();

            if ($hasSubtasks && $request->input('repeat_interval')) {
                $task->repeat_interval = $request->input('repeat_interval');
                switch ($task->repeat_interval) {
                    case 'daily':
                        $task->next_due_date = Carbon::parse($task->due_date)->addDay();
                        break;
                    case 'weekly':
                        $task->next_due_date = Carbon::parse($task->due_date)->addWeek();
                        break;
                    case 'monthly':
                        $task->next_due_date = Carbon::parse($task->due_date)->addMonth();
                        break;
                }
                $task->save();
            } elseif (!$hasSubtasks) {
                // Return error if no subtasks
                return response()->json(['error' => 'Task does not have subtasks.'], 400);
            }

            DB::commit();
            return response()->json(['success' => 'Task updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function storeSubtask(Request $request)
    {

        DB::beginTransaction();
        try {
            for ($i = 0; $i < count($request->title); $i++) {
                $subtask = new Subtask;
                $subtask->id = (string) Str::uuid();
                $subtask->task_id = $request->task_id;
                $subtask->title = $request->title[$i];
                $subtask->description = $request->description[$i] ?? null;
                $subtask->due_date = $request->due_date[$i] ?? null;

                // Handle file upload for each subtask
                if ($request->hasFile("attachments.$i")) {
                    $file = $request->file("attachments.$i");

                    // Save the file
                    $path = $file->store('public/file');
                    $subtask->attachments = $path;
                }

                $subtask->save();
            }

            // Update task status
            $taskData = TaskMaster::find($request->task_id);

            if ($taskData && $taskData->status == 'Completed') {
                $taskData->status = 'In Progress'; // Correct status change
                $taskData->save();
            }

            DB::commit();
            return response()->json(['success' => 'Subtask created successfully'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function deleteSubtask($id)
    {
        DB::beginTransaction();
        try {
            // Find the subtask by ID
            $subtask = Subtask::find($id);
            if (!$subtask) {
                return response()->json(['error' => 'Subtask not found'], 404);
            }

            // Optionally, delete the associated file
            if ($subtask->attachments) {
                Storage::delete($subtask->attachments);
            }

            // Delete the subtask
            $subtask->delete();

            DB::commit();
            return response()->json(['success' => 'Subtask deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function showSubtask($id)
    {
        try {
            // Find the subtask by ID
            $subtask = Subtask::find($id);
            if (!$subtask) {
                return response()->json(['error' => 'Subtask not found'], 404);
            }
    
            // Initialize variables for tracking durations
            $trackingData = [
                'tracking_duration' => null,
                'total_duration' => null
            ];
    
            // Check if time_start is set
            if ($subtask->time_start) {
                $start = Carbon::parse($subtask->time_start);
    
                if ($subtask->time_end) {
                    // Calculate total duration
                    $end = Carbon::parse($subtask->time_end);
                    $trackingData['total_duration'] = $start->diff($end)->format('%H:%I:%S');
                } else {
                    // Calculate elapsed time from start to now
                    $trackingData['tracking_duration'] = $start->diff(Carbon::now())->format('%H:%I:%S');
                }
            }
    
            return response()->json([
                'subtask' => $subtask,
                'tracking_data' => $trackingData
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // Find the subtask by ID
            $subtask = Subtask::find($id);
            if (!$subtask) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Subtask not found'], 404);
            }

            // Validate and update status
            $status = $request->input('status');
            if (!in_array($status, ['Pending', 'In Progress', 'Completed'])) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Invalid status value'], 400);
            }
            $subtask->status = $status;
            $subtask->save();

            // Find the parent task
            $task = TaskMaster::find($subtask->task_id);

            if ($task) {
                // Calculate subtasks status
                $totalSubtasks = Subtask::where('task_id', $task->id)->count();
                $completedSubtasks = Subtask::where('task_id', $task->id)
                                            ->where('status', 'Completed')
                                            ->count();

                // Update task status based on subtasks status
                if ($totalSubtasks === 0) {
                    $task->status = 'TO DO';
                } elseif ($completedSubtasks === 0) {
                    $task->status = 'TO DO';
                } elseif ($completedSubtasks < $totalSubtasks) {
                    $task->status = 'In Progress';
                } else {
                    $task->status = 'Completed';
                }

                // Save the task status and progress
                $task->progress = ($totalSubtasks > 0) ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;
                $task->save();
            }

            // Commit transaction
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Status updated successfully'], 200);
        } catch (\Exception $e) {
            // Rollback transaction if there's an error
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $taskMaster = TaskMaster::find($id);

            if ($taskMaster) {
                // Delete related records first
                Subtask::where('task_id', $id)->delete();
                TaskUser::where('task_id', $id)->delete();
                TaskComment::where('task_id', $id)->delete();

                // Delete the task master
                $taskMaster->delete();

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Task and related data deleted successfully'], 200);
            }

            return response()->json(['success' => false, 'message' => 'Task not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    // Download Attachment
    public function downloadAttachment($id)
    {
        try {
            $task = TaskMaster::findOrFail($id);

            if ($task->attachments) {
                return Storage::download($task->attachments);
            } else {
                return response()->json(['error' => 'No attachment found.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function downloadAttachmentSubtask($id)
    {
        try {
            $subtask = Subtask::findOrFail($id);

            if ($subtask->attachments) {
                return Storage::download($subtask->attachments);
            } else {
                return response()->json(['error' => 'No attachment found.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    // Tracking Data
    public function startTracking(Request $request, $id)
    {
        $subtask = Subtask::findOrFail($id);
        $subtask->latitude_start = $request->input('latitude_start');
        $subtask->longitude_start = $request->input('longitude_start');
        $subtask->status = 'In Progress';
        $subtask->time_start = Carbon::now();
        $subtask->save();
    
        return response()->json(['success' => 'Tracking started.'], 200);
    }

    public function pauseTracking(Request $request, $id)
    {
        try {
            // Find the subtask
            $subtask = Subtask::findOrFail($id);

            // Set pause start time
            $subtask->pause_start = Carbon::now();
            $subtask->status = 'Paused'; // Update status to Paused
            $subtask->save();

            return response()->json(['success' => 'Tracking paused.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function resumeTracking(Request $request, $id)
    {
        try {
            // Find the subtask
            $subtask = Subtask::findOrFail($id);

            // Check if pause_start is set
            if (!$subtask->pause_start) {
                return response()->json(['error' => 'Tracking was not paused.'], 400);
            }

            // Calculate pause duration
            $pauseDuration = Carbon::parse($subtask->pause_start)->diffInMinutes(Carbon::now());

            // Reset pause_start and update status
            $subtask->pause_start = null;
            $subtask->status = 'In Progress'; // Update status to In Progress
            $subtask->save();

            // Return response with pause duration
            return response()->json([
                'success' => 'Tracking resumed.',
                'pause_duration' => $pauseDuration . ' minutes' // Include pause duration in response
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    
    public function stopTracking(Request $request, $id)
{
    try {
        $user = Auth::guard('api')->user();
        // Find the subtask
        $subtask = Subtask::findOrFail($id);

        // Check if tracking is paused and handle pause time
        $pauseDuration = 0;
        if ($subtask->pause_start && $subtask->pause_end) {
            // Calculate the pause duration in minutes
            $pauseStart = Carbon::parse($subtask->pause_start);
            $pauseEnd = Carbon::parse($subtask->pause_end);
            $pauseDuration = $pauseStart->diffInMinutes($pauseEnd);
        }

        // Update subtask with end time and location
        $subtask->latitude_end = $request->input('latitude_start');
        $subtask->longitude_end = $request->input('longitude_start');
        $subtask->time_end = Carbon::now();
        $subtask->status = 'Completed';
        $subtask->save();

        // Calculate the total duration
        $startTime = Carbon::parse($subtask->time_start);
        $endTime = Carbon::parse($subtask->time_end);
        $totalDuration = $startTime->diffInMinutes($endTime) - $pauseDuration; // Subtract pause duration

        // Return response with tracking details
        return response()->json([
            'success' => 'Tracking stopped.',
            'tracking_duration' => $totalDuration . ' minutes', // Duration
            'start_location' => [
                'latitude' => $subtask->latitude_start,
                'longitude' => $subtask->longitude_start
            ],
            'end_location' => [
                'latitude' => $subtask->latitude_end,
                'longitude' => $subtask->longitude_end
            ]
        ], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}



    //Komentar
    public function storeComment(Request $request, $taskId)
    {
        $code = Auth::guard('api')->user()->employee_code;
        $employee = Employee::where('nik', $code)->first();

        $request->validate([
            'content' => 'required|string',
        ]);

        $task = TaskMaster::findOrFail($taskId);
        $comment = new TaskComment();
        $comment->task_id = $task->id;
        $comment->nik = $code;
        $comment->content = $request->content;
        if ($request->hasFile('attachments')) {
            $file = $request->file('attachments');
            $extension = $file->getClientOriginalExtension();

            if (in_array($extension, ['pdf', 'jpg'])) {
                $path = $file->store('public/files');
                $comment->attachments = $path;
            } else {
                return response()->json(['error' => 'Invalid file type.'], 400);
            }
        }
        $comment->save();

        return response()->json(['success' => 'Comment added.'], 200);
    }

    // Subtask Running
    public function getRunningSubtasks(Request $request)
    {
        try {
            // Retrieve the authenticated user
            $user = Auth::guard('api')->user();
        
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        
            $employeeCode = $user->name;
        
            // Fetch running subtasks for the current user (tracking started but not ended)
            $runningSubtasks = Subtask::whereHas('task', function ($query) use ($employeeCode) {
                // Ensure the task is assigned to the current user in the task_user table
                $query->whereHas('assignedUsers', function ($subQuery) use ($employeeCode) {
                    $subQuery->where('nik', $employeeCode);
                });
            })
            ->whereNotNull('time_start')
            ->whereNull('time_end')
            ->get();
        
            if ($runningSubtasks->isEmpty()) {
                return response()->json(['message' => 'No running subtasks found.'], 404);
            }
        
            // Calculate the duration for each subtask
            $runningSubtasks->transform(function ($subtask) {
                $startTime = \Carbon\Carbon::parse($subtask->time_start);
                $currentTime = \Carbon\Carbon::now();
        
                // Calculate the difference in hours, minutes, and seconds
                $duration = $currentTime->diff($startTime);
        
                // Format the duration as H:i:s
                $formattedDuration = sprintf('%02d:%02d:%02d', $duration->h, $duration->i, $duration->s);
        
                // Add the formatted duration to the subtask object
                $subtask->duration = $formattedDuration;
        
                return $subtask;
            });
        
            return response()->json(['runningSubtasks' => $runningSubtasks], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
        
    }

    // Filter 
    public function filterByPriority(Request $request, $priority)
    {
        try {
            $user = Auth::guard('api')->user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $employeeCode = $user->name;
            $employee = Employee::where('nik', $employeeCode)->first();

            if (!$employee) {
                return response()->json(['error' => 'Employee not found'], 404);
            }

            $unitBisnis = $employee->unit_bisnis;
            $assignedTaskIds = TaskUser::where('nik', $employeeCode)->pluck('task_id');

            $taskData = TaskMaster::whereIn('id', $assignedTaskIds)
                                ->where('company', $unitBisnis)
                                ->where('priority', $priority) // Filter by priority
                                ->get();

            return response()->json($taskData);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function filterByProgress(Request $request, $progressStatus)
    {
        try {
            $user = Auth::guard('api')->user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $employeeCode = $user->name;
            $employee = Employee::where('nik', $employeeCode)->first();

            if (!$employee) {
                return response()->json(['error' => 'Employee not found'], 404);
            }

            $unitBisnis = $employee->unit_bisnis;
            $assignedTaskIds = TaskUser::where('nik', $employeeCode)->pluck('task_id');

            $taskData = TaskMaster::whereIn('id', $assignedTaskIds)
                                ->where('company', $unitBisnis)
                                ->where('status', $progressStatus) // Filter by progress status
                                ->get();

            return response()->json($taskData);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function searchTasks(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $employeeCode = $user->name;
            $employee = Employee::where('nik', $employeeCode)->first();

            if (!$employee) {
                return response()->json(['error' => 'Employee not found'], 404);
            }

            $unitBisnis = $employee->unit_bisnis;
            $assignedTaskIds = TaskUser::where('nik', $employeeCode)->pluck('task_id');

            $query = $request->input('query');
            $taskData = TaskMaster::whereIn('id', $assignedTaskIds)
                                ->where('company', $unitBisnis)
                                ->where(function ($queryBuilder) use ($query) {
                                    $queryBuilder->where('title', 'LIKE', "%{$query}%")
                                                ->orWhere('description', 'LIKE', "%{$query}%");
                                })
                                ->get();

            return response()->json($taskData);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function completeSubtask($id)
    {
        DB::beginTransaction();

        try {
            // Cari subtask berdasarkan ID
            $subtask = Subtask::find($id);
            if (!$subtask) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Subtask not found'], 404);
            }

            // Update status menjadi "Completed"
            $subtask->status = 'Completed';
            $subtask->save();

            // Commit transaksi
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Subtask completed successfully'], 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
    
}
