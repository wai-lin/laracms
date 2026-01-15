<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'page_template_id',
        'title',
        'slug',
        'meta_description',
        'status',
        'order',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'order' => 'integer',
        ];
    }

    protected static function booted()
    {
        // If `slug` is empty, convert `title` to `slug`.
        static::creating(function (Page $page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(PageTemplate::class, 'page_template_id');
    }

    public function fieldValues(): HasMany
    {
        return $this->hasMany(PageFieldValue::class);
    }

    public function field(string $name, mixed $default = null): mixed
    {
        $fieldValue = $this->fieldValues()
            ->whereHas('templateField', fn($q) => $q->where('name', $name))
            ->with('templateField')
            ->first();

        if (! $fieldValue) {
            return $default;
        }

        return $this->castFieldValue(
            $fieldValue->value,
            $fieldValue->templateField->type
        );
    }

    public function getFieldsAttribute(): object
    {
        $fields = [];

        $this->fieldValues()
            ->with('templateField')
            ->get()
            ->each(function ($fieldValue) use (&$fields) {
                $name = $fieldValue->templateField->name;
                $fields[$name] = $this->castFieldValue(
                    $fieldValue->value,
                    $fieldValue->templateField->type
                );
            });

        return (object) $fields;
    }

    protected function castFieldValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'image' => $value,
            default => $value,
        };
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', 'scheduled')
            ->where('published_at', '>', now());
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at->lte(now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
