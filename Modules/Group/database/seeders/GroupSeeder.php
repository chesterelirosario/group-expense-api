<?php

namespace Modules\Group\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Group\Models\Group;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        Group::factory()->count(10)->create();
    }
}
