<?php

namespace Modules\Notification\Listeners;

use Modules\Membership\Events\MemberPromoted;
use Modules\Notification\Dto\CreateNotificationDto;
use Modules\Notification\Services\NotificationService;

class CreateMemberPromotedNotification
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    
    public function handle(MemberPromoted $event): void
    {
        $dto = new CreateNotificationDto(
            $event->groupId,
            $event->userId,
            "Member $event->userId has been promoted to $event->role in Group $event->groupId.",
        );
        $this->notificationService->createNotification($dto);
    }
}
