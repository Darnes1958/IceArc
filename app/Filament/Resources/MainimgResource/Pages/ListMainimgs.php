<?php

namespace App\Filament\Resources\MainimgResource\Pages;

use App\Filament\Resources\MainimgResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListMainimgs extends ListRecords
{

  public function getBreadcrumbs(): array
  {
    return [""];
  }
    protected static string $resource = MainimgResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                    ->label('تحميل مستند'),


        ];
    }
}
