<?php

namespace App\Filament\Resources\ProjectCostings\Pages;

use App\Filament\Resources\ProjectCostings\ProjectCostingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProjectCosting extends EditRecord
{
    protected static string $resource = ProjectCostingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
