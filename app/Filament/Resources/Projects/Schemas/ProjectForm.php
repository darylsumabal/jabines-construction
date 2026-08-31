<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('project_code')
                    ->required()
                    ->maxLength(255),
                TextInput::make('project_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('client')
                    ->required()
                    ->maxLength(255),
                TextInput::make('budget')
                    ->required()
                    ->numeric()
                    ->prefix('₱'),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'on_hold' => 'On Hold',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }
}
