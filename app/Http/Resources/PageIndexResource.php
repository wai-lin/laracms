<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageIndexResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'meta_description' => $this->meta_description,
            'status' => $this->status,
            'order' => $this->order,
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'template' => $this->whenLoaded('template', fn () => [
                'id' => $this->template->id,
                'name' => $this->template->name,
                'slug' => $this->template->slug,
            ]),
        ];
    }
}
