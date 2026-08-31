<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(
    'name',
    'address',
    'phone',
)]
class Supplier extends Model
{
    use SoftDeletes;

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}
