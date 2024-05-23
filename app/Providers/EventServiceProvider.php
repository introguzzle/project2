<?php

namespace App\Providers;

use App\Events\OrderCallbackReceivedEvent;
use App\Events\OrderCreatedEvent;
use App\Events\RegisteredEvent;
use App\Listeners\OrderCallbackReceivedListener;
use App\Listeners\OrderCreatedListener;
use App\Listeners\RegisteredListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\VKontakte\VKontakteExtendSocialite;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        OrderCallbackReceivedEvent::class => [
            OrderCallbackReceivedListener::class
        ],

        SocialiteWasCalled::class => [
            VKontakteExtendSocialite::class,
        ],

        RegisteredEvent::class => [
            RegisteredListener::class
        ],

        OrderCreatedEvent::class => [
            OrderCreatedListener::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
