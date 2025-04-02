<?php

namespace Modules\Group\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Group\Database\Factories\GroupFactory;

class Group extends Model
{
    use HasUuids;
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_id',
    ];

    protected static function newFactory()
    {
        return GroupFactory::new();
    }
}
