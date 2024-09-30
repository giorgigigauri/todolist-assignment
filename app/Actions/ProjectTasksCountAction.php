<?php

namespace App\Actions;

use App\Models\Project;
use Lorisleiva\Actions\Concerns\AsAction;

class ProjectTasksCountAction
{
    use AsAction;

    public function handle()
    {
        $projects = Project::withCount([
            'tasks',
            'tasks as completed_tasks_count' => function ($query) {
                $query->where('status', 'completed');
            }
        ])->get();

        $result = $projects->map(function ($project) {
            $totalTasks = $project->tasks_count;
            $completedTasks = $project->completed_tasks_count;

            $completionPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;

            return [
                'project' => $project->title,
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completedTasks,
                'completion_percentage' => round($completionPercentage, 2),
            ];
        });

        return $result;
    }
}
