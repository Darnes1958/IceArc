<?php

namespace App\Filament\Resources\SellResource\Pages;

use App\Filament\Resources\SellResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class ListSells extends ListRecords
{
    protected static string $resource = SellResource::class;
  public function getTitle():  string|Htmlable
  {
    return  new HtmlString('<div class="leading-3 h-4 py-0 text-base text-blue-400 py-0">استفسار عن فواتير مبيعات بالتقسيط</div>');
  }
    protected function getHeaderActions(): array
    {
       return [];
    }
}
