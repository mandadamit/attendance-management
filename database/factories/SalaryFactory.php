<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Employee;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Salary>
 */
class SalaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $employee = Employee::inRandomOrder()->first() ?? Employee::factory()->create();
        $baseSalary = $employee->base_salary;

        $totalDays = 30;
        $present = rand(20, 28);
        $leaves = rand(0, 3);
        $halfDays = rand(0, 2);
        $absents = max(0, $totalDays - $present - $leaves - ($halfDays * 0.5));

        $perDaySalary = $baseSalary / 30;
        $deductions = ($absents * $perDaySalary) + ($halfDays * ($perDaySalary / 2));
        $finalSalary = $baseSalary - $deductions;

        return [
            'employee_id' => Employee::inRandomOrder()->first()->id ?? Employee::factory(),
            'month' => now()->format('Y-m'),
            'total_working_days' => $totalDays,
            'present_days' => $present,
            'leaves' => $leaves,
            'absents' => $absents,
            'half_days' => $halfDays,
            'base_salary' => $baseSalary,
            'final_salary' => $finalSalary,
        ];
    }
}
