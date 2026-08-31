<?php

namespace App\Filament\Resources\Purchases\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                    ->relationship('project', 'project_name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('supplier_id')
                    ->relationship('supplier', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('inventory_id')
                    ->relationship('inventory', 'ref_no')
                    ->required()
                    ->searchable()
                    ->preload(),
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
