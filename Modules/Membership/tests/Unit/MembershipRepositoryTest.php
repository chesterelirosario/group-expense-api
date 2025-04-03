<?php

namespace Modules\Membership\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Membership\Dto\CreateMemberDto;
use Modules\Membership\Enums\Role;
use Modules\Membership\Models\Membership;
use Modules\Membership\Repositories\MembershipRepository;
use Tests\TestCase;

class MembershipRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected MembershipRepository $membershipRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->membershipRepository = new MembershipRepository();
    }

    public function test_can_list_members_of_a_group()
    {
        Membership::factory()->count(3)->create([
            'group_id' => 'group-uuid',
            'user_id' => 'user-uuid',
            'role' => Role::Member
        ]);

        $members = $this->membershipRepository->listMembers('group-uuid');

        $this->assertCount(3, $members);
    }

    public function test_can_find_membership_by_user_and_group()
    {
        $membership = Membership::factory()->create([
            'group_id' => 'group-uuid',
            'user_id' => 'user-uuid'
        ]);

        $foundMembership = $this->membershipRepository->findByUserAndGroup($membership->user_id, $membership->group_id);

        $this->assertNotNull($foundMembership);
        $this->assertEquals($membership->id, $foundMembership->id);
    }

    public function test_returns_null_if_membership_not_found()
    {
        $foundMembership = $this->membershipRepository->findByUserAndGroup('invalid-id', 'invalid-id');

        $this->assertNull($foundMembership);
    }

    public function test_can_return_next_admin_owner()
    {
        $membership = Membership::factory()->create([
            'group_id' => 'group-uuid',
            'role' => Role::Administrator
        ]);

        $nextOwner = $this->membershipRepository->getNextOwner($membership->group_id);

        $this->assertNotNull($nextOwner);
        $this->assertEquals(Role::Administrator, $nextOwner->role);
    }

    public function test_can_return_next_member_owner()
    {
        $membership = Membership::factory()->create([
            'group_id' => 'group-uuid',
            'role' => Role::Member,
        ]);

        $nextOwner = $this->membershipRepository->getNextOwner($membership->group_id);

        $this->assertNotNull($nextOwner);
        $this->assertEquals(Role::Member, $nextOwner->role);
    }

    public function test_returns_null_if_next_owner_not_found()
    {
        $nextOwner = $this->membershipRepository->getNextOwner('invalid-id');

        $this->assertNull($nextOwner);
    }

    public function test_can_create_a_membership()
    {
        $dto = new CreateMemberDto('group-uuid', 'user-uuid', Role::Member);
        $this->membershipRepository->create($dto);

        $this->assertDatabaseHas('memberships', [
            'group_id' => $dto->groupId,
            'user_id' => $dto->userId,
            'role' => $dto->role,
        ]);
    }

    public function test_can_update_a_membership()
    {
        $membership = Membership::factory()->create(['role' => Role::Member]);

        $updatedMembership = $this->membershipRepository->update($membership, Role::Administrator);

        $this->assertEquals(Role::Administrator, $updatedMembership->role);
        $this->assertDatabaseHas('memberships', [
            'id' => $membership->id,
            'role' => $updatedMembership->role,
        ]);
    }

    public function test_can_delete_a_membership()
    {
        $membership = Membership::factory()->create();

        $this->membershipRepository->delete($membership);

        $this->assertDatabaseMissing('memberships', ['id' => $membership->id]);
    }
}