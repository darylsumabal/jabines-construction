<?php

namespace App\Filament\Resources\Payroll\Salaries\Pages;

use App\Filament\Resources\Payroll\Salaries\SalaryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSalary extends EditRecord
{
    protected static string $resource = SalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
