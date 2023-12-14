<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->id,
            'title' =>$this->title,
            'order' => $this->order,
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'members' => UserResource::collection($this->whenLoaded('members')),
            'created_at' =>$this->created_at,
            'project_list_id' => $this->project_list_id,

        ];
    }
}
