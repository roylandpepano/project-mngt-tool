<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'deadline',
        'user_id',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function setDeadlineAttribute($value)
    {
        $this->attributes['deadline'] = $value ? $value : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Percentage of tasks completed for this project (0-100).
     */
    public function getProgressAttribute()
    {
        // Prefer preloaded counts if available to avoid extra queries
        if (isset($this->attributes['tasks_count']) && isset($this->attributes['done_tasks_count'])) {
            $total = (int) $this->attributes['tasks_count'];
            $done = (int) $this->attributes['done_tasks_count'];
            return $total ? (int) round($done / $total * 100) : 0;
        }

        // If tasks relation is loaded, use collection
        if ($this->relationLoaded('tasks')) {
            $total = $this->tasks->count();
            $done = $this->tasks->where('status', 'done')->count();
            return $total ? (int) round($done / $total * 100) : 0;
        }

        // Fallback to querying counts
        $total = $this->tasks()->count();
        $done = $this->tasks()->where('status', 'done')->count();
        return $total ? (int) round($done / $total * 100) : 0;
    }
}
