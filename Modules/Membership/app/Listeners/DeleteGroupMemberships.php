<?php

namespace Modules\Membership\Listeners;

use Modules\Group\Events\GroupDeleted;
use Modules\Membership\Repositories\MembershipRepository;

class DeleteGroupMemberships
{
    protected $membershipRepository;

    public function __construct(MembershipRepository $membershipRepository)
    {
        $this->membershipRepository = $membershipRepository;
    }
    
    public function handle(GroupDeleted $event): void
    {
        $memberships = $this->membershipRepository->listMembers($event->groupId);

        foreach ($memberships as $membership) {
            $this->membershipRepository->delete($membership);
        }
    }
}
