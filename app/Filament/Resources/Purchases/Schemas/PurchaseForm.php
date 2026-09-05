<?php

namespace App\Filament\Resources\Purchases\Schemas;

use App\Models\Material;
use App\Models\Project;
use App\Models\Purchase;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
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
                Section::make('')->schema([


                    Grid::make(3)->schema([
                        TextInput::make('ref_no')
                            ->required()
                            ->suffixAction(
                                Action::make('generateRefCode')
                                    ->icon('heroicon-m-arrow-path')
                                    ->action(function (Set $set) {
                                        $lastCategory = Purchase::orderBy('id', 'desc')->first();

                                        if ($lastCategory && preg_match('/^PUR-(\d+)$/', $lastCategory->ref_no, $matches)) {
                                            $nextNumber = intval($matches[1]) + 1;
                                        } else {
                                            $nextNumber = 1;
                                        }

                                        $set('ref_no', 'PUR-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT));
                                    })
                            )
                            ->maxLength(255),

                        Select::make('project_id')
                            ->relationship('project', 'project_name')
                            ->getOptionLabelFromRecordUsing(
                                fn(Project $record) => "{$record->project_code} {$record->client}  {$record->project_name}"
                            )
                            ->required()
                            ->searchable()
                            ->preload(),

                        DatePicker::make('date_purchased')
                            ->required()
                            ->native(false),

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
                            ->afterStateHydrated(function ($state, Set $set) {
                                if (! $state) {
                                    return;
                                }

                                $material = Material::find($state);

                                if (! $material) {
                                    return;
                                }

                                $set('material_name', $material->name);
                                $set('unit', $material->unit);
                                $set('unit_cost', $material->unit_cost);
                            })
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

                        Select::make('payment_method')
                            ->required()
                            ->options([
                                'cash' => 'Cash'
                            ]),


                    ])->columnSpanFull(),

                    // Section::make('')->schema([
                    //     TextEntry::make('material_name')
                    //         ->label('Material Name'),

                    //     TextEntry::make('unit')
                    //         ->label('Material Unit'),

                    //     TextEntry::make('unit_cost')
                    //         ->label('Material Unit Cost'),

                    //     Hidden::make('total_amount')
                    //         ->required(),

                    //     TextEntry::make('total_amount_display')
                    //         ->label('Total Amount')
                    //         ->state(fn($get) => '₱' . number_format((float) ($get('total_amount') ?? 0), 2)),

                    //     Hidden::make('total')
                    //         ->required(),

                    //     TextEntry::make('total_display')
                    //         ->label('Total Amount')
                    //         ->state(fn($get) => '₱' . number_format((float) ($get('total') ?? 0), 2))

                    // ])
                    Section::make('')->schema([
                        Hidden::make('material_name')->dehydrated(false),
                        TextEntry::make('material_name_display')
                            ->label('Material Name')
                            ->state(fn(Get $get) => $get('material_name') ?? 'no material selected'),

                        Hidden::make('unit')->dehydrated(false),
                        TextEntry::make('unit_display')
                            ->label('Material Unit')
                            ->state(fn(Get $get) => $get('unit') ?? 'no material selected'),

                        Hidden::make('unit_cost')->dehydrated(false),
                        TextEntry::make('unit_cost_display')
                            ->label('Material Unit Cost')
                            ->state(fn(Get $get) => $get('unit_cost') ?? 'no material selected'),

                        Hidden::make('total_amount')->required(),
                        TextEntry::make('total_amount_display')
                            ->label('Total Amount')
                            ->state(fn(Get $get) => '₱' . number_format((float) ($get('total_amount') ?? 0), 2)),

                        Hidden::make('total')->required(),
                        TextEntry::make('total_display')
                            ->label('Total Amount')
                            ->state(fn(Get $get) => '₱' . number_format((float) ($get('total') ?? 0), 2)),
                    ])
                ])->columnSpanFull()
            ]);
    }
}
