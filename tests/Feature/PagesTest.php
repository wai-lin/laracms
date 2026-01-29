<?php

use App\Models\Page;
use App\Models\PageTemplate;

describe('smoke tests', function () {
    test('blogs page loads successfully', function () {
        $response = $this->get('/blogs');

        $response->assertOk();
    });

    test('blog detail page loads for published page', function () {
        $template = PageTemplate::firstOrCreate(
            ['slug' => 'blog'],
            ['name' => 'Blog']
        );

        $page = Page::create([
            'page_template_id' => $template->id,
            'title' => 'Test Blog Post',
            'slug' => 'test-blog-post-' . uniqid(),
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('page.show', $page->slug));

        $response->assertOk();
    });

    test('blog detail page returns 404 for draft page', function () {
        $template = PageTemplate::firstOrCreate(
            ['slug' => 'blog'],
            ['name' => 'Blog']
        );

        $page = Page::create([
            'page_template_id' => $template->id,
            'title' => 'Draft Blog Post',
            'slug' => 'draft-blog-post-' . uniqid(),
            'status' => 'draft',
        ]);

        $response = $this->get(route('page.show', $page->slug));

        $response->assertNotFound();
    });
});
