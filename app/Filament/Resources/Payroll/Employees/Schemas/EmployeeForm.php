<?php

namespace App\Filament\Resources\Payroll\Employees\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('status')
                    ->options([
                        'single' => 'Single',
                        'married' => 'Married',
                    ])
                    ->required(),
                Select::make('department_id')
                    ->relationship('department', 'department')
                    ->required(),
                Select::make('position_id')
                    ->relationship('position', 'position')
                    ->required(),

            ]);
    }
}
