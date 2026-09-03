<?php

namespace App\Filament\Resources\Payroll\Employees\Pages;

use App\Filament\Resources\Payroll\Employees\EmployeeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
}
