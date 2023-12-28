<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellResource\Pages;
use App\Filament\Resources\SellResource\RelationManagers;
use App\Models\Main;
use App\Models\Sell;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;

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
    return parent::getEloquentQuery()->where('sell_type','2')->orderBy('order_date','desc');
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
              TextColumn::make('Jehasell.jeha_name')
                ->searchable()
                ->sortable()
                ->label('اسم الزبون'),
              TextColumn::make('tot')
                ->label('اجمالي الفاتورة'),
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
                  Tables\Actions\Action::make('عرض')
                    ->fillForm(fn (Sell $record): array => [
                      'Main.no' => $record->Main->no,
                      'Main.kst' => $record->Main->kst,
                      'Main.sul_date' => $record->Main->sul_date,
                    ])
                    ->form([
                      TextInput::make('Main.no'),
                      TextInput::make('Main.kst'),
                      TextInput::make('Main.sul_date'),
                    ])
                )

            ])
            ->filters([
                //
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
