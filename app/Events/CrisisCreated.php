<?php

namespace App\Events;

use App\Crisis;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CrisisCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $crisis;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Crisis $crisis)
    {
        $this->crisis = $crisis;
    }


}
