<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageFieldValue extends Model
{
    protected $fillable = [
        'page_id',
        'page_template_field_id',
        'value',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function templateField(): BelongsTo
    {
        return $this->belongsTo(PageTemplateField::class, 'page_template_field_id');
    }
}
