<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;

class CrisisCreated
{


    use Dispatchable, SerializesModels;

    public $crisis;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($crisis)
    {
        $this->crisis = $crisis;
    }

}
