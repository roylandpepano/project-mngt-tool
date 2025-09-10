<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'status',
        'due_date',
        'project_id',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = $value ? $value : null;
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
