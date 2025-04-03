<?php

namespace Modules\Membership\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Group\Events\GroupCreated;
use Modules\Group\Events\GroupDeleted;
use Modules\Membership\Listeners\CreateGroupOwnerMembership;
use Modules\Membership\Listeners\DeleteGroupMemberships;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        GroupCreated::class => [
            CreateGroupOwnerMembership::class,
        ],
        GroupDeleted::class => [
            DeleteGroupMemberships::class,
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
