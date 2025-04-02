<?php

namespace Modules\Group\Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Modules\Group\Dto\CreateGroupDto;
use Modules\Group\Dto\UpdateGroupDto;
use Modules\Group\Models\Group;
use Modules\Group\Repositories\GroupRepository;
use Tests\TestCase;

class GroupRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected GroupRepository $groupRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->groupRepository = new GroupRepository();
    }

    public function test_can_fetch_all_groups()
    {
        Group::factory()->count(3)->create();

        $groups = $this->groupRepository->all();

        $this->assertCount(3, $groups);
    }

    public function test_can_find_a_group_by_id()
    {
        $group = Group::factory()->create();

        $foundGroup = $this->groupRepository->find($group->id);

        $this->assertNotNull($foundGroup);
        $this->assertEquals($group->id, $foundGroup->id);
    }

    public function test_returns_null_if_group_not_found()
    {
        $foundGroup = $this->groupRepository->find(Str::uuid());

        $this->assertNull($foundGroup);
    }

    public function test_can_create_a_group()
    {
        $user = User::factory()->create();
        $dto = new CreateGroupDto('Test Group', $user->id);

        $group = $this->groupRepository->create($dto);

        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'name' => 'Test Group',
            'owner_id' => $user->id,
        ]);
    }

    public function test_can_update_a_group()
    {
        $group = Group::factory()->create(['name' => 'Old Name']);
        $dto = new UpdateGroupDto('New Name', null);

        $updatedGroup = $this->groupRepository->update($group, $dto);

        $this->assertEquals('New Name', $updatedGroup->name);
        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'name' => 'New Name',
        ]);
    }

    public function test_can_delete_a_group()
    {
        $group = Group::factory()->create();

        $this->groupRepository->delete($group);

        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    }
}