<?php

namespace Modules\Notification\Listeners;

use Modules\Group\Events\GroupCreated;
use Modules\Notification\Dto\CreateNotificationDto;
use Modules\Notification\Services\NotificationService;

class CreateGroupCreatedNotification
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    
    public function handle(GroupCreated $event): void
    {
        $dto = new CreateNotificationDto(
            $event->groupId,
            $event->userId,
            "User $event->userId created Group $event->groupId",
        );
        $this->notificationService->createNotification($dto);
    }
}
