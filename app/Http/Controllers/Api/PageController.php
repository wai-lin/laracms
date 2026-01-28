<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageIndexResource;
use App\Http\Resources\PageResource;
use App\Models\Page;

class PageController extends Controller
{
    /**
     * List all published pages (paginated).
     * 
     * GET /api/pages
     * Query params:
     *   - per_page: int (default: 15)
     *   - template: string (optional) - filter by template slug or name
     */
    public function index()
    {
        $perPage = request()->integer('per_page', 15);
        $template = request()->string('template')->toString();
        
        $query = Page::published()
            ->with('template')
            ->ordered();

        if ($template) {
            $query->whereHas(
                'template',
                fn ($q) => $q->where('slug', $template)
                    ->orWhere('name', $template)
            );
        }

        return PageIndexResource::collection($query->paginate($perPage));
    }

    /**
     * Get a single published page by slug.
     * 
     * GET /api/pages/{slug}
     */
    public function show(string $slug)
    {
        $page = Page::published()
            ->with(['template', 'template.fields', 'fieldValues.templateField'])
            ->where('slug', $slug)
            ->firstOrFail();

        return PageResource::make($page);
    }
}
