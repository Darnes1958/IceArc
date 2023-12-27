<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BuyimgResource\Pages;
use App\Filament\Resources\BuyimgResource\RelationManagers;
use App\Models\Buy;
use App\Models\Buyimg;
use App\Models\Jeha;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Storage;

class BuyimgResource extends Resource
{
    public static function shouldRegisterNavigation(): bool
    {
        return  auth()->user()->can('ارشفة مشتريات');
    }
  protected static ?string $pluralModelLabel='مستندات مشتريات';
    protected static ?string $model = Buyimg::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              Select::make('buy_order_no')

                ->relationship(
                  name: 'Buy',
                  modifyQueryUsing: fn (Builder $query) => $query->orderBy('order_no','desc'),
                )
                ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->Jeha->jeha_name} {$record->order_no}")
                ->searchable()
                ->preload()
                ->label('رقم الفاتورة')
                ->required(),

              FileUpload::make('image')
                ->directory('buy')
                ->label('تحميل المستند')

                ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('buy_order_no')
                ->searchable()
                ->sortable()
                ->label('رقم الفاتورة'),
                TextColumn::make('Jeha.jeha_name')
                ->searchable()
                ->sortable()
                ->label('المورد'),
              TextColumn::make('order_date')
                ->searchable()
                ->sortable()
                ->label('تاريخ الفاتورة'),
              TextColumn::make('created_at')
                ->searchable()
                ->sortable()
                ->label('تاريخ الأرشفة'),
                ImageColumn::make('image')
                ->label('المستند')
                    ->action(
                        Tables\Actions\Action::make('عرض')
                            ->fillForm(fn (Buyimg $record): array => [
                                'image' => $record->image,
                            ])
                            ->form([
                                FileUpload::make('image')
                                    ->hiddenLabel()
                                    ->image()
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('16:9')
                                    ->imageResizeTargetWidth('1920')
                                    ->imageResizeTargetHeight('1080')
                                    ->disabled()
                                    ->openable()
                                    ->downloadable()
                           ])
                    )


            ])
            ->filters([
              SelectFilter::make('jeha_jeha_no')
                ->options(Jeha::where('jeha_type',2)->pluck('jeha_name', 'jeha_no'))
                ->label('مورد معين'),

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
              Tables\Filters\Filter::make('created_at')
                ->form([
                  Forms\Components\DatePicker::make('من_تاريخ_الأرشفة'),
                  Forms\Components\DatePicker::make('الي_تاريخ_الأرشفة'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                  return $query
                    ->when(
                      $data['من_تاريخ_الأرشفة'],
                      fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                    )
                    ->when(
                      $data['الي_تاريخ_الأرشفة'],
                      fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                    );
                })

            ])
            ->actions([
                Tables\Actions\EditAction::make(),

              Tables\Actions\DeleteAction::make()
                ->modalHeading('حذف المستند')
                ->after(function (Buyimg $record) {
                  // delete single
                  if ($record->image) {

                    Storage::disk('public')->delete($record->image);
                  }

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
            'index' => Pages\ListBuyimgs::route('/'),
            'create' => Pages\CreateBuyimg::route('/create'),
            'edit' => Pages\EditBuyimg::route('/{record}/edit'),
        ];
    }
}
