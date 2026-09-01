<?php

namespace App\Filament\Resources\Inventories\Schemas;


use App\Models\Material;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Components\Utilities\Set as UtilitiesSet;
use Filament\Schemas\Schema;

class InventoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('ref_no')
                    ->required()
                    ->maxLength(255),
                Select::make('materials_id')
                    ->relationship('material', 'ref_code')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->optionsLimit(1000)
                    ->live()
                    ->afterStateUpdated(function ($state, UtilitiesSet $set) {
                        logger($state);
                        if ($state) {
                            $material = Material::find($state);
                            $set('material_name', $material?->name);
                        } else {
                            $set('material_name', null);
                        }
                    }),
                TextInput::make('material_name')
                    ->label('Material Name')
                    ->readOnly()
                    ->dehydrated(false),
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
                    ->default(0),
                TextInput::make('inventory_value')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('₱'),
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
