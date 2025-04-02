<?php

namespace Modules\Group\Dto;

use Illuminate\Support\Facades\Request;

class UpdateGroupDto
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->get('name'),
        );
    }
}
