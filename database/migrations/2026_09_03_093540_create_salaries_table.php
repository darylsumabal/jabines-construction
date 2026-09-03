<?php

use App\Models\Employee;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employee::class);
            $table->string('monthly_salary');
            $table->string('semi_monthly_salary');
            $table->string('days_worked');
            $table->string('daily_rate');
            $table->string('hourly_rate');
            $table->string('minute_rate');
            $table->string('basic_pay');
            $table->date('month');
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
