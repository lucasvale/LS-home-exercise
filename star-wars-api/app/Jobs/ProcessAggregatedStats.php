<?php

namespace App\Jobs;

use App\Dao\AggregatedStatsDao;
use App\Services\AggregatedStatsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ProcessAggregatedStats implements ShouldQueue
{
    use Queueable, Dispatchable;

    private string $stats_key = 'api:stats';

    /**
     * Create a new job instance.
     */
    public function __construct(
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(AggregatedStatsService $aggregatedStatsService): void
    {
        $aggregatedStatsService->processBackgroundTask();
    }

}
