<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\ProjectList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }
    public function index(Request $request)
    {

        $projects = QueryBuilder::for(Project::class)
            ->allowedIncludes('tasks')
            ->paginate();

        return new ProjectCollection($projects);
    }
    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();

        $projectList = ProjectList::find($validated['project_list_id']);
        $projectsCount = $projectList->projects()->count();
        $project = Auth::user()->projects()->create([
            'title' => $validated['title'],
            'project_list_id' => $validated['project_list_id'],
            'order' => $projectsCount + 1,
        ]);
        return new ProjectResource($project);
    }

    public function show(Request $request, Project $project)
    {
        return (new ProjectResource($project))
            ->load('tasks')
            ->load('members');
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validated = $request->validated();
        $project->update($validated);
        return new ProjectResource($project);
    }

    public function destroy(Request $request, Project $project)
    {
        $projectsToUpdateOrder = Project::where('project_list_id', $project->projectList->id)
            ->where('order', '>', $project->order)
            ->orderBy('order', 'asc')
            ->get();

        foreach ($projectsToUpdateOrder as $projectToUpdate) {
            $projectToUpdate->order = $projectToUpdate->order - 1;
            $projectToUpdate->save();
        }
        $project->delete();
        return response()->noContent();
    }
}
