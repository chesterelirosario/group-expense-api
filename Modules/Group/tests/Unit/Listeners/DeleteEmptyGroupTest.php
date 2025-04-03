<?php

namespace Modules\Group\Tests\Unit\Listeners;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Group\Listeners\DeleteEmptyGroup;
use Modules\Group\Models\Group;
use Modules\Group\Services\GroupService;
use Modules\Membership\Events\GroupEmptied;
use Tests\TestCase;

class DeleteEmptyGroupTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_an_empty_group()
    {
        $groupService = Mockery::mock(GroupService::class);
        $group = Group::factory()->create();

        $groupService
            ->shouldReceive('findGroup')
            ->with($group->id)
            ->once()
            ->andReturn($group);
        
        $groupService
            ->shouldReceive('deleteGroup')
            ->with($group)
            ->once();

        $listener = new DeleteEmptyGroup($groupService);
        $event = new GroupEmptied($group->id);
        $listener->handle($event);

        Mockery::close();
        
        $this->assertTrue(true);
    }
}