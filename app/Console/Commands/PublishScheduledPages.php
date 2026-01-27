<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;

class PublishScheduledPages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pages:publish-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish pages that are scheduled and past their publish date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = Page::where('status', 'scheduled')
            ->where('published_at', '<=', now())
            ->update(['status' => 'published']);
        $this->info("Published {$count} scheduled page(s).");

        return Command::SUCCESS;
    }
}
