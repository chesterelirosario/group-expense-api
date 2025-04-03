<?php

namespace Modules\Membership\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Group\Models\Group;
use Modules\Membership\Enums\Role;
use Modules\Membership\Models\Membership;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        $groups = Group::all();

        foreach ($groups as $group) {
            Membership::factory()
                ->count(rand(1, 5))
                ->create([
                    'group_id' => $group->id,
                    'role' => Role::Member,
                ]);
        }
    }
}
