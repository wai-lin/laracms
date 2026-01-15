<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PageTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    protected static function booted()
    {
        // If `slug` is empty, auto convert `name` to `slug`
        static::creating(function (PageTemplate $template) {
            if (empty($template->slug)) {
                $template->slug = Str::slug($template->name);
            }
        });
    }

    public function fields(): HasMany
    {
        return $this->hasMany(PageTemplateField::class)->orderBy('order');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
