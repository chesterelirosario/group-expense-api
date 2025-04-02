<?php

namespace Modules\Notification\Listeners;

use Modules\Membership\Events\MemberLeft;
use Modules\Notification\Dto\CreateNotificationDto;
use Modules\Notification\Services\NotificationService;

class CreateMemberLeftNotification
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    
    public function handle(MemberLeft $event): void
    {
        $dto = new CreateNotificationDto(
            $event->groupId,
            $event->userId,
            "Member $event->userId left Group $event->groupId",
        );
        $this->notificationService->createNotification($dto);
    }
}
