<?php

namespace Modules\Group\Dto;

class UpdateGroupDto
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function fromRequest($request): self
    {
        return new self(
            name: $request->name
        );
    }
}
