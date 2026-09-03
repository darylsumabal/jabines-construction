<?php

namespace App\Filament\Resources\Payroll\Departments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('department')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
