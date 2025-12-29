<?php

namespace App\Events;

use App\Data\ProcessRequestData;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;

class ProcessRequest
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(public readonly ProcessRequestData $data){}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
