<?php

namespace Modules\Group\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
    ];
}
