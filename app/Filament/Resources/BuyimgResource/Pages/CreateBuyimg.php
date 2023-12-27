<?php

namespace App\Filament\Resources\BuyimgResource\Pages;

use App\Filament\Resources\BuyimgResource;
use App\Models\Buy;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBuyimg extends CreateRecord
{
    protected static string $resource = BuyimgResource::class;
  protected ?string $heading = '';
  public function getBreadcrumbs(): array
  {
    return [""];
  }
  protected function getRedirectUrl(): string
  {
    return $this->getResource()::getUrl('create');
  }
  protected function mutateFormDataBeforeCreate(array $data): array
  {
    $data['jeha_jeha_no'] = Buy::where('order_no',$data['buy_order_no'])->first()->jeha;
    $data['order_date'] = Buy::where('order_no',$data['buy_order_no'])->first()->order_date;
    return $data;
  }
}
