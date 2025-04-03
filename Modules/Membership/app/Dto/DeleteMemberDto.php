<?php

namespace Modules\Membership\Dto;

use Illuminate\Http\Request;

class DeleteMemberDto
{
    public string $groupId;
    public string $userId;

    public function __construct(string $groupId, string $userId)
    {
        $this->groupId = $groupId;
        $this->userId = $userId;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            groupId: $request->get('group_id'),
            userId: $request->user()->id,
        );
    }
}