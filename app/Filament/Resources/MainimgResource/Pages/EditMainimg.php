<?php

namespace App\Filament\Resources\MainimgResource\Pages;

use App\Filament\Resources\MainimgResource;
use App\Models\Main;
use App\Models\Mainimg;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditMainimg extends EditRecord
{
    protected static string $resource = MainimgResource::class;


  public function getBreadcrumbs(): array
  {
    return [""];
  }


  protected function getActions(): array
  {
    return [
      Actions\DeleteAction::make()
        ->after(function (Mainimg $record) {
          // delete single
          if ($record->image) {

            Storage::disk('public')->delete($record->image);
          }

        }),
    ];
  }
  protected function mutateFormDataBeforeSave(array $data): array
  {
    $data['jeha_jeha_no'] = Main::where('no',$data['main_no'])->first()->jeha;
    $data['bank_bank_no'] = Main::where('no',$data['main_no'])->first()->bank;


    return $data;
  }

}
