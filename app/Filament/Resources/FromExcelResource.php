<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FromExcelResource\Pages;
use App\Filament\Resources\FromExcelResource\RelationManagers;
use App\Models\FromExcel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FromExcelResource extends Resource
{
    protected static ?string $model = FromExcel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        return  auth()->user()->id==1;
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
              Tables\Columns\TextColumn::make('no')->searchable()->sortable(),
              Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
              Tables\Columns\TextColumn::make('acc')->searchable()->sortable(),
              Tables\Columns\TextColumn::make('ksm_date'),
              Tables\Columns\TextColumn::make('ksm'),
              Tables\Columns\TextColumn::make('hafitha_tajmeehy'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListFromExcels::route('/'),
            'create' => Pages\CreateFromExcel::route('/create'),
            'edit' => Pages\EditFromExcel::route('/{record}/edit'),
        ];
    }
}
