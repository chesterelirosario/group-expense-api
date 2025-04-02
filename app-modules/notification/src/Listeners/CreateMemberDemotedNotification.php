<?php

namespace Modules\Notification\Listeners;

use Modules\Membership\Events\MemberDemoted;
use Modules\Notification\Dto\CreateNotificationDto;
use Modules\Notification\Services\NotificationService;

class CreateMemberDemotedNotification
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    
    public function handle(MemberDemoted $event): void
    {
        $dto = new CreateNotificationDto(
            $event->groupId,
            $event->userId,
            "Member $event->userId has been demoted to $event->role in Group $event->groupId.",
        );
        $this->notificationService->createNotification($dto);
    }
}
