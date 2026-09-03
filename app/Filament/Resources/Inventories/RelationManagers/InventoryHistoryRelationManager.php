<?php

namespace App\Filament\Resources\Inventories\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InventoryHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'inventoryHistory';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                // TextColumn::make('project.project_name')
                //     ->label('Project')
                //     ->searchable(),
                TextColumn::make('material.name')
                    ->label('Material')
                    ->formatStateUsing(
                        fn ($state, $record) => "{$record->material->ref_code} - {$state}"
                    )
                    ->searchable(),
                TextColumn::make('material.unit_cost')
                    ->searchable()
                    ->money('PHP'),
                TextColumn::make('quantity')
                    ->searchable(),

                TextColumn::make('total_amount')
                    ->searchable()
                    ->money('PHP'),
                TextColumn::make('total')
                    ->searchable()
                    ->money('PHP'),
                TextColumn::make('date_purchased')
                    ->label('Date')
                    ->date(),
                TextColumn::make('type'),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
