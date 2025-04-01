<?php

namespace Modules\Group\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Group\Models\Group;

class GroupCreated implements ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $groupId;
    public $userId;

    public function __construct(Group $group, User $user)
    {
        $this->groupId = $group->getKey();
        $this->userId = $user->getKey();
    }
}
