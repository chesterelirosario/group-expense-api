<?php

namespace Modules\Group\Dto;

class CreateGroupDto
{
    public string $name;
    public string $ownerId;

    public function __construct(string $name, string $ownerId)
    {
        $this->name = $name;
        $this->ownerId = $ownerId;
    }

    public static function fromRequest($request): self
    {
        return new self(
            name: $request->name,
            ownerId: $request->user()->id
        );
    }
}
