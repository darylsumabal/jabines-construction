<?php

namespace App\Filament\Resources\Payroll\Positions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PositionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('position')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
