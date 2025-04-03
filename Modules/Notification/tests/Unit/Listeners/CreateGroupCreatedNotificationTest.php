<?php

namespace Modules\Notification\Tests\Unit\Listeners;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Group\Events\GroupCreated;
use Modules\Notification\Dto\CreateNotificationDto;
use Modules\Notification\Listeners\CreateGroupCreatedNotification;
use Modules\Notification\Services\NotificationService;
use Tests\TestCase;

class CreateGroupCreatedNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_a_notification_when_group_is_created()
    {
        $dto = new CreateNotificationDto(
            'group-uuid',
            'user-uuid',
            "User user-uuid created Group group-uuid",
        );

        $notificationService = Mockery::mock(NotificationService::class);
        $notificationService
            ->shouldReceive('createNotification')
            ->withArgs(function ($actualDto) use ($dto) {
                return $actualDto instanceof CreateNotificationDto
                    && $actualDto->groupId === $dto->groupId
                    && $actualDto->userId === $dto->userId
                    && $actualDto->message === $dto->message;
            })
            ->once();

        $listener = new CreateGroupCreatedNotification($notificationService);
        $event = new GroupCreated($dto->groupId, $dto->userId);
        $listener->handle($event);

        Mockery::close();
        
        $this->assertTrue(true);
    }
}