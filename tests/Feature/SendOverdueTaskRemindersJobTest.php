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
