<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(
    'code',
    'name',
    'category',
    'unit_cost',
)]
class Materials extends Model
{
    use SoftDeletes;

    public function materialUsages(): HasMany
    {
        return $this->hasMany(MaterialUsage::class, 'materials_id');
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class, 'materials_id');
    }
}
