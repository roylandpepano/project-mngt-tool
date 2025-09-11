<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();
        // Allow admins to add tasks to any project; otherwise ensure project belongs to user
        $projectQuery = Project::where('id', $data['project_id']);
        if (Auth::user()->role !== 'admin') {
            $projectQuery->where('user_id', Auth::id());
        }
        $project = $projectQuery->firstOrFail();
        // enforce assignment rules: non-admins can only assign to themselves or leave unassigned
        if (isset($data['assigned_to']) && Auth::user()->role !== 'admin') {
            if ($data['assigned_to'] != Auth::id()) {
                abort(403);
            }
        }

        $task = Task::create($data);
        return redirect()->route('projects.show', $project->id)->with('success', 'Task created.');
    }

    public function edit(Task $task)
    {
        if (Auth::user()->role !== 'admin' && $task->project->user_id !== Auth::id()) abort(403);
        $users = [];
        // Admins get full user list to assign; non-admins only themselves
        if (Auth::user()->role === 'admin') {
            $users = User::orderBy('name')->get();
        }
        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        if (Auth::user()->role !== 'admin' && $task->project->user_id !== Auth::id()) abort(403);
        $data = $request->validated();
        if (isset($data['assigned_to']) && Auth::user()->role !== 'admin') {
            if ($data['assigned_to'] != Auth::id()) {
                abort(403);
            }
        }

        $task->update($data);
        return redirect()->route('projects.show', $task->project_id)->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        if (Auth::user()->role !== 'admin' && $task->project->user_id !== Auth::id()) abort(403);
        $projectId = $task->project_id;
    $task->delete();
        return redirect()->route('projects.show', $projectId)->with('success', 'Task deleted.');
    }
}
