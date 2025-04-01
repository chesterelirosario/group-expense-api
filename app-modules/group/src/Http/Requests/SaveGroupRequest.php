<?php

namespace Modules\Group\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Group\Models\Group;

class SaveGroupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
            ],
        ];
    }

    public function handle(Group $group): Group
    {
        $group->fill([
            'name' => $this->name,
            'code' => $group->code ?? uniqid(),
        ])->save();
        return $group;
    }
}
