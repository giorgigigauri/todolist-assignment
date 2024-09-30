<?php

namespace App\Http\Controllers;

use App\Actions\ProjectTasksCountAction;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $statistics = [
            'project_tasks' => ProjectTasksCountAction::run($request),
        ];
        return response()->json($statistics);
    }
}
