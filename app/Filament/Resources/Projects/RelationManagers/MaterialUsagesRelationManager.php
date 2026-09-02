<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Models\Inventory;
use App\Models\Material;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use PtPlugins\FilamentNumberInput\Fields\NumberInput;

class MaterialUsagesRelationManager extends RelationManager
{
    protected static string $relationship = 'materialUsages';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('ref_code')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('date_used')->native(false)
                    ->required(),
                Hidden::make('project_id')
                    ->default(fn() => $this->getOwnerRecord()->id),
                Select::make('inventory_id')
                    ->relationship('inventory', 'ref_code')
                    ->getOptionLabelFromRecordUsing(fn(Inventory $record) => "{$record->material->ref_code} {$record->material->name} ")
                    ->required()
                    ->searchable(['ref_code', 'name'])
                    ->preload()
                    ->optionsLimit(1000)
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        if ($state) {
                            $inventory = Inventory::find($state);
                            $unitCost = $inventory->material?->unit_cost ?? 0;
                            $quantity = (float) ($get('quantity_used') ?? 0);

                            $set('material_name', $inventory?->material?->name);
                            $set('material_unit', $inventory?->material?->unit);
                            $set('material_unit_cost', $unitCost);

                            // Calculate total cost
                            $set('total_cost', number_format($unitCost * $quantity, 2, '.', ''));
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

                NumberInput::make('material_unit_cost')
                    ->label('Material Unit Cost')
                    ->numeric()
                    ->readOnly()
                    ->dehydrated(false)
                    ->american()
                    ->prefix('₱'),

                TextInput::make('quantity_used')
                    ->required()
                    ->numeric()
                    ->live(debounce: 500) // Debounce avoids instant recalculation on every keystroke
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $unitCost = (float) ($get('material_unit_cost') ?? 0);
                        $quantity = (float) ($state ?? 0);

                        $set('total_cost', number_format($unitCost * $quantity, 2, '.', ''));
                    }),

                NumberInput::make('total_cost')->readOnly()
                    ->prefix('₱')->american(),
                TextInput::make('remarks')
                    ->required()
                    ->maxLength(255)->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ref_code')
            ->columns([
                TextColumn::make('ref_code')
                    ->searchable(),
                TextColumn::make('material.name')
                    ->label('Material Name')
                    ->searchable(),
                TextColumn::make('material.unit')
                    ->label('Unit')
                    ->searchable(),
                TextColumn::make('material.unit_cost')
                    ->label('Unit Cost')
                    ->money('PHP')
                    ->searchable(),
                TextColumn::make('quantity_used')
                    ->searchable(),
                TextColumn::make('total_cost')
                    ->money('PHP')
                    ->searchable(),
                TextColumn::make('remarks')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
