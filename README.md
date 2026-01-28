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
| [DigitalOcean Storage](https://www.digitalocean.com/products/spaces) | Image & backup storage | 5$ / month |
| [Resend](https://resend.com) | Transactional email service | Free tier available |
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
# - Set AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, AWS_DEFAULT_REGION (for S3)
# - Set RESEND_API_KEY (for email service)
# - Set SENTRY_LARAVEL_DSN (optional for local)
# - Set BACKUP_ARCHIVE_PASSWORD (optional for backup encryption)

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

### Backups (automated to S3)

```bash
# Trigger a backup manually
php artisan backup:run

# Monitor backup health
php artisan backup:monitor

# Clean up old backups
php artisan backup:clean
```

Backups are automatically stored on S3 and include the database and application files. See `config/backup.php` for detailed backup configuration.

## Features Checklist

### Core CMS

- [x] Page Templates with custom fields
- [x] Field types: text, textarea, richtext, image, boolean
- [x] Pages CRUD with dynamic fields based on template
- [x] Page statuses: draft, published, scheduled
- [x] Settings management (key/value with caching)
- [x] Rich text editor (Quill.js) with dark mode support
- [x] Image storage via Amazon S3
- [x] Automatic backups to S3 with scheduled cleanup

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
- [x] Quote API integration for auto-generating page titles
- [x] Sidebar navigation

### Infrastructure

- [x] Sentry error monitoring
- [x] Umami analytics
- [x] UptimeRobot monitoring
- [x] Scheduled page auto-publishing (cron)
- [x] Scheduled daily backup (cron)

### Planned Features

- [ ] Headless CMS API for using the platform as a backend service
- [ ] Add testing on major features
- [ ] Repeater fields (arrays of sub-fields)
- [ ] Multi-language support (locale-based pages)
- [ ] SEO preview component (Google, Facebook, Twitter, LinkedIn, Instagram)

## Project Structure

```
app/
├── Console/Commands/
│   └── PublishScheduledPages.php   # Scheduled publishing command
├── Helpers/
│   └── StorageHelper.php           # S3 URL management with caching
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
