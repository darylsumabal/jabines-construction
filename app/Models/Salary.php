<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(
    'employee_id',
    'monthly_salary',
    'semi_monthly_salary',
    'days_worked',
    'daily_rate',
    'hourly_rate',
    'minute_rate',
    'basic_pay',
    'month',
)]
class Salary extends Model
{
    protected function casts(): array
    {
        return [
            'month' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
