<?php

namespace Modules\Notification\Listeners;

use Modules\Membership\Events\MemberJoined;
use Modules\Notification\Dto\CreateNotificationDto;
use Modules\Notification\Services\NotificationService;

class CreateMemberJoinedNotification
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    
    public function handle(MemberJoined $event): void
    {
        $dto = new CreateNotificationDto(
            $event->groupId,
            $event->userId,
            "Member $event->userId joined Group $event->groupId",
        );
        $this->notificationService->createNotification($dto);
    }
}
