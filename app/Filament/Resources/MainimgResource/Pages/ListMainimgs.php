<?php

namespace App\Filament\Resources\MainimgResource\Pages;

use App\Filament\Resources\MainimgResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class ListMainimgs extends ListRecords
{

  public function getBreadcrumbs(): array
  {
    return [""];
  }
    public function getTitle():  string|Htmlable
    {
        return  new HtmlString('<div class="leading-3 h-4 py-0 text-base text-blue-400 py-0">مستندات عقود</div>');
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
