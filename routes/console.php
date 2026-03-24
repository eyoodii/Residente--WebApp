<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Fetch fresh Buguey news every morning at 6:00 AM
Schedule::command('news:fetch-buguey')->dailyAt('06:00');
