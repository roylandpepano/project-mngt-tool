<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Project;

class ProjectDeadlineReminder extends Mailable
{
    use Queueable, SerializesModels;

    public Project $project;

    /**
     * Create a new message instance.
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Project deadline reminder: ' . $this->project->title)
                    ->view('emails.project_deadline_reminder')
                    ->with(['project' => $this->project]);
    }
}
