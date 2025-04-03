<?php

namespace Modules\Group\Tests\Unit\Listeners;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Group\Dto\UpdateGroupDto;
use Modules\Group\Listeners\UpdateGroupOwner;
use Modules\Group\Models\Group;
use Modules\Group\Services\GroupService;
use Modules\Membership\Enums\Role;
use Modules\Membership\Events\OwnerChanged;
use Modules\Membership\Models\Membership;
use Tests\TestCase;

class UpdateGroupOwnerTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_group_owner()
    {
        $groupService = Mockery::mock(GroupService::class);
        $group = Group::factory()->create();
        $membership = Membership::factory()->create(['group_id' => $group->id, 'role' => Role::Member]);
        $dto = new UpdateGroupDto($group->name, $membership->user_id);

        $groupService
            ->shouldReceive('findGroup')
            ->with($group->id)
            ->once()
            ->andReturn($group);

        $groupService
            ->shouldReceive('updateGroup')
            ->withArgs(function ($actualGroup, $actualDto) use ($group, $dto) {
                return $actualGroup === $group
                    && $actualDto instanceof UpdateGroupDto
                    && $actualDto->name === $dto->name
                    && $actualDto->ownerId === $dto->ownerId;
            })
            ->once();

        $listener = new UpdateGroupOwner($groupService);
        $event = new OwnerChanged($membership);
        $listener->handle($event);

        Mockery::close();
        
        $this->assertTrue(true);
    }
}