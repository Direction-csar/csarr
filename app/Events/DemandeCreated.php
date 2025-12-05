<?php

namespace App\Events;

use App\Models\PublicRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DemandeCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $demande;

    /**
     * Create a new event instance.
     */
    public function __construct(PublicRequest $demande)
    {
        $this->demande = $demande;
    }
}

