<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(
    'purchase_id',
    'inventory_id',
    'project_id',
    'material_id',
    'supplier_id',
    'category_id',
    'quantity',
    'total_amount',
    'total',
    'date_purchased',
    'type',
)]
class InventoryHistory extends Model
{
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
