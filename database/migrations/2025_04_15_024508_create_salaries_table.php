<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('month');
            $table->integer('total_working_days')->default(0);
            $table->integer('present_days')->default(0);
            $table->integer('leaves')->default(0);
            $table->integer('absents')->default(0);
            $table->integer('half_days')->default(0);
            $table->decimal('base_salary', 10, 2)->default(0.00);
            $table->decimal('final_salary', 10, 2)->default(0.00);
            $table->enum('status', ['Pending', 'Paid'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
