<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResources extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,
            'problem' => $this->problem,
            'objectif' => $this->objectif,
            'budget' => (float) $this->budget,
            'duration' => $this->duration,
            'status' => $this->status,
            'funded' => (bool) ($this->funded_at !== null),
            'documents_count' => $this->documents_count ?? $this->documents()->count(),
            'owner' => [
                'id' => $this->user_id,
                'name' => $this->whenLoaded('user', fn() => $this->user->name),
            ],
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
