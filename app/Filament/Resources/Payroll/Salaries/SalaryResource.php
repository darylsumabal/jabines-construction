<?php

namespace App\Filament\Resources\Payroll\Salaries;

use App\Filament\Resources\Payroll\Salaries\Pages\CreateSalary;
use App\Filament\Resources\Payroll\Salaries\Pages\EditSalary;
use App\Filament\Resources\Payroll\Salaries\Pages\ListSalaries;
use App\Filament\Resources\Payroll\Salaries\Schemas\SalaryForm;
use App\Filament\Resources\Payroll\Salaries\Tables\SalariesTable;
use App\Models\Salary;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SalaryResource extends Resource
{
    protected static ?string $model = Salary::class;

    protected static string|UnitEnum|null $navigationGroup = 'Payroll';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function form(Schema $schema): Schema
    {
        return SalaryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalariesTable::configure($table);
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
            'index' => ListSalaries::route('/'),
            'create' => CreateSalary::route('/create'),
            'edit' => EditSalary::route('/{record}/edit'),
        ];
    }
}
