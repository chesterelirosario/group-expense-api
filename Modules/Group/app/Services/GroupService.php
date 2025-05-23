<?php

namespace Modules\Group\Services;

use Modules\Group\Dto\CreateGroupDto;
use Modules\Group\Dto\UpdateGroupDto;
use Modules\Group\Events\GroupCreated;
use Modules\Group\Events\GroupDeleted;
use Modules\Group\Models\Group;
use Modules\Group\Repositories\GroupRepository;

class GroupService
{
    protected $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function getAllGroups(): array
    {
        return $this->groupRepository->all()->toArray();
    }

    public function findGroup(string $id): ?Group
    {
        return $this->groupRepository->find($id);
    }

    public function createGroup(CreateGroupDto $dto): Group
    {
        $group = $this->groupRepository->create($dto);

        event(new GroupCreated($group->id, $dto->ownerId));

        return $group;
    }

    public function updateGroup(Group $group, UpdateGroupDto $dto): Group
    {
        return $this->groupRepository->update($group, $dto);
    }

    public function deleteGroup(Group $group): void
    {
        $groupId = $group->id;

        $this->groupRepository->delete($group);

        event(new GroupDeleted($groupId));
    }
}
