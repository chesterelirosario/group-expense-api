<?php

namespace Modules\Group\Repositories;

use Modules\Group\Dto\CreateGroupDto;
use Modules\Group\Dto\UpdateGroupDto;
use Modules\Group\Models\Group;

class GroupRepository
{
    public function all(): array
    {
        return Group::all()->toArray();
    }

    public function find(string $id): Group
    {
        return Group::find($id);
    }

    public function create(CreateGroupDto $dto): Group
    {
        return Group::create(['name' => $dto->name]);
    }

    public function update(Group $group, UpdateGroupDto $dto): Group
    {
        $group->update(['name' => $dto->name]);

        return $group;
    }

    public function delete(Group $group): void
    {
        $group->delete();
    }
}
