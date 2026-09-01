<?php

namespace App\Filament\Resources\Purchases\Schemas;

use App\Models\Material;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('ref_no')
                    ->required()
                    ->maxLength(255),
                Select::make('project_id')
                    ->relationship('project', 'project_code')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('supplier_id')
                    ->relationship('supplier', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                // Select::make('inventory_id')
                //     ->relationship('inventory', 'ref_no')
                //     ->required()
                //     ->searchable()
                //     ->preload(),
                Select::make('material_id')
                    ->label('Material Code')
                    ->relationship('material', 'ref_code')
                    ->getOptionLabelFromRecordUsing(fn(Material $record) => "{$record->ref_code} {$record->name}")
                    ->required()
                    ->searchable(['ref_code', 'name'])
                    ->preload()
                    ->optionsLimit(1000)
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        if ($state) {
                            $material = Material::find($state);
                            $unitCost = $material?->unit_cost ?? 0;
                            $quantity = (float) ($get('quantity_used') ?? 0);

                            $set('material_name', $material?->name);
                            $set('material_unit', $material?->unit);
                            $set('material_unit_cost', $unitCost);

                            // // Calculate total cost
                            // $set('total_cost', number_format($unitCost * $quantity, 2, '.', ''));
                        } else {
                            $set('material_name', null);
                            $set('material_unit', null);
                            $set('material_unit_cost', null);
                            $set('total_cost', null);
                        }
                    }),

                TextInput::make('material_name')
                    ->label('Material Name')
                    ->readOnly()
                    ->dehydrated(false),

                TextInput::make('material_unit')
                    ->label('Material Unit')
                    ->readOnly()
                    ->dehydrated(false),

                TextInput::make('material_unit_cost')
                    ->label('Material Unit Cost')
                    ->numeric()
                    ->readOnly()
                    ->dehydrated(false),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('unit')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('unit_cost')
                    ->required()
                    ->numeric()
                    ->prefix('₱'),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->prefix('₱'),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->prefix('₱'),
            ]);
    }
}
