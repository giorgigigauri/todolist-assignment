<?php

namespace App\Jobs;

use App\Mail\TaskOverdueReminderMail;
use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class SendOverdueTaskRemindersJob implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $overdueTasks = Task::where('due_date', '<=', Carbon::now())
            ->where('status', '!=', 'completed')
            ->get();

        foreach ($overdueTasks as $task) {
            Mail::to($task->creator->email)->send(new TaskOverdueReminderMail($task));
        }
    }
}
