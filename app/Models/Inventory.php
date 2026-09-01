<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


#[Fillable(
    'ref_no',
    'material_id',
    'category_id',
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

    // public function purchases(): HasMany
    // {
    //     return $this->hasMany(Purchase::class);
    // }
}
