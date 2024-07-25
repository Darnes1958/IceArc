<?php

namespace App\Filament\Resources\FromExcelResource\Pages;

use App\Filament\Resources\FromExcelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFromExcel extends EditRecord
{
    protected static string $resource = FromExcelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
