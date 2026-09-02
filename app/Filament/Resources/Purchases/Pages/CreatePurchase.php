<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use App\Models\Inventory;
use App\Models\InventoryHistory;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function handleRecordCreation(array $data): Model
    {

        $purchase = static::getModel()::create($data);

        $existingInventory = Inventory::where('material_id', $data['material_id'])
            ->where('project_id', $data['project_id'])
            ->where('category_id', $data['category_id'])
            ->first();

        $lastCategory = Inventory::orderBy('id', 'desc')->first();

        if ($lastCategory && preg_match('/^INV-(\d+)$/', $lastCategory->ref_no, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        if ($existingInventory) {
            $existingInventory->update([
                'purchased_quantity' => $existingInventory->purchased_quantity + $data['quantity'],
                'inventory_value' => $existingInventory->inventory_value + $data['total_amount'],
                'ending_stock' => $existingInventory->ending_stock + $data['quantity'],
            ]);
            $inventory = $existingInventory;
        } else {
            $inventory = Inventory::create([
                'ref_no' => 'INV-'.str_pad($nextNumber, 3, '0', STR_PAD_LEFT),
                'material_id' => $data['material_id'],
                'category_id' => $data['category_id'],
                'project_id' => $data['project_id'],
                'purchase_id' => $purchase->id,
                'beg_stock' => 0,
                'purchased_quantity' => $data['quantity'],
                'used_quantity' => 0,
                'ending_stock' => $data['quantity'],
                'inventory_value' => $data['total_amount'],
                'stock_status' => 'in_stock',
                'date_purchased' => $data['date_purchased'],
            ]);
        }

        InventoryHistory::create([
            'purchase_id' => $purchase->id,
            'inventory_id' => $inventory->id,
            'project_id' => $data['project_id'],
            'supplier_id' => $data['supplier_id'],
            'material_id' => $data['material_id'],
            'category_id' => $data['category_id'],
            'quantity' => $data['quantity'],
            'total_amount' => $data['total_amount'],
            'total' => $data['total'],
            'date_purchased' => $data['date_purchased'],
            'type' => 'purchase',
        ]);

        return $purchase;
    }
}
