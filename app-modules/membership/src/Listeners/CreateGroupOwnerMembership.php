<?php

namespace Modules\Membership\Listeners;

use Modules\Group\Events\GroupCreated;
use Modules\Membership\Enums\Role;
use Modules\Membership\Models\Membership;

class CreateGroupOwnerMembership
{
    public function handle(GroupCreated $event): void
    {
        Membership::create([
            'group_id' => $event->groupId,
            'user_id' => $event->userId,
            'role' => Role::Owner,
        ]);
    }
}
