<?php

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Material;
use App\Models\Project;
use App\Models\Purchase;
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
        Schema::create('inventory_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Purchase::class);
            $table->foreignIdFor(Project::class);
            $table->foreignIdFor(Supplier::class);
            $table->foreignIdFor(Inventory::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Material::class);
            $table->foreignIdFor(Category::class);
            $table->integer('quantity');
            $table->decimal('total_amount', 15, 2);
            $table->decimal('total', 15, 2);
            $table->date('date_purchased');
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_histories');
    }
};
