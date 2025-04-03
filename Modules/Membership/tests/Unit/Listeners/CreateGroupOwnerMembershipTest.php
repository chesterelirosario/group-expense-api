<?php

namespace Modules\Membership\Tests\Unit\Listeners;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Group\Events\GroupCreated;
use Modules\Membership\Dto\CreateMemberDto;
use Modules\Membership\Enums\Role;
use Modules\Membership\Listeners\CreateGroupOwnerMembership;
use Modules\Membership\Models\Membership;
use Modules\Membership\Services\MembershipService;
use Tests\TestCase;

class CreateGroupOwnerMembershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_group_owner_membership()
    {
        $membershipService = Mockery::mock(MembershipService::class);
        $membership = Membership::factory()->create(['role' => Role::Owner]);
        $dto = new CreateMemberDto(
            $membership->group_id,
            $membership->user_id,
            $membership->role,
        );

        $membershipService
            ->shouldReceive('joinGroup')
            ->withArgs(function ($actualDto) use ($dto) {
                return $actualDto instanceof CreateMemberDto
                    && $actualDto->groupId === $dto->groupId
                    && $actualDto->userId === $dto->userId
                    && $actualDto->role === $dto->role;
            })
            ->once();

        $listener = new CreateGroupOwnerMembership($membershipService);
        $event = new GroupCreated($membership->group_id, $membership->user_id);
        $listener->handle($event);

        Mockery::close();
        
        $this->assertTrue(true);
    }
}