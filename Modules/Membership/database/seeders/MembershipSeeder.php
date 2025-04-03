<?php

namespace Modules\Membership\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Membership\Models\Membership;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        Membership::factory()->count(10)->create();
    }
}
