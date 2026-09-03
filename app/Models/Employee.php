<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(
    'position_id',
    'department_id',
    'first_name',
    'last_name',
    'email',
    'status',
)]
class Employee extends Model
{
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }
}
