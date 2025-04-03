<?php

namespace Modules\Membership\Tests\Unit\Listeners;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Group\Events\GroupDeleted;
use Modules\Membership\Listeners\DeleteGroupMemberships;
use Modules\Membership\Models\Membership;
use Modules\Membership\Repositories\MembershipRepository;
use Tests\TestCase;

class DeleteGroupMembershipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_group_memberships()
    {
        $membershipRepository = Mockery::mock(MembershipRepository::class);
        $memberships = Membership::factory()->count(3)->create(['group_id' => 'group-uuid']);

        $membershipRepository
            ->shouldReceive('listMembers')
            ->with('group-uuid')
            ->once()
            ->andReturn($memberships);
        
        $membershipRepository
            ->shouldReceive('delete')
            ->times(3);

        $listener = new DeleteGroupMemberships($membershipRepository);
        $event = new GroupDeleted('group-uuid');
        $listener->handle($event);

        Mockery::close();
        
        $this->assertTrue(true);
    }
}