<?php

namespace App\Listeners;

use App\Events\ProcessRequest;
use App\Services\MetricServices;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SaveRequestInformation implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The name of the connection the job should be sent to.
     *
     * @var string|null
     */
    public ?string $connection = 'redis';

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public ?string $queue = 'listeners';

    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly MetricServices $metricServices
    )
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProcessRequest $event): void
    {
        $this->metricServices->save($event->data);
    }
}
