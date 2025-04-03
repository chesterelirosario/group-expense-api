<?php

namespace Modules\Membership\Services;

use Modules\Membership\Dto\CreateMemberDto;
use Modules\Membership\Dto\DeleteMemberDto;
use Modules\Membership\Dto\UpdateMemberDto;
use Modules\Membership\Enums\Role;
use Modules\Membership\Events\GroupEmptied;
use Modules\Membership\Events\MemberDemoted;
use Modules\Membership\Events\MemberJoined;
use Modules\Membership\Events\MemberLeft;
use Modules\Membership\Events\MemberPromoted;
use Modules\Membership\Events\OwnerChanged;
use Modules\Membership\Exceptions\MemberAlreadyAMemberException;
use Modules\Membership\Exceptions\MemberAlreadyAnAdminOrOwnerException;
use Modules\Membership\Exceptions\MemberAlreadyExistsException;
use Modules\Membership\Models\Membership;
use Modules\Membership\Repositories\MembershipRepository;

class MembershipService
{
    protected $membershipRepository;

    public function __construct(MembershipRepository $membershipRepository)
    {
        $this->membershipRepository = $membershipRepository;
    }

    public function listMembers(string $groupId): array
    {
        $memberships = $this->membershipRepository->listMembers($groupId);

        return $memberships->toArray();
    }

    public function findMember(string $userId, string $groupId): ?Membership
    {
        return $this->membershipRepository->findByUserAndGroup($userId, $groupId);
    }

    public function joinGroup(CreateMemberDto $dto): Membership
    {
        $existingMembership = $this->membershipRepository->findByUserAndGroup($dto->userId, $dto->groupId);

        if ($existingMembership) {
            throw new MemberAlreadyExistsException();
        }

        $membership = $this->membershipRepository->create($dto);

        event(new MemberJoined($membership));

        return $membership;
    }

    public function promoteMember(UpdateMemberDto $dto): Membership
    {
        $existingMembership = $this->membershipRepository->findByUserAndGroup($dto->userId, $dto->groupId);

        if (in_array($existingMembership->role, [Role::Owner, Role::Administrator])) {
            throw new MemberAlreadyAnAdminOrOwnerException();
        }

        $membership = $this->membershipRepository->update($existingMembership, Role::Administrator);

        event(new MemberPromoted($membership));

        return $membership;
    }

    public function demoteMember(UpdateMemberDto $dto): Membership
    {
        $existingMembership = $this->membershipRepository->findByUserAndGroup($dto->userId, $dto->groupId);

        if ($existingMembership->role === Role::Member) {
            throw new MemberAlreadyAMemberException();
        }

        $membership = $this->membershipRepository->update($existingMembership, Role::Member);

        event(new MemberDemoted($membership));

        return $membership;
    }

    public function leaveGroup(DeleteMemberDto $dto): void
    {
        $membership = $this->membershipRepository->findByUserAndGroup($dto->userId, $dto->groupId);

        if ($membership->role === Role::Owner) {
            $this->handleOwnershipTransfer($membership);
        }

        $groupId = $membership->group_id;
        $userId = $membership->user_id;
        $role = $membership->role->value;

        $this->membershipRepository->delete($membership);

        event(new MemberLeft($groupId, $userId, $role));
    }

    private function handleOwnershipTransfer(Membership $membership): void
    {
        $newOwner = $this->membershipRepository->getNextOwner($membership->group_id);

        if ($newOwner) {
            $membership = $this->membershipRepository->update($newOwner, Role::Administrator);
            event(new OwnerChanged($membership));
        } else {
            event(new GroupEmptied($membership->group_id));
        }
    }
}