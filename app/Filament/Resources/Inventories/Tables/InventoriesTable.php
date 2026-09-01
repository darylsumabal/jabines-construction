<?php

namespace App\Filament\Resources\Inventories\Tables;

use Filament\Actions\BulkActionGroup;
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
                TextColumn::make('used_quantity')
                    ->label('Used'),
                TextColumn::make('ending_stock')
                    ->label('Ending'),
                TextColumn::make('inventory_value')
                    ->label('Value')
                    ->money('PHP'),
                TextColumn::make('stock_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'in_stock' => 'success',
                        'low_stock' => 'warning',
                        'out_of_stock' => 'danger',
                    }),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
