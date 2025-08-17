<?php

namespace Database\Factories;

use App\Models\BasePlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BasePlan>
 */
class BasePlanFactory extends Factory
{

    protected $model = BasePlan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'external_id' => $this->faker->numberBetween(1, 100),
            'title' => $this->faker->title(),
            'uuid' => $this->faker->uuid(),
        ];
    }
}
