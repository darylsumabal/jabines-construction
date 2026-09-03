<?php

namespace App\Filament\Resources\Payroll\Salaries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SalaryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->relationship('employee', 'first_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('monthly_salary')
                    ->required()
                    ->maxLength(255)
                    ->prefix('₱'),
                TextInput::make('semi_monthly_salary')
                    ->required()
                    ->maxLength(255)
                    ->prefix('₱'),
                TextInput::make('days_worked')
                    ->required()
                    ->maxLength(255),
                TextInput::make('daily_rate')
                    ->required()
                    ->maxLength(255)
                    ->prefix('₱'),
                TextInput::make('hourly_rate')
                    ->required()
                    ->maxLength(255)
                    ->prefix('₱'),
                TextInput::make('minute_rate')
                    ->required()
                    ->maxLength(255)
                    ->prefix('₱'),
                TextInput::make('basic_pay')
                    ->required()
                    ->maxLength(255)
                    ->prefix('₱'),
                DatePicker::make('month')
                    ->required()
                    ->displayFormat('F Y'),
            ]);
    }
}
