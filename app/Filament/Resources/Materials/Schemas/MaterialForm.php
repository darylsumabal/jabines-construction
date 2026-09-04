<?php

namespace App\Filament\Resources\Materials\Schemas;

use App\Models\Material;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    TextInput::make('ref_code')
                        ->required()
                        ->suffixAction(
                            Action::make('generateRefCode')
                                ->icon('heroicon-m-arrow-path')
                                ->action(function (Set $set) {
                                    $lastCategory = Material::orderBy('id', 'desc')->first();

                                    if ($lastCategory && preg_match('/^MAT-(\d+)$/', $lastCategory->ref_code, $matches)) {
                                        $nextNumber = intval($matches[1]) + 1;
                                    } else {
                                        $nextNumber = 1;
                                    }

                                    $set('ref_code', 'MAT-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT));
                                })
                        )
                        ->maxLength(255),
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Select::make('category_id')
                        ->relationship('category', 'name')
                        ->required(),
                ])->columnSpanFull(),
                Grid::make(2)->schema([
                    TextInput::make('unit')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('unit_cost')
                        ->required()
                        ->numeric()
                        ->prefix('₱')
                ])->columnSpanFull()
            ]);
    }
}
