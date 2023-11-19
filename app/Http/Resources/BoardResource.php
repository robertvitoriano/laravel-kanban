<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "created_at" => $this->created_at,
            "project_lists" => ProjectListResource::collection($this->whenLoaded('projectLists')),
            "members" => UserResource::collection($this->whenLoaded('boardMembers')),
        ];
    }
}
