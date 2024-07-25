<?php

namespace App\Filament\Resources\FromExcelResource\Pages;

use App\Filament\Resources\FromExcelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFromExcel extends CreateRecord
{
    protected static string $resource = FromExcelResource::class;
}
