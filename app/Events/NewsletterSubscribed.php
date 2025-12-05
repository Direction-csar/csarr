<?php

namespace App\Events;

use App\Models\NewsletterSubscriber;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewsletterSubscribed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $subscriber;

    /**
     * Create a new event instance.
     */
    public function __construct(NewsletterSubscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }
}

