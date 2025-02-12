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
            'App\Listeners\SendFacebookCrisisCreatedNotification',
            // 'App\Listeners\SendTwitterCrisisCreatedNotification',
        ],

        'App\Events\CrisisUpdated' => [
            // 'App\Listeners\SendSmsCrisisUpdatedNotification',
            // 'App\Listeners\SendFacebookCrisisUpdatedNotification',
            // 'App\Listeners\SendTwitterCrisisUpdatedNotification',
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
