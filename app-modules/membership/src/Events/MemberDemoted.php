<?php

namespace Modules\Membership\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Membership\Models\Membership;

class MemberDemoted implements ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $groupId;
    public string $userId;
    public string $role;

    public function __construct(Membership $membership)
    {
        $this->groupId = $membership->group_id;
        $this->userId = $membership->user_id;
        $this->role = $membership->role->value;
    }
}
