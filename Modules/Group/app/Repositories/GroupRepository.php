<?php

namespace Modules\Group\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Group\Dto\CreateGroupDto;
use Modules\Group\Dto\UpdateGroupDto;
use Modules\Group\Models\Group;

class GroupRepository
{
    public function all(): Collection
    {
        return Group::all();
    }

    public function find(string $id): ?Group
    {
        return Group::find($id);
    }

    public function create(CreateGroupDto $dto): Group
    {
        return Group::create([
            'name' => $dto->name,
            'owner_id' => $dto->ownerId
        ]);
    }

    public function update(Group $group, UpdateGroupDto $dto): Group
    {
        $group->update([
            'name' => $dto->name ?? $group->name,
            'owner_id' => $dto->ownerId ?? $group->owner_id,
        ]);

        return $group;
    }

    public function delete(Group $group): void
    {
        $group->delete();
    }
}
