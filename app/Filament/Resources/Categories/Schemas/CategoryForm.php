<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('ref_code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->suffixAction(
                        Action::make('generateRefCode')
                            ->icon('heroicon-m-arrow-path')
                            ->action(function (Set $set) {
                                $lastCategory = Category::orderBy('id', 'desc')->first();

                                if ($lastCategory && preg_match('/^CAT-(\d+)$/', $lastCategory->ref_code, $matches)) {
                                    $nextNumber = intval($matches[1]) + 1;
                                } else {
                                    $nextNumber = 1;
                                }

                                $set('ref_code', 'CAT-'.str_pad($nextNumber, 3, '0', STR_PAD_LEFT));
                            })
                    ),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->rows(3)
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }
}
