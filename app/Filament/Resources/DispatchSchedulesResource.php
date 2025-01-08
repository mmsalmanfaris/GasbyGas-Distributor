<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DispatchSchedulesResource\Pages;
use App\Filament\Resources\DispatchSchedulesResource\RelationManagers;
use App\Models\DispatchSchedules;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DispatchSchedulesResource extends Resource
{
    protected static ?string $model = DispatchSchedules::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

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
                //
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
            'index' => Pages\ListDispatchSchedules::route('/'),
            'create' => Pages\CreateDispatchSchedules::route('/create'),
            'edit' => Pages\EditDispatchSchedules::route('/{record}/edit'),
        ];
    }
}
