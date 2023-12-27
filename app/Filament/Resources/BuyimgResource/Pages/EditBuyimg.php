<?php

namespace App\Filament\Resources\BuyimgResource\Pages;

use App\Filament\Resources\BuyimgResource;
use App\Models\Buy;
use App\Models\Buyimg;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditBuyimg extends EditRecord
{
    protected static string $resource = BuyimgResource::class;

  protected function getActions(): array
  {
    return [
      Actions\DeleteAction::make()
        ->after(function (Buyimg $record) {
          // delete single
          if ($record->image) {

            Storage::disk('public')->delete($record->image);
          }

        })


    ];
  }
  protected function mutateFormDataBeforeSave(array $data): array
  {
    $data['jeha_jeha_no'] = Buy::where('order_no',$data['buy_order_no'])->first()->jeha;
    $data['order_date'] = Buy::where('order_no',$data['buy_order_no'])->first()->order_date;


    return $data;
  }

}
