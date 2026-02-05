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
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,
            'budget' => $this->budget,
            'duration' => $this->duration,
            'status' => $this->status,
            'submitted_at' => $this->submitted_at ? $this->submitted_at->format('Y-m-d H:i:s') : null,
            'funded_at' => $this->funded_at ? $this->funded_at->format('Y-m-d H:i:s') : null,
            
            // On inclut les relations utiles
            'documents' => DocumentResource::collection($this->whenLoaded('documents')),
            
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
