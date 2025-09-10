<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectApiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
            $query = Project::withCount(['tasks', 'tasks as done_tasks_count' => function ($q) { $q->where('status', 'done'); }]);
            if ($user->role !== 'admin') {
                $query->where('user_id', $user->id);
            }
            $projects = $query->paginate(10);

        return ProjectResource::collection($projects);
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        $project->load('tasks');
        activity()->causedBy(Auth::user())->performedOn($project)->withProperties(['viewed_by' => Auth::id()])->log('project.viewed');
        return new ProjectResource($project);
    }

    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $project = Project::create($data);
        return new ProjectResource($project);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);
        $project->update($request->validated());
        return new ProjectResource($project);
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        return response()->json(['message' => 'Project deleted.']);
    }
}
