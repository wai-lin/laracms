<?php

namespace Database\Seeders;

use App\Models\PageTemplate;
use App\Models\PageTemplateField;
use App\Models\Setting;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedAdminUser();
        $this->seedDefaultSettings();
        $this->seedBasicPageTemplate();
    }

    protected function seedAdminUser(): void
    {
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            [
                'name' => env('ADMIN_NAME', 'ADMIN_USER'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            ],
        );
    }

    protected function seedDefaultSettings(): void
    {
        $settings = [
            'site_name' => 'My CMS',
            'site_description' => 'A simple content management system.',
        ];

        foreach ($settings as $key => $value) {
            Setting::firstOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }
    }

    protected function seedBasicPageTemplate(): void
    {
        $template = PageTemplate::firstOrCreate(
            ['slug' => 'basic-page'],
            [
                'name' => 'Basic Page',
                'description' => 'A simple basic page.',
            ]
        );

        $fields = [
            [
                'name' => 'body',
                'label' => 'Content',
                'type' => 'richtext',
                'order' => 1,
                'required' => true,
            ],
            [
                'name' => 'featured_image',
                'label' => 'Featured Image',
                'type' => 'image',
                'order' => 2,
                'required' => false,
            ],
        ];

        foreach ($fields as $field) {
            PageTemplateField::firstOrCreate(
                [
                    'page_template_id' => $template->id,
                    'name' => $field['name'],
                ],
                $field,
            );
        }
    }
}
