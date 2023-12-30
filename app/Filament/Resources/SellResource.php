<?php

namespace App\Filament\Resources;
use Filament\Forms\Get;
use Filament\Forms\Components\Select;
use Illuminate\Support\Collection;
use App\Models\MasCenter;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Enums\FiltersLayout;
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
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Radio;
use Livewire\Attributes\Reactive;

class SellResource extends Resource
{

  public static $place_type=2;
  public static function shouldRegisterNavigation(): bool
  {
    return  auth()->user()->can('فواتير مبيعات') ||
      auth()->user()->can('عقود');
  }
    protected static ?string $pluralModelLabel='استفسار عن فواتير مبيعات بالتقسيط';
    protected static ?string $model = Sell::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

  protected ?string $heading = '';
  public function getBreadcrumbs(): array
  {
    return [""];
  }
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
                ->visible(function (){return self::$place_type==1;})
                ->label('نقطة البيع'),
              TextColumn::make('Halls_name.hall_name')
                ->visible(function (){return self::$place_type==2;})
                ->label('نقطة البيع'),
              TextColumn::make('Main.no')
                ->label('رقم العقد'),
              Tables\Columns\IconColumn::make('Main.no')
                ->label('لها عقد')
                ->icon(function (string $state) {
                 if (is_string($state) ) return 'heroicon-o-check' ;
                 else return 'heroicon-o-x-mark';
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
                Tables\Filters\Filter::make('order_date')
                    ->form([
                      Forms\Components\Grid::make()
                      ->schema([
                        Radio::make('مخزن_او_صاله')
                          ->inline()
                          ->live()
                          ->reactive()
                          ->options([
                            1 => 'مخازن',
                            2 => 'صالات',

                          ])
                          ->afterStateUpdated(function (?string $state) {
                            if ($state==1) {  self::$place_type=1 ;}
                            else { self::$place_type=2 ;}
                          }),
                        Forms\Components\DatePicker::make('من_تاريخ_الفاتورة')
                          ->inlineLabel()

                          ->afterStateUpdated(function (Forms\Get $get) {
                            self::$place_type=$get('مخزن_او_صاله');
                          }
                          ),
                        Forms\Components\DatePicker::make('الي_تاريخ_الفاتورة')->inlineLabel()
                          ->afterStateUpdated(function (Forms\Get $get) {
                            self::$place_type=$get('مخزن_او_صاله');
                          }),
                       Select::make('place_no')
                        ->label('نقطة البيع')
                          ->options(fn (Get $get): Collection => MasCenter::query()
                            ->where('place_type', $get('مخزن_او_صاله'))
                            ->pluck('CenterName', 'WhoID'))
                          ->preload()
                         ->inlineLabel()
                          ->afterStateUpdated(function (Forms\Get $get) {
                           self::$place_type=$get('مخزن_او_صاله');
                            }
                            ),

                      ])->columns(4)
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
                            )
                          ->when( $data['مخزن_او_صاله'],
                            fn (Builder $query, $int): Builder => $query->where('sell_type', '=', $int),
                          )
                          ->when( $data['place_no'],
                            fn (Builder $query, $int): Builder => $query->where('place_no', '=', $int),
                          );

                    }),

            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(1) ;
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
