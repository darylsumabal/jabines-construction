<?php

namespace App\Filament\Resources\ProjectCostings\Pages;

use App\Filament\Resources\ProjectCostings\ProjectCostingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProjectCostings extends ListRecords
{
    protected static string $resource = ProjectCostingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
