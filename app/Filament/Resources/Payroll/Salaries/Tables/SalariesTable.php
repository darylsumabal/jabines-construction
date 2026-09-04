<?php

namespace App\Filament\Resources\Payroll\Salaries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalariesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.first_name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('month')
                    ->date('F Y')
                    ->sortable(),
                TextColumn::make('monthly_salary')
                    ->label('Monthly')
                    ->money('PHP')
                    ->summarize([
                        Sum::make()->money('PHP')->hiddenLabel(),
                    ])
                    ->searchable(),
                TextColumn::make('semi_monthly_salary')
                    ->label('Semi-Monthly')
                    ->money('PHP')
                    ->summarize([
                        Sum::make()->money('PHP')->hiddenLabel(),
                    ])
                    ->searchable(),
                TextColumn::make('days_worked')
                    ->label('Days')
                    ->summarize([
                        Sum::make()->hiddenLabel(),
                    ])
                    ->searchable(),
                TextColumn::make('daily_rate')
                    ->label('Daily Rate')
                    ->money('PHP')
                    ->summarize([
                        Sum::make()->money('PHP')->hiddenLabel(),
                    ])
                    ->searchable(),
                TextColumn::make('hourly_rate')
                    ->label('Hourly Rate')
                    ->money('PHP')
                    ->summarize([
                        Sum::make()->money('PHP')->hiddenLabel(),
                    ])
                    ->searchable(),
                TextColumn::make('minute_rate')
                    ->label('Minute Rate')
                    ->money('PHP')
                    ->summarize([
                        Sum::make()->money('PHP')->hiddenLabel(),
                    ])
                    ->searchable(),
                TextColumn::make('basic_pay')
                    ->label('Basic Pay')
                    ->money('PHP')
                    ->summarize([
                        Sum::make()->money('PHP')->hiddenLabel(),
                    ])
                    ->searchable(),
            ])
            ->filters([])
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
