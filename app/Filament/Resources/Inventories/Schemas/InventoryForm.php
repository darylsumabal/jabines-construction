<?php

namespace App\Filament\Resources\Inventories\Schemas;

use App\Models\Inventory;
use App\Models\Material;
use App\Models\Project;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
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
                    ->suffixAction(
                        Action::make('generateRefCode')
                            ->icon('heroicon-m-arrow-path')
                            ->action(function (Set $set) {
                                $lastCategory = Inventory::orderBy('id', 'desc')->first();

                                if ($lastCategory && preg_match('/^INV-(\d+)$/', $lastCategory->ref_no, $matches)) {
                                    $nextNumber = intval($matches[1]) + 1;
                                } else {
                                    $nextNumber = 1;
                                }

                                $set('ref_no', 'INV-'.str_pad($nextNumber, 3, '0', STR_PAD_LEFT));
                            })
                    )
                    ->maxLength(255),

                Select::make('project_id')
                    ->relationship('project', 'project_name')
                    ->required()
                    ->getOptionLabelFromRecordUsing(
                        fn (Project $record) => "{$record->project_code} {$record->client},  {$record->project_name}"
                    )
                    ->searchable()
                    ->live()
                    ->preload(),

                Grid::make(4)->schema([

                    Select::make('category_id')
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
                        ->relationship('material', 'ref_code')
                        ->required()
                        ->disabled(fn (Get $get) => ! $get('category_id'))
                        ->searchable()
                        ->options(function (Get $get) {
                            $categoryId = $get('category_id');

                            if (! $categoryId) {
                                return [];
                            }

                            return Material::where('category_id', $categoryId)
                                ->get()
                                ->mapWithKeys(fn (Material $material) => [
                                    $material->id => "{$material->ref_code} {$material->name}",
                                ]);
                        })
                        ->preload()
                        ->optionsLimit(1000)
                        ->live()
                        ->afterStateHydrated(function ($state, Set $set, Get $get) {
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
                        })
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

                    NumberInput::make('material_unit_cost')->readOnly()
                        ->prefix('₱')->american()->dehydrated(false)->numeric(),
                ])->columnSpanFull(),

                Grid::make(4)->schema([

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
                        ->default(0)
                        ->live(debounce: 500)
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            $unitCost = (float) ($get('material_unit_cost') ?? 0);
                            $quantity = (float) ($state ?? 0);

                            $set('inventory_value', number_format($unitCost * $quantity, 2, '.', ''));
                        }),
                ])->columnSpanFull(),

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
