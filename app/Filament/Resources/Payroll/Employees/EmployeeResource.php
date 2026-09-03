<?php

namespace App\Filament\Resources\Payroll\Employees;

use App\Filament\Resources\Payroll\Employees\Pages\CreateEmployee;
use App\Filament\Resources\Payroll\Employees\Pages\EditEmployee;
use App\Filament\Resources\Payroll\Employees\Pages\ListEmployees;
use App\Filament\Resources\Payroll\Employees\Schemas\EmployeeForm;
use App\Filament\Resources\Payroll\Employees\Tables\EmployeesTable;
use App\Models\Employee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static string|UnitEnum|null $navigationGroup = 'Payroll';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return EmployeeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeesTable::configure($table);
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
            'index' => ListEmployees::route('/'),
            'create' => CreateEmployee::route('/create'),
            'edit' => EditEmployee::route('/{record}/edit'),
        ];
    }
}
