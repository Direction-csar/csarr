<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WarehouseUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $warehouse;

    /**
     * Create a new event instance.
     */
    public function __construct($warehouse)
    {
        $this->warehouse = $warehouse;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('warehouses'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'warehouse.updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'warehouse' => [
                'id' => $this->warehouse->id,
                'nom' => $this->warehouse->nom,
                'adresse' => $this->warehouse->adresse,
                'latitude' => (float) $this->warehouse->latitude,
                'longitude' => (float) $this->warehouse->longitude,
                'capacite' => $this->warehouse->capacite,
                'status' => $this->warehouse->status,
                'updated_at' => $this->warehouse->updated_at->toISOString()
            ]
        ];
    }
}
