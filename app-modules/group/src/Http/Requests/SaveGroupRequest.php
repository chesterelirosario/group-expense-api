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
}
