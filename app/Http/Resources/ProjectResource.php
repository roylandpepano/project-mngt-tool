<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'deadline' => $this->deadline ? $this->deadline->toDateString() : null,
            'progress' => $this->progress,
            'tasks_count' => $this->when(isset($this->attributes['tasks_count']) ? true : $this->relationLoaded('tasks'), $this->tasks()->count()),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
