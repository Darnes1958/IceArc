<?php

namespace App\Filament\Resources\MainimgResource\Pages;

use App\Filament\Resources\MainimgResource;
use App\Models\Main;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;


class CreateMainimg extends CreateRecord
{
  protected ?string $heading = '';
  public function getBreadcrumbs(): array
  {
    return [""];
  }
  protected function getRedirectUrl(): string
  {
    return $this->getResource()::getUrl('create');
  }
  protected static string $resource = MainimgResource::class;
  protected function mutateFormDataBeforeCreate(array $data): array
  {
    $data['jeha_jeha_no'] = Main::where('no',$data['main_no'])->first()->jeha;
    $data['bank_bank_no'] = Main::where('no',$data['main_no'])->first()->bank;


    return $data;
  }
}
