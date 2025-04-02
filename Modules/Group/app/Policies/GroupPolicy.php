<?php

namespace Modules\Group\Policies;

use App\Models\User;
use Modules\Group\Models\Group;

class GroupPolicy
{
    public function update(User $user, Group $group): bool
    {
        return $group->owner_id === $user->id;
    }

    public function destroy(User $user, Group $group): bool
    {
        return $group->owner_id === $user->id;
    }
}
