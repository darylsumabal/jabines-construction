<?php

use App\Models\Inventory;
use App\Models\Material;
use App\Models\Project;
use App\Models\Supplier;
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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no');
            $table->foreignIdFor(Project::class);
            $table->foreignIdFor(Supplier::class);
            // $table->foreignIdFor(Inventory::class);
            $table->foreignIdFor(Material::class);
            $table->integer('quantity');
            // $table->integer('unit');
            // $table->decimal('unit_cost', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->decimal('total', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
