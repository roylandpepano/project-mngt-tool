<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        // allow sorting by progress via ?sort=progress (desc) or ?sort=progress_asc
        $sort = request('sort');

        // Admins should see all projects; normal users only their own
        $query = Project::query()
            ->with('user')
            ->withCount(['tasks', 'tasks as done_tasks_count' => function ($q) {
                $q->where('status', 'done');
            }]);

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        if ($sort === 'progress') {
            // order by done_tasks_count/tasks_count desc (approx by done_tasks_count desc)
            $query->orderByDesc('done_tasks_count');
        } else {
            $query->latest();
        }

        $projects = $query->paginate(10)->withQueryString();

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        Project::create($data);

        return redirect()->route('projects.index')->with('success', 'Project created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);
        $project->load(['tasks', 'user']);

        $total = $project->tasks->count();
        $done = $project->tasks->where('status', 'done')->count();
        $progress = $total ? round($done / $total * 100) : 0;

        return view('projects.show', compact('project', 'progress'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);
        $project->update($request->validated());
        return redirect()->route('projects.index')->with('success', 'Project updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted.');
    }
}
