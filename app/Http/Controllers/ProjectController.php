<?php

namespace App\Http\Controllers;

use App\Filters\TitleDescriptionSearchFilter;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectResourceCollection;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $projects = QueryBuilder::for(Project::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new TitleDescriptionSearchFilter()),
            ])
            ->paginate();

        return response()->json(new ProjectResourceCollection($projects));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $project = Project::create($request->validated());
        $project->users()->attach(auth()->user());

        if (!empty($request->get('user_ids'))) {
            $project->users()->sync($request->user_ids);
        }

        if (!$project) {
            return response()->json([
                'message' => 'Project could not be created.'
            ], 400);
        }
        return response()->json(new ProjectResource($project));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::with(['users'])->findOrFail($id);
        return response()->json(new ProjectResource($project));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, string $id)
    {
        $project = Project::findOrFail($id);

        if (!empty($request->get('user_ids'))) {
            $project->users()->sync($request->user_ids);
        }

        $project->update($request->validated());
        return response()->json(new ProjectResource($project));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return response()->json([
            'message' => 'Project successfully deleted.'
        ]);
    }
}
