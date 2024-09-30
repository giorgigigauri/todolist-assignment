<?php

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->task = Task::factory()->create([
            'user_id' => $this->user->id,
        ]);
    $this->project = Project::factory()->create();
    $this->project->users()->attach($this->user);
    $this->task->project()->associate($this->project);
    $this->task->save();
});
it('can list tasks', function () {
    $response = $this->getJson(route('tasks.index'));

    $response->assertStatus(200)
        ->assertJsonStructure(['data' => [['id', 'title', 'description', 'status']]]);
});

it('can create a task', function () {
    $taskData = Task::factory()->make()->toArray();

    $response = $this->postJson(route('tasks.store'), $taskData);

    $response->assertStatus(200)
        ->assertJsonFragment(['title' => $taskData['title']]);

    $this->assertDatabaseHas('tasks', ['title' => $taskData['title']]);
});


it('can show a specific task', function () {
    $response = $this->getJson(route('tasks.show', $this->task->id));

    $response->assertStatus(200)
        ->assertJsonFragment(['title' => $this->task->title]);
});
it('can update a task', function () {
    $task = Task::factory()->create([
        'project_id' => $this->project->id,
        'user_id' => $this->user->id,
        'due_date' => now()->addDays(7)->format('Y-m-d'),
    ]);

    $newData = [
        'title' => 'Updated Title',
        'description' => 'Updated Description',
        'project_id' => $this->project->id,
        'due_date' => now()->addDays(14)->format('Y-m-d'),
    ];

    $response = $this->putJson(route('tasks.update', $task->id), $newData);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'due_date' => $newData['due_date'],
        ]);

    $this->assertDatabaseHas('tasks', [
        'title' => 'Updated Title',
        'description' => 'Updated Description',
        'project_id' => $this->project->id,
        'due_date' => $newData['due_date'],
    ]);
});

it('can delete a task', function () {
    $response = $this->deleteJson(route('tasks.destroy', $this->task->id));

    $response->assertStatus(200)
        ->assertJson(['message' => 'Task successfully deleted.']);

    $this->assertDatabaseMissing('tasks', ['id' => $this->task->id]);
});
