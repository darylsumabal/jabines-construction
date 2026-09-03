<?php

use App\Models\Project;
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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no');
            $table->foreignIdFor(Project::class);
            $table->string('type');
            $table->decimal('amount', 15, 2);
            $table->decimal('vat', 15, 2);
            $table->decimal('total', 15, 2);
            $table->decimal('amount_collected', 15, 2);
            $table->decimal('balance', 15, 2);
            $table->string('status');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
