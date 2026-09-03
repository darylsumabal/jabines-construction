<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Models\Billing;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use PtPlugins\FilamentNumberInput\Fields\NumberInput;

class BillingRelationManager extends RelationManager
{
    protected static string $relationship = 'billings';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    TextInput::make('ref_no')
                        ->required()
                        ->suffixAction(
                            Action::make('generateRefCode')
                                ->icon('heroicon-m-arrow-path')
                                ->action(function (Set $set) {
                                    $lastCategory = Billing::orderBy('id', 'desc')->first();

                                    if ($lastCategory && preg_match('/^BIL-(\d+)$/', $lastCategory->ref_no, $matches)) {
                                        $nextNumber = intval($matches[1]) + 1;
                                    } else {
                                        $nextNumber = 1;
                                    }

                                    $set('ref_no', 'BIL-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT));
                                })
                        )
                        ->maxLength(255),
                    DatePicker::make('date')
                        ->native(false)
                        ->required(),
                    Select::make('type')
                        ->label('Billing Type')
                        ->options([
                            'down_payment_billing' => 'Down Payment Billing',
                            'progress_billing' => 'Progress Billing',
                            'final_billing' => 'Final Billing',
                            'retention_billing' => 'Retention Billing',
                            'change_order_billing' => 'Change Order Billing',
                            'mobilization_billing' => 'Mobilization Billing',
                        ])
                        ->required(),
                ])->columnSpanFull(),
                NumberInput::make('amount')
                    ->label('Billing Amount')
                    ->american()
                    ->inputMode('decimal')
                    ->prefix('₱')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $vat = round((float) $state * 0.12, 2);
                        $total = (float) $state + $vat;
                        $set('vat', $vat);
                        $set('total', $total);
                        $set('balance', $total - (float) $get('amount_collected'));
                    }),
                NumberInput::make('vat')
                    ->label('VAT (12%)')
                    ->american()
                    ->prefix('₱')
                    ->readOnly(),
                NumberInput::make('total')
                    ->label('Total Billing')
                    ->american()
                    ->prefix('₱')
                    ->readOnly()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $set('balance', (float) $state - (float) $get('amount_collected'));
                    }),
                NumberInput::make('amount_collected')
                    ->required()
                    ->live()
                    ->american()
                    ->prefix('₱')
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $set('balance', (float) $get('total') - (float) $state);
                    }),
                NumberInput::make('balance')
                    ->label('Balance')
                    ->american()
                    ->prefix('₱')
                    ->readOnly(),
                Select::make('status')
                    ->options([
                        'partial' => 'Partial',
                        'unpaid' => 'Unpaid',
                        'paid' => 'Paid',
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
                    ->searchable(),
                TextColumn::make('date')
                    ->date(),
                TextColumn::make('type')
                    ->label('Billing Type'),
                TextColumn::make('amount')
                    ->label('Billing Amount')
                    ->numeric()
                    ->money('PHP'),
                TextColumn::make('vat')
                    ->label('VAT (12%)')
                    ->numeric()
                    ->money('PHP'),
                TextColumn::make('total')
                    ->label('Total Billing')
                    ->numeric()
                    ->money('PHP'),
                TextColumn::make('amount_collected')
                    ->numeric()
                    ->money('PHP'),
                TextColumn::make('balance')
                    ->numeric()
                    ->money('PHP'),
                TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->modalWidth(Width::FiveExtraLarge),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
