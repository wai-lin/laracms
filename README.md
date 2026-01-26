# ConstantCMS

A simple, developer-friendly headless CMS built with Laravel 12, Livewire Volt, and Flux UI.

[Uptime Status Page](https://stats.uptimerobot.com/0ADlv4KAe6)

## Tech Stack

- **Framework**: Laravel 12
- **Frontend**: Livewire Volt, Flux UI (free)
- **Rich Text Editor**: Quill.js
- **Styling**: Tailwind CSS v4

## External Services

| Service | Purpose | Tier |
|---------|---------|------|
| [Sentry](https://sentry.io) | Error monitoring & email alerts | Free |
| [Umami Cloud](https://cloud.umami.is) | Privacy-friendly analytics | Free |
| [UptimeRobot](https://uptimerobot.com) | Uptime monitoring & alerts | Free |

## Development Setup

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & npm
- Laravel Herd (or Valet/Sail)

### Installation

```bash
# Clone the repository
git clone <repo-url> <project_name>
cd <project_name>

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure .env
# - Set ADMIN_EMAIL, ADMIN_NAME, ADMIN_PASSWORD
# - Set SENTRY_LARAVEL_DSN (optional for local)

# Database setup
php artisan migrate --seed

# Build assets
npm run dev
```

### Running Locally

```bash
# Start Vite dev server
npm run dev

# Access the site via Laravel Herd
# e.g., http://constantcms.test
```

### Scheduler (for scheduled pages)

```bash
# Test manually
php artisan pages:publish-scheduled

# Production cron
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## Features Checklist

### Core CMS

- [x] Page Templates with custom fields
- [x] Field types: text, textarea, richtext, image, boolean
- [x] Pages CRUD with dynamic fields based on template
- [x] Page statuses: draft, published, scheduled
- [x] Settings management (key/value with caching)
- [x] Rich text editor (Quill.js) with dark mode support

### Developer SDK

- [x] `Page::byTemplate('blog', 10)` - Get paginated pages by template
- [x] `Page::bySlug('about')` - Get single published page
- [x] `Page::bySlugOrFail('about')` - Get or 404
- [x] `$page->field('body')` - Get single field value
- [x] `$page->fields` - Get all fields as object
- [x] Scopes: `published()`, `draft()`, `scheduled()`, `ordered()`

### Frontend

- [x] Public page rendering via `/{slug}`
- [x] Template-specific views with fallback
- [x] Custom 404 page
- [x] Waffle Studio theme (example implementation)

### Admin Panel

- [x] Templates CRUD with field management
- [x] Pages CRUD with status/template filtering
- [x] Sidebar navigation

### Infrastructure

- [x] Sentry error monitoring
- [x] Umami analytics
- [x] UptimeRobot monitoring
- [x] Scheduled page auto-publishing (cron)

### Planned Features

- [ ] Repeater fields (arrays of sub-fields)
- [ ] Multi-language support (locale-based pages)
- [ ] SEO preview component (Google, Facebook, Twitter, LinkedIn, Instagram)

## Project Structure

```
app/
├── Console/Commands/
│   └── PublishScheduledPages.php   # Scheduled publishing command
├── Http/Controllers/
│   └── PageController.php          # Frontend page rendering
├── Models/
│   ├── Page.php                    # Page model with SDK methods
│   ├── PageTemplate.php
│   ├── PageTemplateField.php
│   ├── PageFieldValue.php
│   └── Setting.php

resources/views/
├── components/
│   ├── layouts/
│   │   ├── app.blade.php           # Admin layout wrapper
│   │   ├── public.blade.php        # Public layout (minimal)
│   │   └── waffle.blade.php        # Waffle Studio theme layout
│   └── richtext-editor.blade.php   # Quill editor component
├── livewire/admin/
│   ├── pages/                      # Pages CRUD
│   └── templates/                  # Templates CRUD
├── templates/                      # Frontend page templates
│   ├── default.blade.php
│   └── blog.blade.php
└── errors/
    └── 404.blade.php
```

## License

MIT
