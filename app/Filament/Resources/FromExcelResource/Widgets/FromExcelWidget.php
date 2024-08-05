<?php

namespace App\Filament\Resources\FromExcelResource\Widgets;

use App\Models\Dateofexcel;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class FromExcelWidget extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(function (Dateofexcel $dateofexcel) {
              $dateofexcel= Dateofexcel::where('taj_id',Auth::user()->IsAdmin);
              return $dateofexcel;
              }
            )
            ->columns([
                Tables\Columns\TextColumn::make('date_begin'),
                Tables\Columns\TextColumn::make('date_end'),
                Tables\Columns\TextColumn::make('taj_id'),
            ])
         ->action([
           Action::make('del')
            ->action(function (Dateofexcel $record){
             $record->delete();
            })
           ->requiresConfirmation()
          ]);
    }
}
