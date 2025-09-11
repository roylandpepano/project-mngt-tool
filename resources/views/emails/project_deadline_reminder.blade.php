<p>Hi {{ $project->user->name }},</p>

<p>This is a reminder that the project "{{ $project->title }}" is due on {{ $project->deadline->toFormattedDateString() }}.</p>

<p>Progress: {{ $project->progress }}% complete.</p>

<p>Regards,<br/>Project Management Tool</p>
