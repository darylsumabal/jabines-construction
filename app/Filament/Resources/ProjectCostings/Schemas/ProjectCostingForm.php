<?php

namespace App\Filament\Resources\ProjectCostings\Schemas;

use App\Models\Project;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use PtPlugins\FilamentNumberInput\Fields\NumberInput;

class ProjectCostingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('project_id')
                        ->relationship('project', 'project_name')
                        ->getOptionLabelFromRecordUsing(
                            fn (Project $record) => "{$record->project_code} {$record->client} {$record->project_name}"
                        )
                        ->required()
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateHydrated(function ($state, Set $set) {
                            if (! $state) {
                                return;
                            }

                            $project = Project::find($state);

                            if (! $project) {
                                return;
                            }

                            $set('budget', $project->budget);
                        })
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            if ($state) {
                                $project = Project::find($state);
                                $actualCost = (float) ($get('actual_cost') ?? 0);
                                $variance = $project->budget - $actualCost;

                                $set('budget', $variance);
                                $set('status', $variance < 0 ? 'over_budget' : 'on_track');
                            } else {
                                $set('budget', null);
                                $set('status', 'pending');
                            }
                        }),

                    NumberInput::make('budget')
                        ->required()
                        ->american()
                        ->prefix('₱')
                        ->readOnly()
                        ->dehydrated(false),

                    NumberInput::make('actual_cost')
                        ->required()
                        ->american()
                        ->prefix('₱')
                        ->live(debounce: 500)
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            $projectId = $get('project_id');

                            if ($projectId) {
                                $project = Project::find($projectId);
                                $actualCost = (float) ($state ?? 0);
                                $variance = $project->budget - $actualCost;

                                $set('variance', number_format($variance, 2, '.', ''));
                                $set('status', $variance < 0 ? 'over_budget' : 'on_track');
                            }
                        }),

                    NumberInput::make('variance')
                        ->readOnly()
                        ->american()
                        ->prefix('₱'),

                    Select::make('status')
                        ->options([
                            'on_track' => 'On Track',
                            'over_budget' => 'Over Budget',
                        ])
                        ->default('pending')
                        ->disabled()
                        ->required(),
                ])->columnSpanFull(),
            ]);
    }
}
