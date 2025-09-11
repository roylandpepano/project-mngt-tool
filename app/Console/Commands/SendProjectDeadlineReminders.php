<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Mail\ProjectDeadlineReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendProjectDeadlineReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:send-deadline-reminders {--days=1 : Number of days before deadline to remind}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for projects with upcoming deadlines';

    public function handle()
    {
        $days = (int) $this->option('days');
        $targetDate = Carbon::today()->addDays($days)->toDateString();

        $this->info("Looking for projects with deadline on {$targetDate}");

        $projects = Project::with('user')
            ->whereDate('deadline', $targetDate)
            ->get();

        if ($projects->isEmpty()) {
            $this->info('No projects found for reminders.');
            return 0;
        }

        $sent = 0;
        foreach ($projects as $project) {
            if (!$project->user || !$project->user->email) {
                continue;
            }

            // Send the mailable — the configured mailer
            Mail::to($project->user->email)->send(new ProjectDeadlineReminder($project));
            $sent++;
        }

        $this->info("Sent {$sent} reminder(s).");
        return 0;
    }
}
