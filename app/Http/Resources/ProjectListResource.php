<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



class ProjectListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $sortedProjects = $this->whenLoaded('projects')->sortBy('order')->values();

        $resource =  [
            "id" => $this->id,
            "creator_id" => $this->creator_id,
            "title" => $this->title,
            "board_id" => $this->board_id,
            "projects" => ProjectResource::collection($sortedProjects)
        ];

        return $resource;
    }
}
