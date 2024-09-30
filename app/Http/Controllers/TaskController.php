<?php

namespace App\Http\Controllers;

use App\Filters\TitleDescriptionSearchFilter;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskResourceCollection;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                'status',
                AllowedFilter::custom('search', new TitleDescriptionSearchFilter()),
                AllowedFilter::scope('due_date', 'beforeDueDate')
            ])
            ->with('project')
            ->paginate();

        return response()->json(new TaskResourceCollection($tasks));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->validated() + ['user_id' => auth()->id()]);
        if (!$task) {
            return response()->json([
                'message' => 'Task could not be created.'
            ], 400);
        }
        return response()->json(new TaskResource($task));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::with('project')->findOrFail($id);
        return response()->json(new TaskResource($task));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTaskRequest $request, string $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->validated());
        return response()->json(new TaskResource($task));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json([
            'message' => 'Task successfully deleted.'
        ]);
    }
}
