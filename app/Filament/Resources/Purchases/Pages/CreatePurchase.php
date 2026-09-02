<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use App\Models\Inventory;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;


    protected function handleRecordCreation(array $data): Model
    {

        $purchase =  static::getModel()::create($data);

        Inventory::create([
            'ref_no' => $data['ref_no'],
            'material_id' => $data['material_id'],
            'category_id' => $data['category_id'],
            'project_id' => $data['project_id'],
            'purchase_id' => $purchase->id,
            'beg_stock' => 0,
            'purchased_quantity' => $data['quantity'],
            'used_quantity' => 0,
            'ending_stock' => 0,
            'inventory_value' => $data['total_amount'],
            'stock_status' => 'in_stock',
        ]);

        return $purchase;
    }
}
