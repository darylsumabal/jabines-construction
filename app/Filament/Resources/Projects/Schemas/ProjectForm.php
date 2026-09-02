<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Models\Project;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use PtPlugins\FilamentNumberInput\Fields\NumberInput;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    TextInput::make('project_code')
                        ->required()
                        ->suffixAction(
                            Action::make('generateRefCode')
                                ->icon('heroicon-m-arrow-path')
                                ->action(function (Set $set) {
                                    $lastCategory = Project::orderBy('id', 'desc')->first();

                                    if ($lastCategory && preg_match('/^PRJ-(\d+)$/', $lastCategory->project_code, $matches)) {
                                        $nextNumber = intval($matches[1]) + 1;
                                    } else {
                                        $nextNumber = 1;
                                    }

                                    $set('project_code', 'PRJ-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT));
                                })
                        )
                        ->maxLength(255),
                    TextInput::make('project_name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('client')
                        ->required()
                        ->maxLength(255),
                ])->columnSpanFull(),
                NumberInput::make('budget')
                    ->required()
                    ->american()
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
