<?php

namespace App\Filament\Resources\Purchases\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PurchasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ref_no')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('project.project_name')
                    ->formatStateUsing(
                        fn ($state, $record) => "{$record->project->client} - {$state}"
                    )
                    ->label('Project')
                    ->searchable(),
                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable(),
                TextColumn::make('quantity'),
                TextColumn::make('material.name')
                    ->formatStateUsing(
                        fn ($state, $record) => "{$record->material->ref_code} - {$state}"
                    ),
                TextColumn::make('material.unit_cost')
                    ->label('Unit Cost')
                    ->money('PHP'),
                TextColumn::make('total_amount')
                    ->money('PHP'),
                TextColumn::make('date_purchased')
                    ->label('Date')
                    ->date(),

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
