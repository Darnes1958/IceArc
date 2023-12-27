<?php

namespace App\Filament\Resources\BuyimgResource\Pages;

use App\Filament\Resources\BuyimgResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class ListBuyimgs extends ListRecords
{
  public function getBreadcrumbs(): array
  {
    return [""];
  }
    protected static string $resource = BuyimgResource::class;
  public function getTitle():  string|Htmlable
  {
    return  new HtmlString('<div class="leading-3 h-4 py-0 text-base text-green-400 py-0">مستندات مشتريات</div>');
  }
  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make()
        ->label('تحميل مستند'),
    ];
  }
}
