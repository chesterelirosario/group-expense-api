<?php

namespace Modules\Group\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Group\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;

class GroupControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_groups()
    {
        $user = User::factory()->create();
        Group::factory()->count(2)->create();

        $this->actingAs($user)
            ->getJson(route('api.groups.index'))
            ->assertStatus(200)
            ->assertJsonCount(2, 'groups');
    }

    public function test_guest_cannot_access_groups()
    {
        $this->getJson(route('api.groups.index'))
            ->assertStatus(401);
    }

    public function test_user_can_create_a_group()
    {
        $user = User::factory()->create();
        $groupData = ['name' => 'New Group'];

        $this->actingAs($user)
            ->postJson(route('api.groups.store'), $groupData)
            ->assertStatus(201)
            ->assertJsonFragment(['name' => 'New Group']);
    }

    public function test_user_can_update_group_if_authorized()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create(['owner_id' => $user->id]);

        $updateData = ['name' => 'Updated Group'];

        $this->actingAs($user)
            ->putJson(route('api.groups.update', $group), $updateData)
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Group']);
    }

    public function test_user_cannot_update_group_without_permission()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $updateData = ['name' => 'Unauthorized Update'];

        $this->actingAs($user)
            ->putJson(route('api.groups.update', $group), $updateData)
            ->assertStatus(403);
    }

    public function test_user_can_delete_group_if_authorized()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create(['owner_id' => $user->id]);

        $this->actingAs($user)
            ->deleteJson(route('api.groups.destroy', $group))
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'Deleted group successfully.']);
    }

    public function test_user_cannot_delete_group_without_permission()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $this->actingAs($user)
            ->deleteJson(route('api.groups.destroy', $group))
            ->assertStatus(403);
    }
}
