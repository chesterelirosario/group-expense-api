<?php

namespace Modules\Group\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Group\Events\GroupCreated;
use Modules\Group\Models\Group;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = Group::factory()->count(10)->create();

        foreach ($groups as $group) {
            event(new GroupCreated($group->id, $group->owner_id));
        }
    }
}
