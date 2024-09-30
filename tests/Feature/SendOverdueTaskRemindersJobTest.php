<?php

use App\Jobs\SendOverdueTaskRemindersJob;
use App\Mail\TaskOverdueReminderMail;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('sends overdue task reminders', function () {
    Mail::fake();

    $user = User::factory()->create();


    $task = Task::factory()->create([
        'due_date' => Carbon::now()->subDay(),
        'status' => 'in progress',
        'user_id' => $user->id,
    ]);
    $project = Project::factory()->create();
    $project->users()->attach($user);
    $task->project()->associate($project);
    $task->save();


    dispatch(new SendOverdueTaskRemindersJob());
    Mail::assertSent(TaskOverdueReminderMail::class, function ($mail) use ($task) {
        return $mail->hasTo($task->creator->email);
    });
})->skip();

it('does not send reminders for completed tasks', function () {
    Mail::fake();

    $user = User::factory()->create();

    Task::factory()->create([
        'due_date' => Carbon::now()->subDay(),
        'status' => 'completed',
        'user_id' => $user->id,
    ]);

    dispatch(new SendOverdueTaskRemindersJob());

    Mail::assertNothingSent();
});

it('does not send reminders for future tasks', function () {
    Mail::fake();

    $user = User::factory()->create();

    Task::factory()->create([
        'due_date' => Carbon::now()->addDay(),
        'status' => 'in progress',
        'user_id' => $user->id,
    ]);

    dispatch(new SendOverdueTaskRemindersJob());

    Mail::assertNothingSent();
});
