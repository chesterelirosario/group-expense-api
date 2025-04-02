<?php

namespace Modules\Membership\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListMembersRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'group_id' => [
                'required',
                'string',
            ],
        ];
    }
}
