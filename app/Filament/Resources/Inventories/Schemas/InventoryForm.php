<?php

namespace App\Filament\Resources\Inventories\Schemas;

use App\Models\Material;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use PtPlugins\FilamentNumberInput\Fields\NumberInput;

class InventoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('ref_no')
                    ->required()
                    ->maxLength(255),
                Select::make('material_id')
                    ->relationship('material', 'ref_code')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->optionsLimit(1000)
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        if ($state) {
                            $material = Material::find($state);
                            $unitCost = $material?->unit_cost ?? 0;
                            $quantity = (float) ($get('ending_stock') ?? 0);
                            $set('material_unit_cost', $unitCost);
                            $set('material_name', $material?->name);
                            $set('inventory_value', number_format($unitCost * $quantity, 2, '.', ''));
                        } else {
                            $set('material_name', null);
                            $set('material_unit_cost', null);
                        }
                    }),
                TextInput::make('material_name')
                    ->label('Material Name')
                    ->readOnly()
                    ->dehydrated(false),
                // TextInput::make('material_unit_cost')
                //     ->numeric()
                //     ->prefix('₱')
                //     ->readOnly()
                //     ->dehydrated(false),
                NumberInput::make('material_unit_cost')->readOnly()
                    ->prefix('₱')->american()->dehydrated(false)->numeric(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('beg_stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('purchased_quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('used_quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('ending_stock')
                    ->required()
                    ->numeric()
                    ->live(debounce: 500)
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $unitCost = (float) ($get('material_unit_cost') ?? 0);
                        $quantity = (float) ($state ?? 0);

                        $set('inventory_value', number_format($unitCost * $quantity, 2, '.', ''));
                    }),
                NumberInput::make('inventory_value')->readOnly()
                    ->prefix('₱')->american(),
                Select::make('stock_status')
                    ->options([
                        'in_stock' => 'In Stock',
                        'low_stock' => 'Low Stock',
                        'out_of_stock' => 'Out of Stock',
                    ])
                    ->required()
                    ->default('in_stock'),
            ]);
    }
}
