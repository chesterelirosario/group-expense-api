<?php

namespace Modules\Membership\Listeners;

use Modules\Group\Dto\UpdateGroupDto;
use Modules\Group\Services\GroupService;
use Modules\Membership\Events\OwnerChanged;

class UpdateGroupOwner
{
    protected $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }
    
    public function handle(OwnerChanged $event): void
    {
        $group = $this->groupService->findGroup($event->groupId);

        if ($group) {
            $dto = new UpdateGroupDto($group->name, $event->userId);
            $this->groupService->updateGroup($group, $dto);
        }
    }
}
