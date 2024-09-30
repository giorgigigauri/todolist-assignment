<?php
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can list projects', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Project::factory()->count(5)->create();

    $response = $this->getJson(route('projects.index'));

    $response->assertStatus(200)
        ->assertJsonStructure(['data' => [['id', 'title', 'description']]]);
});

it('can create a project', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $projectData = Project::factory()->make()->toArray();

    $response = $this->postJson(route('projects.store'), array_merge($projectData, [
        'user_ids' => [$user->id]
    ]));

    $response->assertStatus(200)
        ->assertJsonFragment(['title' => $projectData['title']]);

    $this->assertDatabaseHas('projects', ['title' => $projectData['title']]);
});

it('can show a specific project', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $project = Project::factory()->create();

    $response = $this->getJson(route('projects.show', $project->id));

    $response->assertStatus(200)
        ->assertJsonFragment(['title' => $project->title]);
});

it('can update a project', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $project = Project::factory()->create();
    $newData = [
        'title' => 'Updated Title',
        'description' => 'Updated Description',
    ];

    $response = $this->putJson(route('projects.update', $project->id), $newData);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'title' => 'Updated Title',
            'description' => 'Updated Description',
        ]);

    $this->assertDatabaseHas('projects', [
        'title' => 'Updated Title',
        'description' => 'Updated Description',
    ]);
});

it('can delete a project', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $project = Project::factory()->create();

    $response = $this->deleteJson(route('projects.destroy', $project->id));

    $response->assertStatus(200)
        ->assertJson(['message' => 'Project successfully deleted.']);

    $this->assertDatabaseMissing('projects', ['id' => $project->id]);
});
