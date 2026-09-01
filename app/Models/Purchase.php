<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(
    'ref_no',
    'project_id',
    'supplier_id',
    'material_id',
    'quantity',
    'total_amount',
    'total',
)]
class Purchase extends Model
{
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    // public function inventory(): BelongsTo
    // {
    //     return $this->belongsTo(Inventory::class);
    // }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
