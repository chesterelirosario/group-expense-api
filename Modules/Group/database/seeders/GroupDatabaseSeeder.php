<?php

namespace Modules\Group\Database\Seeders;

use Illuminate\Database\Seeder;

class GroupDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(GroupSeeder::class);
    }
}
