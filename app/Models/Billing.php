<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(
    'reference_no',
    'project_id',
    'billing_type',
    'billing_amount',
    'vat',
    'total_billing',
    'amount_collected',
    'balance',
    'status',
)]
class Billing extends Model
{
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
