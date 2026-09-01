<?php

use App\Models\Category;
use App\Models\Material;
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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no');
            $table->foreignIdFor(Material::class);
            $table->foreignIdFor(Category::class);
            $table->integer('beg_stock');
            $table->integer('purchased_quantity');
            $table->integer('used_quantity');
            $table->integer('ending_stock');
            $table->integer('inventory_value');
            $table->string('stock_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
