<?php

namespace Modules\Membership\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Membership\Enums\Role;

class Membership extends Model
{
    protected $fillable = [
        'group_id',
        'user_id',
        'role',
    ];

    protected function casts(): array
    {
        return [
            'role' => Role::class,
        ];
    }
}
