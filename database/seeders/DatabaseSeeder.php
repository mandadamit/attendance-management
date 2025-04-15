<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Branch::factory(3)->create()->each(function ($branch) {
            $employees = \App\Models\Employee::factory(10)->create([
                'branch_id' => $branch->id,
            ]);
    
            foreach ($employees as $employee) {
                \App\Models\Attendance::factory(20)->create([
                    'employee_id' => $employee->id,
                ]);
    
                \App\Models\Salary::factory()->create([
                    'employee_id' => $employee->id,
                    'month' => now()->format('Y-m'),
                ]);
            }
        });
    }
}
