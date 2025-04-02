<?php

namespace Modules\Membership\Repositories;

use Modules\Membership\Dto\CreateMemberDto;
use Modules\Membership\Enums\Role;
use Modules\Membership\Models\Membership;

class MembershipRepository
{
    public function listMembers(string $groupId): array
    {
        return Membership::where('group_id', $groupId)
            ->with('user')
            ->get()
            ->toArray();
    }

    public function findByUserAndGroup(string $userId, string $groupId): ?Membership
    {
        return Membership::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->first();
    }

    public function getNextOwner(string $groupId): ?Membership
    {
        $admin = Membership::where('group_id', $groupId)
            ->where('role', Role::Administrator)
            ->orderBy('created_at')
            ->first();

        if ($admin) {
            return $admin;
        }

        return Membership::where('group_id', $groupId)
            ->where('role', Role::Member)
            ->orderBy('created_at')
            ->first();
    }

    public function create(CreateMemberDto $dto): Membership
    {
        return Membership::create([
            'group_id' => $dto->groupId,
            'user_id' => $dto->userId,
            'role' => $dto->role,
        ]);
    }

    public function update(Membership $membership, Role $role): Membership
    {
        $membership->update(['role' => $role]);

        return $membership;
    }

    public function delete(Membership $membership): void
    {
        $membership->delete();
    }
}
