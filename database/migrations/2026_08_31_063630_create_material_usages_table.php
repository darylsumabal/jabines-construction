<?php

use App\Models\Inventory;
use App\Models\Material;
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
        Schema::create('material_usages', function (Blueprint $table) {
            $table->id();
            $table->string('ref_code');
            $table->date('date_used');
            $table->foreignIdFor(Project::class);
            $table->foreignIdFor(Inventory::class);
            $table->integer('quantity_used');
            $table->decimal('total_cost', 15, 2);
            $table->string('remarks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_usages');
    }
};
