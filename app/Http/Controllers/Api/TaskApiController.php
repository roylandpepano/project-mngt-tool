<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\User;

class TaskApiController extends Controller
{
    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();
        // allow admins to create tasks for any project, otherwise ensure project belongs to user
        $projectQuery = Project::where('id', $data['project_id']);
        if (Auth::user()->role !== 'admin') {
            $projectQuery->where('user_id', Auth::id());
        }
        $project = $projectQuery->firstOrFail();
        if (isset($data['assigned_to']) && Auth::user()->role !== 'admin') {
            if ($data['assigned_to'] != Auth::id()) abort(403);
        }

        $task = Task::create($data);
        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        if (Auth::user()->role !== 'admin' && $task->project->user_id !== Auth::id()) abort(403);
        activity()->causedBy(Auth::user())->performedOn($task)->withProperties(['viewed_by' => Auth::id()])->log('task.viewed');
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        if (Auth::user()->role !== 'admin' && $task->project->user_id !== Auth::id()) abort(403);
        $task->update($request->validated());
        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        if (Auth::user()->role !== 'admin' && $task->project->user_id !== Auth::id()) abort(403);
        $task->delete();
        return response()->json(['message' => 'Task deleted.']);
    }
}
