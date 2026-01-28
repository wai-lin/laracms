<?php

namespace App\Http\Resources;

use App\Helpers\StorageHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'fields' => $this->transformFields(),
        ];
    }

    /**
     * Transform field values into a key-value object with proper types.
     */
    protected function transformFields(): object
    {
        $fields = [];

        if (!$this->relationLoaded('fieldValues')) {
            return (object) $fields;
        }

        foreach ($this->fieldValues as $fieldValue) {
            $templateField = $fieldValue->templateField;
            
            if (!$templateField) {
                continue;
            }

            $name = $templateField->name;
            $type = $templateField->type;
            $value = $fieldValue->value;

            $fields[$name] = $this->castFieldValue($value, $type);
        }

        return (object) $fields;
    }

    /**
     * Cast field value based on field type.
     */
    protected function castFieldValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'image' => $value ? StorageHelper::temporaryUrl($value) : null,
            default => $value,
        };
    }
}
