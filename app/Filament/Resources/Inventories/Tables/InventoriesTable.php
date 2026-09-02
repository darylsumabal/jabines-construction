<?php

namespace App\Filament\Resources\Inventories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InventoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ref_no')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('project.project_name')
                    ->label('Project')
                    ->formatStateUsing(
                        fn ($state, $record) => "{$record->project->client} - {$state}"
                    )
                    ->searchable(),
                TextColumn::make('material.name')
                    ->label('Material')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable(),
                TextColumn::make('beg_stock')
                    ->label('Beg Stock'),
                TextColumn::make('purchased_quantity')
                    ->label('Purchased'),
                TextColumn::make('used_quantity'),
                TextColumn::make('ending_stock'),
                TextColumn::make('inventory_value')
                    ->money('PHP'),
                TextColumn::make('stock_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'in_stock' => 'success',
                        'low_stock' => 'warning',
                        'out_of_stock' => 'danger',
                    }),
            ])
            ->filters([])
            ->recordActions([
                DeleteAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
