<?php

namespace App\Filament\Resources\Payroll\Departments\Pages;

use App\Filament\Resources\Payroll\Departments\DepartmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDepartments extends ListRecords
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
