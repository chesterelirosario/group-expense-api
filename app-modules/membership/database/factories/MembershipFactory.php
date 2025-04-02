<?php

namespace Modules\Membership\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Membership\Enums\Role;

class MembershipFactory extends Factory
{
    public function definition(): array
    {
        return [
            'group_id' => $this->faker->uuid(),
            'user_id' => User::factory(),
            'role' => array_rand(Role::cases()),
        ];
    }
}
