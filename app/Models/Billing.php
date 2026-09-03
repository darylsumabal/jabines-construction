<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(
    'ref_no',
    'project_id',
    'type',
    'amount',
    'vat',
    'total',
    'amount_collected',
    'balance',
    'status',
    'date'
)]
class Billing extends Model
{
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
