<?php

namespace App\Providers;

use App\Events\OrderCreatedEvent;
use App\Events\PasswordResetTokenCreatedEvent;
use App\Events\RegisteredEvent;

use App\Listeners\OrderCreatedListener;
use App\Listeners\PasswordResetTokenCreatedListener;
use App\Listeners\RegisteredListener;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Google\GoogleExtendSocialite;
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
        SocialiteWasCalled::class => [
            VKontakteExtendSocialite::class,
            GoogleExtendSocialite::class
        ],

        RegisteredEvent::class => [
            RegisteredListener::class
        ],

        OrderCreatedEvent::class => [
            OrderCreatedListener::class
        ],

        PasswordResetTokenCreatedEvent::class => [
            PasswordResetTokenCreatedListener::class
        ],
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
