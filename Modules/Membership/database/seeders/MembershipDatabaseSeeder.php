<?php

namespace Modules\Membership\Database\Seeders;

use Illuminate\Database\Seeder;

class MembershipDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(MembershipSeeder::class);
    }
}
