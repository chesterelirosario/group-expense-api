<?php

namespace Modules\Notification\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Group\Events\GroupCreated;
use Modules\Membership\Events\MemberDemoted;
use Modules\Membership\Events\MemberJoined;
use Modules\Membership\Events\MemberLeft;
use Modules\Membership\Events\MemberPromoted;
use Modules\Notification\Listeners\CreateGroupCreatedNotification;
use Modules\Notification\Listeners\CreateMemberDemotedNotification;
use Modules\Notification\Listeners\CreateMemberJoinedNotification;
use Modules\Notification\Listeners\CreateMemberLeftNotification;
use Modules\Notification\Listeners\CreateMemberPromotedNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        GroupCreated::class => [
            CreateGroupCreatedNotification::class,
        ],
        MemberJoined::class => [
            CreateMemberJoinedNotification::class,
        ],
        MemberPromoted::class => [
            CreateMemberPromotedNotification::class,
        ],
        MemberDemoted::class => [
            CreateMemberDemotedNotification::class,
        ],
        MemberLeft::class => [
            CreateMemberLeftNotification::class,
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
