<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use App\Models\FromExcel;
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
            Actions\Action::make('Do')
            ->color('success')
            ->action(function (){
              FromExcel::truncate();
            }),

          \EightyNine\ExcelImport\ExcelImportAction::make()
            ->slideOver()
            ->color('danger')
            ->use(FromExcelImport::class),

        ];
    }
}
