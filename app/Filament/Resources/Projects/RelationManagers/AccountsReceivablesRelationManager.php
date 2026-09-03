<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Models\AccountsReceivable;
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
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use PtPlugins\FilamentNumberInput\Fields\NumberInput;

class AccountsReceivablesRelationManager extends RelationManager
{
    protected static string $relationship = 'accountsReceivables';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('ref_no')
                    ->required()
                    ->suffixAction(
                        Action::make('generateRefCode')
                            ->icon('heroicon-m-arrow-path')
                            ->action(function (Set $set) {
                                $lastCategory = AccountsReceivable::orderBy('id', 'desc')->first();

                                if ($lastCategory && preg_match('/^ACR-(\d+)$/', $lastCategory->ref_no, $matches)) {
                                    $nextNumber = intval($matches[1]) + 1;
                                } else {
                                    $nextNumber = 1;
                                }

                                $set('ref_no', 'ACR-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT));
                            })
                    )
                    ->maxLength(255),

                DatePicker::make('date')
                    ->required()
                    ->native(false),

                NumberInput::make('collected')
                    ->american()
                    ->prefix('₱')
                    ->required(),

                NumberInput::make('balance')
                    ->american()
                    ->prefix('₱')
                    ->required(),

                Select::make('status')
                    ->options([
                        'partial' => 'Partial',
                        'unpaid' => 'Unpaid',
                        'paid' => 'Paid',
                    ])
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ref_no')
            ->columns([
                TextColumn::make('ref_no')
                    ->searchable(),
                TextColumn::make('collected')
                    ->money('PHP'),
                TextColumn::make('balance')
                    ->money('PHP'),
                TextColumn::make('status'),
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
