<?php

namespace App\Filament\Resources\ProjectCostings;

use App\Filament\Resources\ProjectCostings\Pages\CreateProjectCosting;
use App\Filament\Resources\ProjectCostings\Pages\EditProjectCosting;
use App\Filament\Resources\ProjectCostings\Pages\ListProjectCostings;
use App\Filament\Resources\ProjectCostings\Schemas\ProjectCostingForm;
use App\Filament\Resources\ProjectCostings\Tables\ProjectCostingsTable;
use App\Models\ProjectCosting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProjectCostingResource extends Resource
{
    protected static ?string $model = ProjectCosting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProjectCostingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectCostingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjectCostings::route('/'),
            'create' => CreateProjectCosting::route('/create'),
            'edit' => EditProjectCosting::route('/{record}/edit'),
        ];
    }
}
