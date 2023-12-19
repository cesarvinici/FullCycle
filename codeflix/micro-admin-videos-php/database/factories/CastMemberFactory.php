<?php

namespace Database\Factories;

use Core\Domain\Enum\CastMemberType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CastMemberFactory extends Factory
{
    const TYPES = [1, 2];

    public function definition()
    {
        return [
            "id" => (string) Str::uuid(),
            "name" => $this->faker->name,
            "type" => $this->faker->randomElement(self::TYPES)
        ];
    }
}
