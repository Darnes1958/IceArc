<?php

namespace App\Filament\Resources\FromExcelResource\Pages;

use App\Filament\Resources\FromExcelResource;
use App\Imports\FromExcelImport;
use App\Models\BankTajmeehy;
use App\Models\FromExcel;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListFromExcels extends ListRecords
{
    protected static string $resource = FromExcelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('Do')
                ->color('success')
                ->form([
                    Select::make('taj')
                        ->label('المصرف التجميعي')
                        ->options(BankTajmeehy::all()->pluck('TajName','TajNo'))
                        ->searchable()
                        ->preload()
                        ->default(1)
                        ->required(),
                    TextInput::make('headerrow')
                        ->default(10)
                        ->label('رقم سطر العنوان')
                        ->required(),
                ])

                ->action(function (array $data){
                    FromExcel::truncate();
                    User::find(Auth::id())->update(['empno'=>$data['headerrow'],'IsAdmin'=>$data['taj']]);

                }),

            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->slideOver()
                ->color('danger')
                ->use(FromExcelImport::class),

        ];
    }
}