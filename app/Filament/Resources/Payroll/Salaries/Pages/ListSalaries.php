<?php

namespace App\Filament\Resources\Payroll\Salaries\Pages;

use App\Filament\Resources\Payroll\Salaries\SalaryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSalaries extends ListRecords
{
    protected static string $resource = SalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
