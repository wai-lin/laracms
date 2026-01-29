<?php

use App\Models\Page;
use App\Models\PageTemplate;
use App\Models\PageTemplateField;

describe('api - pages', function () {
    beforeEach(function () {
        // Create a blog template with fields
        $this->template = PageTemplate::create([
            'name' => 'Blog',
            'slug' => 'blog',
        ]);

        PageTemplateField::create([
            'page_template_id' => $this->template->id,
            'name' => 'body',
            'label' => 'Body',
            'type' => 'richtext',
            'required' => true,
            'order' => 1,
        ]);

        PageTemplateField::create([
            'page_template_id' => $this->template->id,
            'name' => 'is_featured',
            'label' => 'Featured',
            'type' => 'boolean',
            'required' => false,
            'order' => 2,
        ]);
    });

    describe('GET /api/pages', function () {
        test('returns list of published pages', function () {
            Page::create([
                'page_template_id' => $this->template->id,
                'title' => 'First Post',
                'slug' => 'first-post',
                'status' => 'published',
                'published_at' => now(),
            ]);

            Page::create([
                'page_template_id' => $this->template->id,
                'title' => 'Second Post',
                'slug' => 'second-post',
                'status' => 'published',
                'published_at' => now(),
            ]);

            $response = $this->getJson('/api/pages');

            $response->assertOk();
            $response->assertJsonCount(2, 'data');
            $response->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'slug', 'status', 'template'],
                ],
                'links',
                'meta',
            ]);
        });

        test('excludes draft pages from list', function () {
            Page::create([
                'page_template_id' => $this->template->id,
                'title' => 'Published',
                'slug' => 'published-post',
                'status' => 'published',
                'published_at' => now(),
            ]);

            Page::create([
                'page_template_id' => $this->template->id,
                'title' => 'Draft',
                'slug' => 'draft-post',
                'status' => 'draft',
            ]);

            $response = $this->getJson('/api/pages');

            $response->assertOk();
            $response->assertJsonCount(1, 'data');
            $response->assertJsonPath('data.0.title', 'Published');
        });

        test('filters by template slug', function () {
            $otherTemplate = PageTemplate::create([
                'name' => 'Page',
                'slug' => 'page',
            ]);

            Page::create([
                'page_template_id' => $this->template->id,
                'title' => 'Blog Post',
                'slug' => 'blog-post',
                'status' => 'published',
                'published_at' => now(),
            ]);

            Page::create([
                'page_template_id' => $otherTemplate->id,
                'title' => 'Regular Page',
                'slug' => 'regular-page',
                'status' => 'published',
                'published_at' => now(),
            ]);

            $response = $this->getJson('/api/pages?template=blog');

            $response->assertOk();
            $response->assertJsonCount(1, 'data');
            $response->assertJsonPath('data.0.template.slug', 'blog');
        });

        test('respects per_page pagination parameter', function () {
            // Create 5 pages
            foreach (range(1, 5) as $i) {
                Page::create([
                    'page_template_id' => $this->template->id,
                    'title' => "Post $i",
                    'slug' => "post-$i",
                    'status' => 'published',
                    'published_at' => now(),
                ]);
            }

            $response = $this->getJson('/api/pages?per_page=2');

            $response->assertOk();
            $response->assertJsonCount(2, 'data');
            $response->assertJsonPath('meta.per_page', 2);
        });

        test('returns template data for each page', function () {
            Page::create([
                'page_template_id' => $this->template->id,
                'title' => 'Test Post',
                'slug' => 'test-post',
                'status' => 'published',
                'published_at' => now(),
            ]);

            $response = $this->getJson('/api/pages');

            $response->assertOk();
            $response->assertJsonPath('data.0.template.id', $this->template->id);
            $response->assertJsonPath('data.0.template.name', 'Blog');
            $response->assertJsonPath('data.0.template.slug', 'blog');
        });
    });

    describe('GET /api/pages/{slug}', function () {
        test('returns single published page with all fields', function () {
            $page = Page::create([
                'page_template_id' => $this->template->id,
                'title' => 'Test Post',
                'slug' => 'test-post',
                'meta_description' => 'A test post',
                'status' => 'published',
                'published_at' => now(),
            ]);

            // Add field values
            $page->fieldValues()->create([
                'page_template_field_id' => $this->template->fields()->where('name', 'body')->first()->id,
                'value' => '<p>This is the body</p>',
            ]);

            $page->fieldValues()->create([
                'page_template_field_id' => $this->template->fields()->where('name', 'is_featured')->first()->id,
                'value' => '1',
            ]);

            $response = $this->getJson("/api/pages/{$page->slug}");

            $response->assertOk();
            $response->assertJsonPath('data.title', 'Test Post');
            $response->assertJsonPath('data.slug', 'test-post');
            $response->assertJsonPath('data.meta_description', 'A test post');
            $response->assertJsonPath('data.status', 'published');
        });

        test('returns field values with correct types', function () {
            $page = Page::create([
                'page_template_id' => $this->template->id,
                'title' => 'Test Post',
                'slug' => 'test-post',
                'status' => 'published',
                'published_at' => now(),
            ]);

            $bodyField = $this->template->fields()->where('name', 'body')->first();
            $featuredField = $this->template->fields()->where('name', 'is_featured')->first();

            $page->fieldValues()->create([
                'page_template_field_id' => $bodyField->id,
                'value' => '<p>Rich text content</p>',
            ]);

            $page->fieldValues()->create([
                'page_template_field_id' => $featuredField->id,
                'value' => '1',
            ]);

            $response = $this->getJson("/api/pages/{$page->slug}");

            $response->assertOk();
            // Check richtext field
            $response->assertJsonPath('data.fields.body', '<p>Rich text content</p>');
            // Check boolean field (should be cast to boolean)
            $response->assertJsonPath('data.fields.is_featured', true);
        });

        test('returns 404 for draft pages', function () {
            $page = Page::create([
                'page_template_id' => $this->template->id,
                'title' => 'Draft Post',
                'slug' => 'draft-post',
                'status' => 'draft',
            ]);

            $response = $this->getJson("/api/pages/{$page->slug}");

            $response->assertNotFound();
        });

        test('returns 404 for nonexistent slug', function () {
            $response = $this->getJson('/api/pages/nonexistent-slug');

            $response->assertNotFound();
        });

        test('includes template information', function () {
            $page = Page::create([
                'page_template_id' => $this->template->id,
                'title' => 'Test Post',
                'slug' => 'test-post',
                'status' => 'published',
                'published_at' => now(),
            ]);

            $response = $this->getJson("/api/pages/{$page->slug}");

            $response->assertOk();
            $response->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'slug',
                    'meta_description',
                    'status',
                    'published_at',
                    'template' => [
                        'id',
                        'name',
                        'slug',
                    ],
                    'fields',
                ],
            ]);
        });

        test('returns timestamps in ISO 8601 format', function () {
            $publishedAt = now()->subDays(5);
            $page = Page::create([
                'page_template_id' => $this->template->id,
                'title' => 'Test Post',
                'slug' => 'test-post',
                'status' => 'published',
                'published_at' => $publishedAt,
            ]);

            $response = $this->getJson("/api/pages/{$page->slug}");

            $response->assertOk();
            // Verify ISO 8601 format
            $this->assertMatchesRegularExpression(
                '/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/',
                $response->json('data.published_at')
            );
        });
    });
});
