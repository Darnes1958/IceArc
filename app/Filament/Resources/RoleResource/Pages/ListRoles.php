<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Imports\FromExcelImport;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
          \EightyNine\ExcelImport\ExcelImportAction::make()
            ->slideOver()
            ->color("primary")
            ->use(FromExcelImport::class),
          Actions\CreateAction::make(),
        ];
    }
}
