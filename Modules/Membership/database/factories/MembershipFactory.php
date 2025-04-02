<?php

namespace Modules\Membership\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Membership\Enums\Role;
use Modules\Membership\Models\Membership;

class MembershipFactory extends Factory
{
    protected $model = Membership::class;

    public function definition(): array
    {
        return [
            'group_id' => $this->faker->uuid(),
            'user_id' => User::factory(),
            'role' => $this->faker->randomElement(Role::cases()),
        ];
    }
}
