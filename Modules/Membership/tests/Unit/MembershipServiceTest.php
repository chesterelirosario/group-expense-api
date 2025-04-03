<?php

namespace Modules\Membership\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Mockery;
use Modules\Membership\Dto\CreateMemberDto;
use Modules\Membership\Dto\DeleteMemberDto;
use Modules\Membership\Dto\UpdateMemberDto;
use Modules\Membership\Enums\Role;
use Modules\Membership\Events\GroupEmptied;
use Modules\Membership\Events\MemberDemoted;
use Modules\Membership\Events\MemberJoined;
use Modules\Membership\Events\MemberLeft;
use Modules\Membership\Events\MemberPromoted;
use Modules\Membership\Events\OwnerChanged;
use Modules\Membership\Exceptions\MemberAlreadyAMemberException;
use Modules\Membership\Exceptions\MemberAlreadyAnAdminOrOwnerException;
use Modules\Membership\Exceptions\MemberAlreadyExistsException;
use Modules\Membership\Models\Membership;
use Modules\Membership\Repositories\MembershipRepository;
use Modules\Membership\Services\MembershipService;
use Tests\TestCase;

class MembershipServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $membershipRepository;
    protected $membershipService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->membershipRepository = Mockery::mock(MembershipRepository::class);
        $this->membershipService = new MembershipService($this->membershipRepository);
    }

    public function test_can_list_members()
    {
        $members = Membership::factory()->count(3)->make(['group_id' => 'group-uuid'])->toArray();

        $this->membershipRepository
            ->shouldReceive('listMembers')
            ->once()
            ->andReturn($members);

        $result = $this->membershipService->listMembers('group-uuid');

        $this->assertCount(3, $result);
        $this->assertEquals($members, $result);
    }

    public function test_can_find_member()
    {
        $member = Membership::factory()->make();

        $this->membershipRepository
            ->shouldReceive('findByUserAndGroup')
            ->with($member->user_id, $member->group_id)
            ->once()
            ->andReturn($member);

        $result = $this->membershipService->findMember($member->user_id, $member->group_id);

        $this->assertNotNull($result);
        $this->assertEquals($member->id, $result->id);
    }

    public function test_returns_null_if_member_not_found()
    {
        $this->membershipRepository
            ->shouldReceive('findByUserAndGroup')
            ->with('invalid-id', 'invalid-id')
            ->once()
            ->andReturn(null);

            $result = $this->membershipService->findMember('invalid-id', 'invalid-id');

        $this->assertNull($result);
    }

    public function test_can_join_group_and_dispatches_event()
    {
        Event::fake();

        $dto = new CreateMemberDto('group-uuid', 'user-uuid', Role::Member);

        // No existing membership
        $this->membershipRepository
            ->shouldReceive('findByUserAndGroup')
            ->with($dto->userId, $dto->groupId)
            ->once()
            ->andReturn(null);

        $membership = new Membership([
            'group_id' => $dto->groupId,
            'user_id' => $dto->userId,
            'role' => $dto->role,
        ]);

        $this->membershipRepository
            ->shouldReceive('create')
            ->with($dto)
            ->once()
            ->andReturn($membership);

        $result = $this->membershipService->joinGroup($dto);

        $this->assertEquals($membership, $result);
        Event::assertDispatched(MemberJoined::class, function ($event) use ($membership) {
            return $event->groupId === $membership->group_id
                && $event->userId === $membership->user_id
                && $event->role === $membership->role->value;
        });
    }

    public function test_join_group_throws_exception_if_member_already_exists()
    {
        $dto = new CreateMemberDto('group-uuid', 'user-uuid', Role::Member);
        $existingMembership = new Membership([
            'group_id' => $dto->groupId,
            'user_id' => $dto->userId,
            'role' => $dto->role,
        ]);

        $this->membershipRepository
            ->shouldReceive('findByUserAndGroup')
            ->with($dto->userId, $dto->groupId)
            ->once()
            ->andReturn($existingMembership);

        $this->expectException(MemberAlreadyExistsException::class);

        $this->membershipService->joinGroup($dto);
    }

    public function test_can_promote_member_and_dispatches_event()
    {
        Event::fake();

        $dto = new UpdateMemberDto('group-uuid', 'user-uuid');
        $membership = new Membership([
            'group_id' => $dto->groupId,
            'user_id' => $dto->userId,
            'role' => Role::Member,
        ]);

        $this->membershipRepository
            ->shouldReceive('findByUserAndGroup')
            ->with($dto->userId, $dto->groupId)
            ->once()
            ->andReturn($membership);

        $this->membershipRepository
            ->shouldReceive('update')
            ->with($membership, Role::Administrator)
            ->once()
            ->andReturn($membership);

        $result = $this->membershipService->promoteMember($dto);

        $this->assertEquals($membership, $result);
        Event::assertDispatched(MemberPromoted::class, function ($event) use ($membership) {
            return $event->groupId === $membership->group_id
                && $event->userId === $membership->user_id
                && $event->role === $membership->role->value;
        });
    }

    public function test_promote_member_throws_exception_if_member_already_an_owner_or_admin()
    {
        $dto = new UpdateMemberDto('group-uuid', 'user-uuid');
        $membership = new Membership([
            'group_id' => $dto->groupId,
            'user_id' => $dto->userId,
            'role' => Role::Administrator,
        ]);

        $this->membershipRepository
            ->shouldReceive('findByUserAndGroup')
            ->with($dto->userId, $dto->groupId)
            ->once()
            ->andReturn($membership);

        $this->expectException(MemberAlreadyAnAdminOrOwnerException::class);

        $this->membershipService->promoteMember($dto);
    }

    public function test_can_demote_member_and_dispatches_event()
    {
        Event::fake();

        $dto = new UpdateMemberDto('group-uuid', 'user-uuid');
        $membership = new Membership([
            'group_id' => $dto->groupId,
            'user_id' => $dto->userId,
            'role' => Role::Administrator,
        ]);

        $this->membershipRepository
            ->shouldReceive('findByUserAndGroup')
            ->with($dto->userId, $dto->groupId)
            ->once()
            ->andReturn($membership);

        $this->membershipRepository
            ->shouldReceive('update')
            ->with($membership, Role::Member)
            ->once()
            ->andReturn($membership);

        $result = $this->membershipService->demoteMember($dto);

        $this->assertEquals($membership, $result);
        Event::assertDispatched(MemberDemoted::class, function ($event) use ($membership) {
            return $event->groupId === $membership->group_id
                && $event->userId === $membership->user_id
                && $event->role === $membership->role->value;
        });
    }

    public function test_demote_member_throws_exception_if_member_already_a_member()
    {
        $dto = new UpdateMemberDto('group-uuid', 'user-uuid');
        $membership = new Membership([
            'group_id' => $dto->groupId,
            'user_id' => $dto->userId,
            'role' => Role::Member,
        ]);

        $this->membershipRepository
            ->shouldReceive('findByUserAndGroup')
            ->with($dto->userId, $dto->groupId)
            ->once()
            ->andReturn($membership);

        $this->expectException(MemberAlreadyAMemberException::class);

        $this->membershipService->demoteMember($dto);
    }

    public function test_can_leave_group_and_dispatches_event()
    {
        Event::fake();

        $dto = new DeleteMemberDto('group-uuid', 'user-uuid');
        $membership = new Membership([
            'group_id' => $dto->groupId,
            'user_id' => $dto->userId,
            'role' => Role::Member,
        ]);

        $this->membershipRepository
            ->shouldReceive('findByUserAndGroup')
            ->with($dto->userId, $dto->groupId)
            ->once()
            ->andReturn($membership);

        $this->membershipRepository
            ->shouldReceive('delete')
            ->with($membership)
            ->once();

        $this->membershipService->leaveGroup($dto);

        Event::assertDispatched(MemberLeft::class, function ($event) use ($membership) {
            return $event->groupId === $membership->group_id
                && $event->userId === $membership->user_id
                && $event->role === $membership->role->value;
        });
    }

    public function test_can_transfer_ownership_when_owner_leaves_and_dispatches_owner_changed_event()
    {
        Event::fake();

        $dto = new DeleteMemberDto('group-uuid', 'user-uuid');
        $owner = new Membership([
            'group_id' => $dto->groupId,
            'user_id' => $dto->userId,
            'role' => Role::Owner,
        ]);
        $member = new Membership([
            'group_id' => $dto->groupId,
            'user_id' => $dto->userId,
            'role' => Role::Member,
        ]);

        $this->membershipRepository
            ->shouldReceive('findByUserAndGroup')
            ->with($dto->userId, $dto->groupId)
            ->once()
            ->andReturn($owner);

        $this->membershipRepository
            ->shouldReceive('getNextOwner')
            ->with($dto->groupId)
            ->once()
            ->andReturn($member);

        $this->membershipRepository
            ->shouldReceive('update')
            ->with($member, Role::Administrator)
            ->once()
            ->andReturn($member);

        $this->membershipRepository
            ->shouldReceive('delete')
            ->with($owner)
            ->once();

        $this->membershipService->leaveGroup($dto);

        Event::assertDispatched(OwnerChanged::class, function ($event) use ($member) {
            return $event->groupId === $member->group_id
                && $event->userId === $member->user_id
                && $event->role === $member->role->value;
        });
    }

    public function test_can_dispatch_group_emptied_event_when_group_is_empty()
    {
        Event::fake();

        $dto = new DeleteMemberDto('group-uuid', 'user-uuid');
        $owner = new Membership([
            'group_id' => $dto->groupId,
            'user_id' => $dto->userId,
            'role' => Role::Owner,
        ]);

        $this->membershipRepository
            ->shouldReceive('findByUserAndGroup')
            ->with($dto->userId, $dto->groupId)
            ->once()
            ->andReturn($owner);

        $this->membershipRepository
            ->shouldReceive('getNextOwner')
            ->with($dto->groupId)
            ->once()
            ->andReturn(null);

        $this->membershipRepository
            ->shouldReceive('delete')
            ->with($owner)
            ->once();

        $this->membershipService->leaveGroup($dto);

        Event::assertDispatched(GroupEmptied::class, function ($event) use ($owner) {
            return $event->groupId === $owner->group_id;
        });
    }
}