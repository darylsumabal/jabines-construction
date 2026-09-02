<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(
    'ref_code',
    'date_used',
    'project_id',
    'inventory_id',
    'total_cost',
    'quantity_used',
    'remarks',
)]
class MaterialUsage extends Model
{
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // public function material(): BelongsTo
    // {
    //     return $this->belongsTo(Material::class);
    // }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }
}
