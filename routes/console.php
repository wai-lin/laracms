<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('pages:publish-scheduled')
    ->everyMinute();

Schedule::command('backup:clean')
    ->timezone('Asia/Bangkok')
    ->dailyAt('01:00');
Schedule::command('backup:run')
    ->timezone('Asia/Bangkok')
    ->dailyAt('02:00');
