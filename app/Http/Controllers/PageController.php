<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::bySlugOrFail($slug);
        // Try template-specific view, fallback to default
        $templateView = 'templates.'.$page->template->slug;
        $view = view()->exists($templateView) ? $templateView : 'templates.default';

        return view($view, ['page' => $page]);
    }
}
