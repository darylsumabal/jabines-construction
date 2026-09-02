<?php

namespace App\Filament\Resources\Purchases\Schemas;

use App\Models\Category;
use App\Models\Material;
use App\Models\Project;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use PtPlugins\FilamentNumberInput\Fields\NumberInput;

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
                    ->getOptionLabelFromRecordUsing(
                        fn(Project $record) => "{$record->project_code} {$record->client}  {$record->project_name}"
                    )
                    ->required()
                    ->searchable()
                    ->preload(),


                Select::make('supplier_id')
                    ->relationship('supplier', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('material_id', null);
                        $set('material_name', null);
                        $set('unit', null);
                        $set('unit_cost', null);
                        $set('total_amount', null);
                        $set('total', null);
                    })
                    ->preload(),

                Select::make('material_id')
                    ->label('Material Code')
                    ->disabled(fn(Get $get) => ! $get('category_id'))
                    ->relationship('material', 'ref_code')
                    ->options(function (Get $get) {
                        $categoryId = $get('category_id');

                        if (! $categoryId) {
                            return [];
                        }

                        return Material::where('category_id', $categoryId)
                            ->get()
                            ->mapWithKeys(fn(Material $material) => [
                                $material->id => "{$material->ref_code} {$material->name}",
                            ]);
                    })
                    ->required()
                    ->searchable(['ref_code', 'name'])
                    ->preload()
                    ->optionsLimit(1000)
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        if ($state) {
                            $material = Material::find($state);
                            $unitCost = (float) ($material?->unit_cost ?? 0);
                            $quantity = (float) ($get('quantity') ?? 0);

                            // 1. Calculate total_amount (quantity * unitCost)
                            $totalAmount = $quantity * $unitCost;

                            // 2. Calculate total (total_amount * unit_cost)
                            $total = $totalAmount * $unitCost;

                            $set('material_name', $material?->name);
                            $set('unit', $material?->unit);
                            $set('unit_cost', $unitCost);
                            $set('total_amount', $totalAmount);
                            $set('total', $total);
                        } else {
                            $set('material_name', null);
                            $set('unit', null);
                            $set('unit_cost', null);
                            $set('total_amount', null);
                            $set('total', null);
                        }
                    }),

                TextInput::make('material_name')
                    ->label('Material Name')
                    ->readOnly()
                    ->dehydrated(false),

                TextInput::make('unit')
                    ->label('Material Unit')
                    ->dehydrated(false)
                    ->readOnly(),

                NumberInput::make('unit_cost')
                    ->label('Material Unit Cost')
                    ->dehydrated(false)
                    ->readOnly()
                    ->prefix('₱')->american(),

                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->live(debounce: 500)
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $unitCost = (float) ($get('unit_cost') ?? 0);
                        $quantity = (float) ($state ?? 0);

                        // 1. Calculate total_amount
                        $totalAmount = $quantity * $unitCost;

                        // 2. Calculate total as (total_amount * unit_cost)
                        $total = $totalAmount * $unitCost;

                        $set('total_amount', $totalAmount);
                        $set('total', $total);
                    }),

                NumberInput::make('total_amount')
                    ->readOnly()
                    ->prefix('₱')->american(),

                NumberInput::make('total')
                    ->required()
                    ->readOnly()
                    ->prefix('₱')->american(),
            ]);
    }
}
