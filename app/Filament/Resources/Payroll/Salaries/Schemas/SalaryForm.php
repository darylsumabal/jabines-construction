<?php

namespace App\Filament\Resources\Payroll\Salaries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use PtPlugins\FilamentNumberInput\Fields\NumberInput;

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
                NumberInput::make('monthly_salary')
                    ->required()
                    ->maxLength(255)
                    ->prefix('₱')
                    ->american()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if ($state) {
                            $monthlySalary = (float) $state;
                            $dailyRate = $monthlySalary / 26;
                            $hourlyRate = $dailyRate / 8;
                            $set('semi_monthly_salary', number_format($monthlySalary / 2, 2));
                            $set('daily_rate', number_format($dailyRate, 2));
                            $set('hourly_rate', number_format($hourlyRate, 2));
                            $set('minute_rate', number_format($hourlyRate / 60, 2));
                        } else {
                            $set('semi_monthly_salary', null);
                            $set('daily_rate', null);
                            $set('hourly_rate', null);
                            $set('minute_rate', null);
                        }
                    }),
                NumberInput::make('semi_monthly_salary')
                    ->maxLength(255)
                    ->american()
                    ->decimalPlaces(2)
                    ->readOnly()
                    ->prefix('₱'),
                TextInput::make('days_worked')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        if ($state) {
                            $daysWorked = (float) $state;
                            $dailyRate = $get('daily_rate');
                            $set('basic_pay', round($daysWorked * $dailyRate));
                        } else {
                            $set('basic_pay', null);
                        }
                    })
                    ->maxLength(255),
                NumberInput::make('daily_rate')
                    ->american()
                    ->decimalPlaces(2)
                    ->readOnly()
                    ->prefix('₱'),
                NumberInput::make('hourly_rate')
                    ->american()
                    ->decimalPlaces(2)
                    ->readOnly()
                    ->prefix('₱'),
                TextInput::make('minute_rate')
                    // ->american()
                    // ->decimalPlaces(2)
                    ->readOnly()
                    ->prefix('₱'),
                NumberInput::make('basic_pay')
                    ->american()
                    ->decimalPlaces(2)
                    ->readOnly()
                    ->prefix('₱'),
                DatePicker::make('month')
                    ->required()
                    ->native(false)
                    ->displayFormat('F Y'),
            ]);
    }
}
