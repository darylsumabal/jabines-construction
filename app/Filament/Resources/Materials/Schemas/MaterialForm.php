<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required()
                    ->maxLength(255),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('category')
                    ->required()
                    ->maxLength(255),
                TextInput::make('unit_cost')
                    ->required()
                    ->numeric()
                    ->prefix('₱'),
            ]);
    }
}
