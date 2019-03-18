<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\CrisisCreated' => [
            // 'App\Listeners\SendAgenciesSmsCrisisCreatedNotification',
            // 'App\Listeners\SendSmsCrisisCreatedNotification',
            'App\Listeners\SendTwitterCrisisCreatedNotification',
            'App\Listeners\SendFacebookCrisisCreatedNotification',
        ],

        'App\Events\CrisisUpdated' => [
            'App\Listeners\SendSmsCrisisUpdatedNotification',
            'App\Listeners\SendTwitterCrisisUpdatedNotification',
            'App\Listeners\SendFacebookCrisisUpdatedNotification',
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
