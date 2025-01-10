<?php

namespace App\Filament\Outlet\Resources;

use App\Filament\Outlet\Resources\DispatchSchedulesResource\Pages;
use App\Filament\Outlet\Resources\DispatchSchedulesResource\RelationManagers;
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
                Forms\Components\TextInput::make('outlet_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('request')
                    ->required(),
                Forms\Components\DatePicker::make('edelivery')
                    ->required(),
                Forms\Components\DatePicker::make('sdelivery'),
                Forms\Components\TextInput::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('outlet_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('request')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('edelivery')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sdelivery')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
