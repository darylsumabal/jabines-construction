<?php

namespace App\Filament\Resources\Payroll\Departments;

use App\Filament\Resources\Payroll\Departments\Pages\CreateDepartment;
use App\Filament\Resources\Payroll\Departments\Pages\EditDepartment;
use App\Filament\Resources\Payroll\Departments\Pages\ListDepartments;
use App\Filament\Resources\Payroll\Departments\Schemas\DepartmentForm;
use App\Filament\Resources\Payroll\Departments\Tables\DepartmentsTable;
use App\Filament\Resources\Payroll\Employees\EmployeeResource;
use App\Models\Department;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;
    protected static ?string $navigationParentItem = EmployeeResource::class;
    protected static string|UnitEnum|null $navigationGroup = 'Payroll';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    public static function form(Schema $schema): Schema
    {
        return DepartmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepartmentsTable::configure($table);
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
            'index' => ListDepartments::route('/'),
            // 'create' => CreateDepartment::route('/create'),
            // 'edit' => EditDepartment::route('/{record}/edit'),
        ];
    }
}
