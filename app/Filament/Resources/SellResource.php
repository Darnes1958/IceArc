<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellResource\Pages;
use App\Filament\Resources\SellResource\RelationManagers;
use App\Models\Halls_name;
use App\Models\Jeha;
use App\Models\Main;
use App\Models\Sell;
use App\Models\Stores_name;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\HtmlString;

class SellResource extends Resource
{
  public static function shouldRegisterNavigation(): bool
  {
    return  auth()->user()->can('فواتير مبيعات') ||
      auth()->user()->can('عقود');
  }
    protected static ?string $pluralModelLabel='استفسار عن فواتير مبيعات بالتقسيط';
    protected static ?string $model = Sell::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

  public static function getEloquentQuery(): Builder
  {
    return parent::getEloquentQuery()->where('price_type','2')->orderBy('order_date','desc');
  }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
              TextColumn::make('order_no')
              ->searchable()
              ->sortable()
              ->label('رقم الفاتورة'),
                TextColumn::make('order_date')
                    ->searchable()
                    ->sortable()
                    ->label('تاريخ الفاتورة'),
              TextColumn::make('Jehasell.jeha_name')
                ->searchable()
                ->sortable()
                ->label('اسم الزبون'),
              TextColumn::make('tot')
                ->label('اجمالي الفاتورة'),
              TextColumn::make('Stores_name.st_name')
                    ->label('نقطة البيع')
                    ->visible(function (Forms\Get $get){
                        $get('sell_type')==1;
                    }),
              TextColumn::make('Halls_name.hall_name')
                    ->label('نقطة البيع')
                  ->visible(function (Forms\Get $get){
                      $get('sell_type')==2;
                  }),

              TextColumn::make('Main.no')
                ->label('رقم العقد'),
              Tables\Columns\IconColumn::make('Main.no')
                ->label('لها عقد')
                ->icon(fn (string $state): string => match ($state) {
                  null => 'heroicon-o-pencil',
                  default => 'heroicon-o-check',
                })
                ->color(fn (string $state): string => match ($state) {
                  null => 'danger',
                  default => 'success',
                })
                ->action(
                  Tables\Actions\Action::make('عرض_بيانات_العقد')
                    ->fillForm(fn (Sell $record): array => [
                      'place_name'=> Sell::
                          where('order_no',$record->order_no)
                          ->when($record->sell_type==1,function ($q) {
                          $q->join('stores_names', function (JoinClause $join) {
                              $join->on('place_no', '=', 'st_no');
                          })->selectraw('st_name as place_name');
                         })
                        ->when($record->sell_type==2,function ($q) {
                            $q->join('halls_names', function (JoinClause $join) {
                                $join->on('place_no', '=', 'hall_no');
                            })->selectraw('hall_name as place_name');
                        })

                          ->first()->place_name,



                      'no' => Main::where('order_no',$record->order_no,)->first()->no,
                      'kst_count' => Main::where('order_no',$record->order_no,)->first()->kst_count,
                      'kst' => Main::where('order_no',$record->order_no,)->first()->kst,
                      'sul_date' => Main::where('order_no',$record->order_no,)->first()->sul_date,
                      'acc' => Main::where('order_no',$record->order_no,)->first()->acc,
                      'bank_name' => Main::where('order_no',$record->order_no,)->first()->Bank->bank_name,
                      'sul' => Main::where('order_no',$record->order_no,)->first()->sul,
                      'sul_pay' => Main::where('order_no',$record->order_no,)->first()->sul_pay,
                      'raseed' => Main::where('order_no',$record->order_no,)->first()->raseed,
                    ])

                      ->form([
                      Forms\Components\Section::make('للزبون')
                       ->description(function (Sell $record){
                           return Jeha::find($record->jeha)->jeha_name;
                       })
                       ->schema([
                           TextInput::make('place_name')
                               ->readOnly()
                               ->columnSpan(2)
                               ->label(new HtmlString('<div> <span class="text-blue-400">نقط البيع</span>   </div>')),
                           TextInput::make('no')
                               ->readOnly()
                               ->label(new HtmlString('<div> <span class="text-blue-400">رقم العقد</span>   </div>')),
                           TextInput::make('sul_date')
                               ->label('تاريخ العقد')
                               ->readOnly(),
                           TextInput::make('bank_name')
                               ->label('المصرف')
                               ->readOnly(),
                           TextInput::make('acc')
                               ->label('رقم الحساب')
                               ->readOnly(),
                           TextInput::make('kst_count')
                               ->readOnly()
                               ->label('عدد الاقساط'),
                           TextInput::make('kst')
                               ->readOnly()
                               ->label('القسط'),
                           TextInput::make('sul')
                               ->readOnly()
                               ->label('اجمالي العقد'),
                           TextInput::make('sul_pay')
                               ->readOnly()
                               ->label('المدفوع'),
                           TextInput::make('raseed')
                               ->readOnly()
                               ->label(new HtmlString('<div> <span class="text-danger-600">الرصيد المتبقي</span>   </div>')),
                       ])->columns(2)
                    ])
                      ->slideOver(),
                )

            ])
            ->filters([
                SelectFilter::make('sell_type')
                    ->options([
                        '1' => 'مخازن',
                        '2' => 'صالات',
                    ])
                    ->label('مخازن/صالات'),

                SelectFilter::make('place_no')
                    ->options(Stores_name::pluck('st_name', 'st_no'))

                    ->label('مخزن'),


                Tables\Filters\Filter::make('order_date')
                    ->form([
                        Forms\Components\DatePicker::make('من_تاريخ_الفاتورة'),
                        Forms\Components\DatePicker::make('الي_تاريخ_الفاتورة'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['من_تاريخ_الفاتورة'],
                                fn (Builder $query, $date): Builder => $query->whereDate('order_date', '>=', $date),
                            )
                            ->when(
                                $data['الي_تاريخ_الفاتورة'],
                                fn (Builder $query, $date): Builder => $query->whereDate('order_date', '<=', $date),
                            );
                    }),

            ])

            ;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSells::route('/'),

        ];
    }
}
