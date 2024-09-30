<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'title'=> $this->title,
            'description'=> $this->description,
            'status'=> $this->status,
            'due_date'=> Carbon::parse($this->due_date)->format('Y-m-d'),
            'created_at'=> Carbon::parse($this->created_at)->format('Y-m-d'),
            'updated_at'=> Carbon::parse($this->updated_at)->format('Y-m-d'),
            'project' => new ProjectResource($this->whenLoaded('project')),
        ];
    }
}
