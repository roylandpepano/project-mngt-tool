<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Http\Requests\StoreTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
    $isAdmin = Auth::user()->role === 'admin';
    $isProjectOwner = $task->project->user_id == Auth::id();
    $isAssignee = $task->assigned_to == Auth::id();

        if (! $isAdmin && ! $isProjectOwner && ! $isAssignee) {
            abort(403);
        }

        $users = [];
        if ($isAdmin) {
            $users = User::orderBy('name')->get();
        }

        // If the current user is only the assignee (not admin or project owner), allow status-only editing
        $canEditStatusOnly = (! $isAdmin && ! $isProjectOwner && $isAssignee);

        return view('tasks.edit', compact('task', 'users', 'canEditStatusOnly'));
    }

    public function show(Task $task)
    {
        // allow admins, project owners, or the assignee to view
    if (Auth::user()->role !== 'admin' && $task->project->user_id != Auth::id() && $task->assigned_to != Auth::id()) {
            abort(403);
        }

        $task->load('project', 'assignee');
        return view('tasks.show', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
    $isAdmin = Auth::user()->role === 'admin';
    $isProjectOwner = $task->project->user_id == Auth::id();
    $isAssignee = $task->assigned_to == Auth::id();

        // If user is only the assignee, allow updating status only
        if (! $isAdmin && ! $isProjectOwner && $isAssignee) {
            $validated = Validator::make($request->only('status'), [
                'status' => 'required|in:todo,in progress,done',
            ])->validate();
            $task->update(['status' => $validated['status']]);
            return redirect()->back()->with('success', 'Task status updated.');
        }

        // otherwise require admin or project owner
        if (! $isAdmin && ! $isProjectOwner) {
            abort(403);
        }

        // Validate using the same rules as UpdateTaskRequest
        $data = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'status' => 'required|in:todo,in progress,done',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ])->validate();

        if (isset($data['assigned_to']) && ! $isAdmin) {
            if ($data['assigned_to'] != Auth::id()) {
                abort(403);
            }
        }

        $task->update($data);
        return redirect()->route('projects.show', $task->project_id)->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
    if (Auth::user()->role !== 'admin' && $task->project->user_id != Auth::id()) abort(403);
        $projectId = $task->project_id;
    $task->delete();
        return redirect()->route('projects.show', $projectId)->with('success', 'Task deleted.');
    }
}
