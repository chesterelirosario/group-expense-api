<?php

namespace Modules\Notification\Dto;

class CreateNotificationDto
{
    public function __construct(
        public string $groupId,
        public string $userId,
        public string $message,
    ) {}
}
