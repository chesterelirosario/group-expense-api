<?php

namespace Modules\Group\Dto;

use Illuminate\Http\Request;

class UpdateGroupDto
{
    public ?string $name;
    public ?string $ownerId;

    public function __construct(?string $name, ?string $ownerId)
    {
        $this->name = $name;
        $this->ownerId = $ownerId;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->get('name'),
            ownerId: $request->get('user_id'),
        );
    }
}
