<?php

namespace Modules\Membership\Dto;

use Illuminate\Http\Request;
use Modules\Membership\Enums\Role;

class CreateMemberDto
{
    public string $groupId;
    public string $userId;
    public Role $role;

    public function __construct(string $groupId, string $userId, Role $role)
    {
        $this->groupId = $groupId;
        $this->userId = $userId;
        $this->role = $role;
    }

    public static function fromRequest(Request $request, Role $role = Role::Member): self
    {
        return new self(
            groupId: $request->get('group_id'),
            userId: $request->user()->id,
            role: $role,
        );
    }
}