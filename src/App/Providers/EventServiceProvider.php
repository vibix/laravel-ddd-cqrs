<?php

declare(strict_types=1);

namespace App\Providers;

use Contexts\Account\Application\EventSubscribers\AccountEventsSubscriber;
use Contexts\Account\Application\EventSubscribers\AccountOneTimePasswordEventsSubscriber;
use Contexts\OneTimePassword\Application\EventSubscribers\OneTimePasswordAccountEventsSubscriber;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    protected $subscribe = [
        AccountEventsSubscriber::class,
        AccountOneTimePasswordEventsSubscriber::class,
        OneTimePasswordAccountEventsSubscriber::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
