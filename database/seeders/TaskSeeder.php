<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::factory()->count(50)->create()->each(function ($task) {
            $project = Project::factory()->create();
            $project->users()->attach(User::find(1));
            $task->project()->associate($project);
            $task->save();
        });
    }
}
