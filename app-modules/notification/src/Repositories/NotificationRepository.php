<?php

namespace Modules\Notification\Repositories;

use Modules\Notification\Dto\CreateNotificationDto;
use Modules\Notification\Models\Notification;

class NotificationRepository
{
    public function create(CreateNotificationDto $dto): Notification
    {
        return Notification::create([
            'group_id' => $dto->groupId,
            'user_id' => $dto->userId,
            'message' => $dto->message,
        ]);
    }
}
