<?php

namespace Database\Factories;

use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Zone>
 */
class ZoneFactory extends Factory
{

    protected $model = Zone::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plan_id' => $this->faker->randomNumber(),
            'external_id' => $this->faker->randomNumber(),
            'external_plan_id' => $this->faker->randomNumber(),
            'name' => $this->faker->name(),
            'price' => $this->faker->randomNumber(),
        ];
    }
}
