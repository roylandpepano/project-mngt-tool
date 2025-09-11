<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProjectDeadlineReminder;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class SendProjectDeadlineRemindersTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_sends_reminders()
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'test@example.com']);

        $project = Project::create([
            'title' => 'Test Project',
            'description' => 'desc',
            'deadline' => Carbon::today()->addDay()->toDateString(),
            'user_id' => $user->id,
        ]);

        $this->artisan('projects:send-deadline-reminders --days=1')
             ->assertExitCode(0);

        Mail::assertSent(ProjectDeadlineReminder::class, function ($mail) use ($project) {
            return $mail->project->id === $project->id;
        });
    }
}
