<?php

namespace Modules\Notification\Services;

use Modules\Notification\Dto\CreateNotificationDto;
use Modules\Notification\Models\Notification;
use Modules\Notification\Repositories\NotificationRepository;

class NotificationService
{
    protected $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function createNotification(CreateNotificationDto $dto): Notification
    {
        $notification = $this->notificationRepository->create($dto);

        return $notification;
    }
}
