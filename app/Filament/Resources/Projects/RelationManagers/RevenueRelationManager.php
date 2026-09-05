<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Models\Revenue;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use PtPlugins\FilamentNumberInput\Fields\NumberInput;

class RevenueRelationManager extends RelationManager
{
    protected static string $relationship = 'revenues';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    TextInput::make('ref_no')
                        ->label('Ref No')
                        ->required()
                        ->suffixAction(
                            Action::make('generateRefCode')
                                ->icon('heroicon-m-arrow-path')
                                ->action(function (Set $set) {
                                    $lastRevenue = Revenue::orderBy('id', 'desc')->first();

                                    if ($lastRevenue && preg_match('/^REV-(\d+)$/', $lastRevenue->ref_no, $matches)) {
                                        $nextNumber = intval($matches[1]) + 1;
                                    } else {
                                        $nextNumber = 1;
                                    }

                                    $set('ref_no', 'REV-'.str_pad($nextNumber, 3, '0', STR_PAD_LEFT));
                                })
                        )
                        ->maxLength(255),
                    DatePicker::make('date')
                        ->native(false)
                        ->required(),
                    Select::make('billing_type')
                        ->label('Billing Type')
                        ->options([
                            'down_payment_billing' => 'Down Payment Billing',
                            'progress_billing' => 'Progress Billing',
                            'final_billing' => 'Final Billing',
                            'retention_billing' => 'Retention Billing',
                        ])
                        ->required(),
                ])->columnSpanFull(),
                NumberInput::make('amount')
                    ->label('Revenue Amount')
                    ->american()
                    ->prefix('₱')
                    ->required(),
                Select::make('remarks')
                    ->label('Remarks')
                    ->options([
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'on_hold' => 'On Hold',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ref_no')
            ->columns([
                TextColumn::make('ref_no')
                    ->label('Ref No')
                    ->searchable(),
                TextColumn::make('billing.type')
                    ->searchable(),
                TextColumn::make('billing.amount')
                    ->label('Revenue Amount')
                    ->numeric()
                    ->summarize(Sum::make()
                        ->money('PHP')
                        ->hiddenLabel())
                    ->money('PHP'),
                TextColumn::make('project.status'),
                TextColumn::make('billing.date'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->modalWidth(Width::FiveExtraLarge),
            ])
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
