<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
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
    $task = Task::create($data);
        return redirect()->route('projects.show', $project->id)->with('success', 'Task created.');
    }

    public function edit(Task $task)
    {
        if (Auth::user()->role !== 'admin' && $task->project->user_id !== Auth::id()) abort(403);
        return view('tasks.edit', compact('task'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        if (Auth::user()->role !== 'admin' && $task->project->user_id !== Auth::id()) abort(403);
    $task->update($request->validated());
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
