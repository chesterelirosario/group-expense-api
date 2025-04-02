<?php

namespace Modules\Membership\Listeners;

use Modules\Group\Events\GroupCreated;
use Modules\Membership\Dto\CreateMemberDto;
use Modules\Membership\Enums\Role;
use Modules\Membership\Services\MembershipService;

class CreateGroupOwnerMembership
{
    protected $membershipService;

    public function __construct(MembershipService $membershipService)
    {
        $this->membershipService = $membershipService;
    }
    
    public function handle(GroupCreated $event): void
    {
        $dto = new CreateMemberDto($event->groupId, $event->userId, Role::Owner);
        $this->membershipService->joinGroup($dto);
    }
}
