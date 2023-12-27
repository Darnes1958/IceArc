<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MainimgResource\Pages;
use App\Filament\Resources\MainimgResource\RelationManagers;
use App\Models\Main;
use App\Models\Mainimg;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Storage;

class MainimgResource extends Resource
{
    public static function shouldRegisterNavigation(): bool
    {
        return  auth()->user()->can('ارشفة عقود');
    }

    public static $year='2023';

  protected static ?string $pluralModelLabel='مستندات عقود';

    protected static ?string $model = Mainimg::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('main_no')
                ->relationship('Main','name')
                ->searchable()
                ->preload()
                ->label('رقم العقد')
                ->required()
                ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                  $sul_date=Main::where('no',$state)->first()->sul_date;
                  self::$year = date('Y', strtotime($sul_date));
                }),
                FileUpload::make('image')
                  ->directory(function (Forms\Get $get){
                    return date('Y',strtotime(Main::where('no',$get('main_no'))->first()->sul_date));
                  })
                ->label('تحميل المستند')

                ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
              TextColumn::make('main_no')
              ->label('رقم العقد')
              ->sortable(),
              TextColumn::make('Main.name')
                ->label('الاسم')
                ->searchable()
                ->sortable(),
              TextColumn::make('Bank.bank_name')
                ->label('المصرف')
                ->searchable()
                ->sortable(),
              TextColumn::make('Main.acc')
                ->label('رقم الحساب')
                ->searchable()
                ->sortable(),
              ImageColumn::make('image')
                ->label('المستند')
                  ->action(
                      Tables\Actions\Action::make('عرض')
                          ->fillForm(fn (Mainimg $record): array => [
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
                                  ->imageResizeUpscale()
                                  ->allowImageResize()
                                  ->disabled()
                                  ->openable()
                                  ->downloadable()
                          ])
                  )
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                  ->modalHeading('حذف المستند')
                  ->after(function (Mainimg $record) {
                    // delete single
                    if ($record->image) {
                      Storage::disk('public')->delete($record->image);
                    }
                  }),
            ]);
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
            'index' => Pages\ListMainimgs::route('/'),
            'create' => Pages\CreateMainimg::route('/create'),
            'edit' => Pages\EditMainimg::route('/{record}/edit'),
        ];
    }
}
