<?php

namespace App\Filament\Imports;

use App\Models\Material;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class MaterialImporter extends Importer
{
    protected static ?string $model = Material::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('ref_code')
                ->label('Code')
                ->requiredMapping(),
            ImportColumn::make('name')
                ->label('Name')
                ->requiredMapping(),
            ImportColumn::make('category')
                ->label('Category')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping(),
            ImportColumn::make('unit')
                ->label('Unit')
                ->requiredMapping(),
            ImportColumn::make('unit_cost')
                ->label('Unit Cost')
                ->numeric()
                ->requiredMapping(),
        ];
    }

    public function resolveRecord(): Material
    {
        return Material::firstOrNew([
            'ref_code' => $this->data['ref_code'],
        ])->fill([
            'ref_code' => $this->data['ref_code'],
            'name' => $this->data['name'],
            'unit' => $this->data['unit'],
            'unit_cost' => $this->data['unit_cost'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your material import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
