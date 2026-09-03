<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(
    'ref_no',
    'material_id',
    'category_id',
    'project_id',
    'purchase_id',
    'beg_stock',
    'purchased_quantity',
    'used_quantity',
    'ending_stock',
    'inventory_value',
    'stock_status',
)]
class Inventory extends Model
{
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // public function materialUsages(): HasMany
    // {
    //     return $this->hasMany(MaterialUsage::class);
    // }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function inventoryHistory(): HasMany
    {
        return $this->hasMany(InventoryHistory::class);
    }
}
