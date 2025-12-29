<?php

use App\Jobs\ProcessAggregatedStats;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new ProcessAggregatedStats, env('REDIS_QUEUE', 'swapi_queue'), 'redis')->everyFiveMinutes();

