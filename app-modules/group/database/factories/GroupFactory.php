<?php

namespace Modules\Group\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'owner_id' => User::factory(),
        ];
    }
}
