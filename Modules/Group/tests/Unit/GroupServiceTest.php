<?php

namespace Modules\Group\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Mockery;
use Modules\Group\Dto\CreateGroupDto;
use Modules\Group\Dto\UpdateGroupDto;
use Modules\Group\Events\GroupCreated;
use Modules\Group\Events\GroupDeleted;
use Modules\Group\Models\Group;
use Modules\Group\Repositories\GroupRepository;
use Modules\Group\Services\GroupService;
use Tests\TestCase;

class GroupServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $groupRepository;
    protected $groupService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->groupRepository = Mockery::mock(GroupRepository::class);
        $this->groupService = new GroupService($this->groupRepository);
    }

    public function test_can_fetch_all_groups()
    {
        $groups = Group::factory()->count(3)->make()->toArray();

        $this->groupRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn($groups);

        $result = $this->groupService->getAllGroups();

        $this->assertCount(3, $result);
        $this->assertEquals($groups, $result);
    }

    public function test_can_find_a_group_by_id()
    {
        $group = Group::factory()->make(['id' => 'group-uuid']);

        $this->groupRepository
            ->shouldReceive('find')
            ->with($group->id)
            ->once()
            ->andReturn($group);

        $result = $this->groupService->findGroup($group->id);

        $this->assertNotNull($result);
        $this->assertEquals($group->id, $result->id);
    }

    public function test_returns_null_if_group_not_found()
    {
        $this->groupRepository
            ->shouldReceive('find')
            ->with('invalid-id')
            ->once()
            ->andReturn(null);

        $result = $this->groupService->findGroup('invalid-id');

        $this->assertNull($result);
    }

    public function test_can_create_a_group()
    {
        $dto = new CreateGroupDto('Test Group', 'user-uuid');
        $group = Group::factory()->make([
            'id' => 'group-uuid',
            'name' => $dto->name,
            'owner_id' => $dto->ownerId,
        ]);

        $this->groupRepository
            ->shouldReceive('create')
            ->once()
            ->with($dto)
            ->andReturn($group);

        $result = $this->groupService->createGroup($dto);

        $this->assertEquals($dto->name, $result->name);
    }

    public function test_create_a_group_dispatches_event()
    {
        Event::fake();

        $dto = new CreateGroupDto('Test Group', 'user-uuid');
        $group = Group::factory()->make([
            'id' => 'group-uuid',
            'name' => $dto->name,
            'owner_id' => $dto->ownerId,
        ]);

        $this->groupRepository
            ->shouldReceive('create')
            ->once()
            ->with($dto)
            ->andReturn($group);

        $this->groupService->createGroup($dto);

        Event::assertDispatched(GroupCreated::class, function ($event) use ($group) {
            return $event->groupId === $group->id && $event->userId === $group->owner_id;
        });
    }

    public function test_can_update_a_group()
    {
        $dto = new UpdateGroupDto('New Name', null);
        $group = Group::factory()->create(['name' => 'Old Name']);

        $this->groupRepository
            ->shouldReceive('update')
            ->once()
            ->with($group, $dto)
            ->andReturn(new Group(['id' => $group->id, 'name' => $dto->name]));

        $updatedGroup = $this->groupService->updateGroup($group, $dto);

        $this->assertEquals($dto->name, $updatedGroup->name);
    }

    public function test_can_delete_a_group()
    {
        $group = Group::factory()->make(['id' => 'group-uuid']);

        $this->groupRepository
            ->shouldReceive('delete')
            ->once()
            ->with($group);

        $this->groupService->deleteGroup($group);

        $this->assertTrue(true);
    }

    public function test_delete_a_group_dispatches_event()
    {
        Event::fake();

        $group = Group::factory()->make(['id' => 'group-uuid']);

        $this->groupRepository
            ->shouldReceive('delete')
            ->once()
            ->with($group);

        $this->groupService->deleteGroup($group);

        Event::assertDispatched(GroupDeleted::class, function ($event) use ($group) {
            return $event->groupId === $group->id;
        });
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
