<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::factory()->count(4)->create()->each(function ($project) {
            $project->users()->attach(User::find(1));

            Task::factory()->count(rand(0, 10))->create([
                'project_id' => $project->id,
            ]);
        });

    }
}
