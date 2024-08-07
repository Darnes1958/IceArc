<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use App\Models\BankTajmeehy;
use App\Models\Dateofexcel;
use App\Models\excel\FromExcelModel;
use App\Models\FromExcel;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use App\Imports\FromExcelImport;
use Illuminate\Support\Facades\Auth;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

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
          Actions\Action::make('check')
           ->action(function (){
             $beginDate=FromExcel::min('ksm_date');
             $endDate=FromExcel::max('ksm_date');
             $res=Dateofexcel::where('taj_id',$this->TajNo)
               ->whereBetween('date_begin',[$beginDate,$endDate])->first();
             if ($res){
               FromExcel::truncate();
               $this->dispatchBrowserEvent('mmsg', 'يوجد تداخل في تاريخ الحافظة مع حافظة سابقة لنفس المصرف ');
               return false;

             }

             Dateofexcel::create([
                 'taj_id'=>$this->TajNo,
                 'date_begin'=>FromExcel::min('ksm_date'),
                 'date_end'=>FromExcel::max('ksm_date'),
               ]
             );
           })

        ];
    }
}
