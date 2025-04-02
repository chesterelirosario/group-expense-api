<?php

namespace Modules\Membership\Listeners;

use Modules\Group\Events\GroupDeleted;
use Modules\Membership\Repositories\MembershipRepository;
use Modules\Membership\Services\MembershipService;

class DeleteGroupMemberships
{
    protected $membershipService;
    protected $membershipRepository;

    public function __construct(MembershipService $membershipService, MembershipRepository $membershipRepository)
    {
        $this->membershipService = $membershipService;
        $this->membershipRepository = $membershipRepository;
    }
    
    public function handle(GroupDeleted $event): void
    {
        $memberships = $this->membershipService->listMembers($event->groupId);

        foreach ($memberships as $membership) {
            $this->membershipRepository->delete($membership);
        }
    }
}
