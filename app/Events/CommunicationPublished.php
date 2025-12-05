<?php

namespace App\Events;

use App\Models\News;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommunicationPublished
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $communication;

    /**
     * Create a new event instance.
     */
    public function __construct(News $communication)
    {
        $this->communication = $communication;
    }
}

