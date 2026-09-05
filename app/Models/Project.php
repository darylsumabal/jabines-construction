<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(
    'project_code',
    'project_name',
    'client',
    'budget',
    'status',
)]
class Project extends Model
{
    public function billings(): HasMany
    {
        return $this->hasMany(Billing::class);
    }

    public function revenues(): HasMany
    {
        return $this->hasMany(Revenue::class);
    }

    public function accountsReceivables(): HasMany
    {
        return $this->hasMany(AccountsReceivable::class);
    }

    public function cashReceipts(): HasMany
    {
        return $this->hasMany(CashReceipts::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function materialUsages(): HasMany
    {
        return $this->hasMany(MaterialUsage::class);
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function projectCostings(): HasMany
    {
        return $this->hasMany(ProjectCosting::class);
    }
}
