<?php

namespace App\Http\Controllers\TaskManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

// Model
use App\TaskManagement\TaskMaster;
use App\TaskManagement\Subtask;
use App\TaskManagement\TaskUser;
use App\TaskManagement\TaskComment;
use App\Employee;

class TaskMasterController extends Controller
{
    public function index()
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();
        $userRoles = json_decode(Auth::user()->permission);  // Assuming roles are stored in a way you can access them

        $today = now();
        $hour = $today->hour;
        $hariini2 = Carbon::now();

        if ($hour >= 1 && $hour < 11) {
            $greeting = 'Selamat Pagi';
        } elseif ($hour >= 11 && $hour < 16) {
            $greeting = 'Selamat Siang';
        } elseif ($hour >= 16 && $hour < 20) {
            $greeting = 'Selamat Sore';
        } else {
            $greeting = 'Selamat Malam';
        }

        // Initialize $assignedTaskIds as null
        $assignedTaskIds = null;
        
        // Check if user has superadmin_access or similar role
        if (in_array('superadmin_access', $userRoles) || in_array('am_access', $userRoles) || in_array('client_access', $userRoles)) {
            // Fetch all tasks for users with the access role
            $taskData = TaskMaster::where('company', $employee->unit_bisnis)->orderBy('created_at', 'desc')->get();
        } else {
            // Get the IDs of tasks assigned to the current user
            $assignedTaskIds = TaskUser::where('nik', $code)->pluck('task_id');
            
            // Fetch all tasks that are assigned to the current user
            $taskData = TaskMaster::whereIn('id', $assignedTaskIds)
                                ->where('company', $employee->unit_bisnis)
                                ->orderBy('created_at', 'desc')
                                ->get();
        }

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
        }
        
        $groupedTasks = $taskData->groupBy('status');
        $subtask = Subtask::all();
        $user = Employee::where('unit_bisnis', $employee->unit_bisnis)->where('resign_status',0)->get();

        $mentionUsers = Employee::select('nik', 'nama')->get();

        // Only calculate and fetch tasks for current and previous periods
        $currentDate = Carbon::now();
        
        if ($assignedTaskIds) {
            // Calculate statistics for assigned tasks only
            $currentTaskData = TaskMaster::whereIn('id', $assignedTaskIds)
                                        ->where('company', $employee->unit_bisnis)
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
                                        ->where('company', $employee->unit_bisnis)
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
        } else {
            // If superadmin, calculate stats for all tasks
            $currentTaskData = TaskMaster::where('company', $employee->unit_bisnis)
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
            $previousTaskData = TaskMaster::where('company', $employee->unit_bisnis)
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
        }

        return view('pages.taskmanagement.index', compact(
            'taskData', 'subtask', 'groupedTasks', 'user', 'mentionUsers',
            'totalTasks', 'completedTasks', 'inProgressTasks', 'overdueTasks',
            'totalTasksPercentage', 'completedTasksPercentage', 'inProgressTasksPercentage', 'overdueTasksPercentage','greeting'
        ));
    }

    public function show($id)
    {
        // Fetch the task details
        $task = TaskMaster::where('id', $id) ->first();

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

        return view('pages.taskmanagement.show', compact('task'));
    }

    public function create()
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();
        
        $user = Employee::where('unit_bisnis', $employee->unit_bisnis)->where('resign_status',0)->get();
        return view('pages.taskmanagement.create', compact('user'));
    }

    public function store(Request $request)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        if (!$company) {
            return redirect()->back()->with('error', 'Company not found for the current user.');
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
            $task->description = $request->deskripsi;
            $task->due_date = $request->due_date;
            $task->priority = $request->priority;
            $task->kategori = $request->kategori;
            $task->company = $company->unit_bisnis;

            if ($request->hasFile('attachments')) {
                $file = $request->file('attachments');
                
                // Mendapatkan ekstensi file
                $extension = $file->getClientOriginalExtension();
            
                // Mengecek apakah file adalah PDF atau JPG
                if ($extension !== 'pdf' && $extension !== 'jpg' && $extension !== 'zip' && $extension !== 'rar') {
                    return redirect()->back()->with('error', 'Hanya file PDF dan JPG yang diizinkan.');
                }
            
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

            // Check if there are subtasks and if repeat interval is provided
            $hasSubtasks = Subtask::where('task_id', $task_id)->exists();

            if ($hasSubtasks && $request->repeat_interval) {
                $task->repeat_interval = $request->repeat_interval;
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
            }

            DB::commit();
            return redirect()->route('task-management.index')->with('success', 'Task berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function edit($id)
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();

        // Retrieve the task to be edited
        $task = TaskMaster::find($id);
        if (!$task) {
            return redirect()->route('task-management.index')->with('error', 'Task not found');
        }

        // Retrieve users assigned to the task
        $assignedUsers = TaskUser::where('task_id', $id)
                                ->join('karyawan', 'task_user.nik', '=', 'karyawan.nik')
                                ->get(['karyawan.nik', 'karyawan.nama']);

        // Retrieve users from the same unit who are not resigned
        $users = Employee::where('unit_bisnis', $employee->unit_bisnis)
                        ->where('resign_status', 0)
                        ->get();

        return view('pages.taskmanagement.edit', compact('task', 'users', 'assignedUsers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // Find the task to be updated
        $task = TaskMaster::find($id);
        if (!$task) {
            return redirect()->route('task-management.index')->with('error', 'Task not found');
        }

        DB::beginTransaction();
        try {
            // Update task details
            $task->title = $request->input('title');
            $task->description = $request->input('deskripsi');
            $task->due_date = $request->input('due_date');
            $task->priority = $request->input('priority');
            $task->kategori = $request->input('kategori');

            // Handle file upload
            if ($request->hasFile('attachments')) {
                $file = $request->file('attachments');
                $extension = $file->getClientOriginalExtension();

                if ($extension !== 'pdf' && $extension !== 'jpg' && $extension !== 'zip' && $extension !== 'rar') {
                    return redirect()->back()->with('error', 'Only PDF, JPG, ZIP, and RAR files are allowed.');
                }

                $filename = time() . '-' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $filename); // Adjust path as needed
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

        if ($hasSubtasks && $request->repeat_interval) {
            $task->repeat_interval = $request->repeat_interval;
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
            // Mengembalikan error jika tidak ada subtasks
            return redirect()->back()->with('error', 'Task tidak memiliki subtask.');
        }

            DB::commit();
            return redirect()->route('task-management.index')->with('success', 'Task updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function storeSubtask(Request $request)
    {   
        $request->validate([
        ]);
        DB::beginTransaction();
        try {
            for ($i = 0; $i < count($request->title); $i++) {
                $subtask = new Subtask;
                $subtask->id = Str::uuid();
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

            if ($taskData->status == 'Completed') {
                $taskData->status = 'In Progress'; // Correct status change
                $taskData->save();
            }

            DB::commit();
            return redirect()->route('task-management.index')->with('success', 'Subtask berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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

            // Update status
            $subtask->status = $request->input('is_active');
            $subtask->save();

            // Find the parent task
            $task = TaskMaster::find($subtask->task_id);

            if ($task) {
                // Calculate subtasks status
                $totalSubtasks = Subtask::where('task_id', $task->id)->count();
                $completedSubtasks = Subtask::where('task_id', $task->id)
                                            ->where('status', 'Completed') // Adjust the status check if needed
                                            ->count();
                
                if ($totalSubtasks === 0) {
                    $task->status = 'TO DO'; // No subtasks
                } elseif ($completedSubtasks === 0) {
                    $task->status = 'TO DO'; // No subtasks completed
                } elseif ($completedSubtasks < $totalSubtasks) {
                    $task->status = 'In Progress'; // Some subtasks completed
                } else {
                    $task->status = 'Completed'; // All subtasks completed
                }

                // Save the task status and progress
                $task->progress = ($totalSubtasks > 0) ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;
                $task->save();
            }

            // Commit transaction
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            // Rollback transaction if there's an error
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateSubtask(Request $request,$id)
    {
        $subtask = Subtask::find($id);
        if (!$subtask) {
            return redirect()->back()->with('error', 'Subtask not found');
        }

        $subtask->title = $request->input('title');
        $subtask->description = $request->input('description');
        $subtask->due_date = $request->input('due_date');
        $subtask->save();

        return redirect()->back()->with('success', 'Subtask updated successfully');
    }

    public function destroy($id)
    {
        $taskMaster = TaskMaster::find($id);

        if ($taskMaster) {
            // Delete related records first
            Subtask::where('task_id', $id)->delete();
            TaskUser::where('task_id', $id)->delete();
            TaskComment::where('task_id', $id)->delete();

            // Delete the task master
            $taskMaster->delete();

            return redirect()->back()->with('success', 'Task and related data deleted successfully');
        }

        return redirect()->back()->with('error', 'Subtask not found');
    }

    public function destroySubtask($id)
    {
        $subtask = Subtask::find($id);
        if ($subtask) {
            $subtask->delete();
            return redirect()->back()->with('success', 'Subtask deleted successfully');
        }

        return redirect()->back()->with('error', 'Subtask not found');
    }

    public function downloadAttachment($id)
    {
        try {
            $task = TaskMaster::findOrFail($id);

            if ($task->attachments) {
                return Storage::download($task->attachments);
            } else {
                return redirect()->back()->with('error', 'No attachment found.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadAttachmentSubtask($id)
    {
        try {
            $task = Subtask::findOrFail($id);

            if ($task->attachments) {
                return Storage::download($task->attachments);
            } else {
                return redirect()->back()->with('error', 'No attachment found.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function startTracking($id, Request $request)
    {
        $subtask = Subtask::findOrFail($id);
        $subtask->latitude_start = $request->input('latitude_start');
        $subtask->longitude_start = $request->input('longitude_start');
        $subtask->time_start = Carbon::now();
        $subtask->save();

        return redirect()->back()->with('success', 'Tracking started.');
    }

    public function stopTracking(Request $request, $id)
    {
        $subtask = Subtask::findOrFail($id);
        
        $subtask->latitude_end = $request->input('latitude_stop');
        $subtask->longitude_end = $request->input('longitude_stop');
        $subtask->time_end = Carbon::now();
        $subtask->status = 'Completed';
        $subtask->save();

        $task = TaskMaster::find($subtask->task_id);

            if ($task) {
                // Calculate subtasks status
                $totalSubtasks = Subtask::where('task_id', $task->id)->count();
                $completedSubtasks = Subtask::where('task_id', $task->id)
                                            ->where('status', 'Completed') // Adjust the status check if needed
                                            ->count();
                
                if ($totalSubtasks === 0) {
                    $task->status = 'TO DO'; // No subtasks
                } elseif ($completedSubtasks === 0) {
                    $task->status = 'TO DO'; // No subtasks completed
                } elseif ($completedSubtasks < $totalSubtasks) {
                    $task->status = 'In Progress'; // Some subtasks completed
                } else {
                    $task->status = 'Completed'; // All subtasks completed
                }

                // Save the task status and progress
                $task->progress = ($totalSubtasks > 0) ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;
                $task->save();
            }

        return redirect()->back()->with('success', 'Tracking stopped.');
    }

    // Komentar

    public function storeKomen(Request $request, $taskId)
    {
        $code = Auth::user()->employee_code;
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
            
            // Mendapatkan ekstensi file
            $extension = $file->getClientOriginalExtension();
        
            // Jika file adalah PDF atau JPG maka simpan
            $path = $file->store('public/files');
            $comment->attachments = $path;
        }
        $comment->save();

        return redirect()->back()->with('success', 'Comment added.');
    }

    public function updateStatusDrag(Request $request, $id)
    {
        $task = TaskMaster::find($id);
        if ($task) {
            $task->status = $request->input('status');
            $task->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}
