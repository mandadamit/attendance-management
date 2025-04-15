<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Branch;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'branch_id' => Branch::inRandomOrder()->first()->id ?? Branch::factory(),
            'name' => $this->faker->name,
            'designation' => $this->faker->jobTitle,
            'join_date' => $this->faker->date('Y-m-d'),
            'base_salary' => $this->faker->numberBetween(15000, 50000),
        ];
    }
}
