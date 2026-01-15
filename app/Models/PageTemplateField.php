<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageTemplateField extends Model
{
    protected $fillable = [
        'page_template_id',
        'name',
        'label',
        'type',
        'options',
        'order',
        'required',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'required' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(PageTemplate::class, 'page_template_id');
    }

    public function values(): HasMany
    {
        return $this->hasMany(PageFieldValue::class);
    }
}
