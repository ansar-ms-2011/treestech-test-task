<?php

namespace App\Http\Controllers;

use App\Events\TaskStatusUpdated;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Inertia\Inertia;

class TaskController extends Controller
{

    public function getTaskPage(){
        return Inertia::render('tasks/TaskList', [
            'users' =>  User::role('user')->select('name as label', 'id as value')->get(),
        ]);
    }
    
    public function index()
    {
        $current_user = auth()->user()->id;
        $tasks = Task::with(['user', 'assigned_to']);

        if(auth()->user()->hasRole('user')){
            $tasks->where('assigned_to_id', $current_user);
        }
        $tasks = $tasks->orderBy('priority', 'asc')->orderBy('status', 'asc')->get();

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after_or_equal:today',
            'status' => 'sometimes|in:pending,in_progress,completed,overdue',
            'priority' => 'required|in:low,medium,high',
            'user_id' => 'required|exists:users,id',
            'assigned_to_id' => 'nullable|exists:users,id',
        ]);

        $task = Task::create($validated);
        
        return redirect()->back()->with('success', 'Task created successfully!');
    }

    public function show(Task $task)
    {
        return $task->load('user');
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'sometimes|date|after_or_equal:today',
            'priority' => 'sometimes|in:low,medium,high',
            'status' => 'sometimes|in:pending,in_progress,completed,overdue',
            'user_id' => 'sometimes|exists:users,id',
            'assigned_to_id' => 'nullable|exists:users,id',
        ]);

        if($task->status !== $validated['status']) {
            broadcast(new TaskStatusUpdated($task))->toOthers();
        }
        $task->update($validated);
        return redirect()->back()->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(null, 204);
    }
}
