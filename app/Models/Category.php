<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(
    'ref_code',
    'name',
    'description',
)]
class Category extends Model
{
    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }
}
