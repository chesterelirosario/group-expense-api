<?php

namespace Modules\Membership\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Membership\Enums\Role;

class Membership extends Model
{
    use HasUuids;
    use HasFactory;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
