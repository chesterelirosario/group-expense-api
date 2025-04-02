<?php

namespace Modules\Group\Listeners;

use Modules\Group\Services\GroupService;
use Modules\Membership\Events\GroupEmptied;

class DeleteEmptyGroup
{
    protected $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }
    
    public function handle(GroupEmptied $event): void
    {
        $group = $this->groupService->findGroup($event->groupId);

        if ($group) $this->groupService->deleteGroup($group);
    }
}
