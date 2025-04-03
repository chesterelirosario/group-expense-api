<?php

namespace Modules\Group\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Group\Listeners\DeleteEmptyGroup;
use Modules\Group\Listeners\UpdateGroupOwner;
use Modules\Membership\Events\GroupEmptied;
use Modules\Membership\Events\OwnerChanged;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        GroupEmptied::class => [
            DeleteEmptyGroup::class,
        ],
        OwnerChanged::class => [
            UpdateGroupOwner::class,
        ],
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
