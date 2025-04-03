<?php

namespace Modules\Notification\Tests\Unit\Listeners;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Membership\Events\MemberLeft;
use Modules\Membership\Models\Membership;
use Modules\Notification\Dto\CreateNotificationDto;
use Modules\Notification\Listeners\CreateMemberLeftNotification;
use Modules\Notification\Services\NotificationService;
use Tests\TestCase;

class CreateMemberLeftNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_a_notification_when_member_left_a_group()
    {
        $membership = Membership::factory()->create();

        $dto = new CreateNotificationDto(
            $membership->group_id,
            $membership->user_id,
            "Member $membership->user_id left Group $membership->group_id",
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

        $listener = new CreateMemberLeftNotification($notificationService);
        $event = new MemberLeft($membership->group_id, $membership->user_id, $membership->role->value);
        $listener->handle($event);

        Mockery::close();
        
        $this->assertTrue(true);
    }
}