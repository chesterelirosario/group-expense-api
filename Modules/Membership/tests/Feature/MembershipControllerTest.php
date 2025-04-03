<?php

namespace Modules\Membership\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Membership\Enums\Role;
use Modules\Membership\Models\Membership;
use Tests\TestCase;

class MembershipControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_join_group()
    {
        $user = User::factory()->create();
        $data = ['group_id' => 'group-uuid'];

        $this->actingAs($user)
            ->postJson(route('api.memberships.join'), $data)
            ->assertStatus(201)
            ->assertJsonStructure(['membership'])
            ->assertJsonFragment($data);
    }

    public function test_user_can_view_members_of_a_group()
    {
        $user = User::factory()->create();
        $data = ['group_id' => 'group-uuid'];

        Membership::factory()->create([
            'group_id' => $data['group_id'],
            'user_id' => $user->id,
            'role' => Role::Member,
        ]);

        $this->actingAs($user)
            ->getJson(route('api.memberships.members', $data))
            ->assertStatus(200)
            ->assertJsonStructure(['memberships'])
            ->assertJsonFragment($data);
    }

    public function test_user_cannot_view_members_of_group_they_are_not_in()
    {
        $anotherUser = User::factory()->create();
        $data = ['group_id' => 'group-uuid'];

        $this->actingAs($anotherUser)
            ->getJson(route('api.memberships.members', $data))
            ->assertStatus(403);
    }

    public function test_owner_can_promote_member()
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $data = ['group_id' => 'group-uuid', 'user_id' => $member->id];

        Membership::factory()->create([
            'group_id' => $data['group_id'],
            'user_id' => $owner->id,
            'role' => Role::Owner,
        ]);

        Membership::factory()->create([
            'group_id' => $data['group_id'],
            'user_id' => $member->id,
            'role' => Role::Member,
        ]);

        $this->actingAs($owner)
            ->putJson(route('api.memberships.promote'), $data)
            ->assertStatus(200)
            ->assertJsonStructure(['membership'])
            ->assertJsonFragment($data);
    }

    public function test_admin_can_promote_member()
    {
        $admin = User::factory()->create();
        $member = User::factory()->create();
        $data = ['group_id' => 'group-uuid', 'user_id' => $member->id];

        Membership::factory()->create([
            'group_id' => $data['group_id'],
            'user_id' => $admin->id,
            'role' => Role::Administrator,
        ]);

        Membership::factory()->create([
            'group_id' => $data['group_id'],
            'user_id' => $member->id,
            'role' => Role::Member,
        ]);

        $this->actingAs($admin)
            ->putJson(route('api.memberships.promote'), $data)
            ->assertStatus(200)
            ->assertJsonStructure(['membership'])
            ->assertJsonFragment($data);
    }

    public function test_member_cannot_promote_member()
    {
        $user = User::factory()->create();
        $member = User::factory()->create();
        $data = ['group_id' => 'group-uuid', 'user_id' => $member->id];

        Membership::factory()->create([
            'group_id' => $data['group_id'],
            'user_id' => $user->id,
            'role' => Role::Member,
        ]);

        Membership::factory()->create([
            'group_id' => $data['group_id'],
            'user_id' => $member->id,
            'role' => Role::Member,
        ]);

        $this->actingAs($user)
            ->putJson(route('api.memberships.promote'), $data)
            ->assertStatus(403);
    }

    public function test_owner_can_demote_admin()
    {
        $owner = User::factory()->create();
        $admin = User::factory()->create();
        $data = ['group_id' => 'group-uuid', 'user_id' => $admin->id];

        Membership::factory()->create([
            'group_id' => $data['group_id'],
            'user_id' => $owner->id,
            'role' => Role::Owner,
        ]);

        Membership::factory()->create([
            'group_id' => $data['group_id'],
            'user_id' => $admin->id,
            'role' => Role::Administrator,
        ]);

        $this->actingAs($owner)
            ->putJson(route('api.memberships.demote'), $data)
            ->assertStatus(200)
            ->assertJsonStructure(['membership'])
            ->assertJsonFragment($data);
    }

    public function test_admin_can_demote_admin()
    {
        $admin1 = User::factory()->create();
        $admin2 = User::factory()->create();
        $data = ['group_id' => 'group-uuid', 'user_id' => $admin2->id];

        Membership::factory()->create([
            'group_id' => $data['group_id'],
            'user_id' => $admin1->id,
            'role' => Role::Administrator,
        ]);

        Membership::factory()->create([
            'group_id' => $data['group_id'],
            'user_id' => $admin2->id,
            'role' => Role::Administrator,
        ]);

        $this->actingAs($admin1)
            ->putJson(route('api.memberships.demote'), $data)
            ->assertStatus(200)
            ->assertJsonStructure(['membership'])
            ->assertJsonFragment($data);
    }

    public function test_member_cannot_demote_admin()
    {
        $member = User::factory()->create();
        $admin = User::factory()->create();
        $data = ['group_id' => 'group-uuid', 'user_id' => $admin->id];

        Membership::factory()->create([
            'group_id' => $data['group_id'],
            'user_id' => $member->id,
            'role' => Role::Member,
        ]);

        Membership::factory()->create([
            'group_id' => $data['group_id'],
            'user_id' => $admin->id,
            'role' => Role::Administrator,
        ]);

        $this->actingAs($member)
            ->putJson(route('api.memberships.demote'), $data)
            ->assertStatus(403);
    }

    public function test_user_can_leave_group()
    {
        $user = User::factory()->create();
        $data = ['group_id' => 'group-uuid'];

        Membership::factory()->create([
            'group_id' => $data['group_id'],
            'user_id' => $user->id,
            'role' => Role::Member,
        ]);

        $this->actingAs($user)
            ->deleteJson(route('api.memberships.leave', $data))
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'Left group successfully.']);
    }

    public function test_user_leave_group_they_are_not_in()
    {
        $anotherUser = User::factory()->create();
        $data = ['group_id' => 'group-uuid'];

        $this->actingAs($anotherUser)
            ->deleteJson(route('api.memberships.leave', $data))
            ->assertStatus(403);
    }
}