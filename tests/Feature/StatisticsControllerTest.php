<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can get project tasks statistics', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $project = Project::factory()->create(['title' => 'Project A']);
    $project->users()->attach($user);

    Task::factory()->create(['project_id' => $project->id, 'status' => 'completed', 'user_id' => $user->id]);
    Task::factory()->create(['project_id' => $project->id, 'status' => 'in progress', 'user_id' => $user->id]);
    Task::factory()->create(['project_id' => $project->id, 'status' => 'completed', 'user_id' => $user->id]);

    $response = $this->getJson(route('statistics'));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'project_tasks',
        ])
        ->assertJsonFragment([
            'project' => 'Project A',
            'total_tasks' => 3,
            'completed_tasks' => 2,
            'completion_percentage' => 66.67,
        ]);
});
