<?php

namespace Modules\Membership\Policies;

use App\Models\User;
use Modules\Membership\Enums\Role;
use Modules\Membership\Models\Membership;
use Modules\Membership\Repositories\MembershipRepository;

class MembershipPolicy
{
    protected $membershipRepository;

    public function __construct(MembershipRepository $membershipRepository)
    {
        $this->membershipRepository = $membershipRepository;
    }

    public function see(User $user, ?Membership $membership): bool
    {
        if (!$membership) return false;

        return $membership->user_id === $user->id;
    }

    public function promote(User $user, ?Membership $membership): bool
    {
        if (!$membership) return false;

        $requestingUserMembership = $this->membershipRepository->findByUserAndGroup($user->id, $membership->group_id);

        return in_array($requestingUserMembership?->role, [Role::Owner, Role::Administrator]);
    }

    public function demote(User $user, ?Membership $membership): bool
    {
        if (!$membership) return false;

        return $membership->role !== Role::Owner && $this->promote($user, $membership);
    }

    public function leave(User $user, ?Membership $membership): bool
    {
        if (!$membership) return false;

        return $membership->user_id === $user->id;
    }
}
