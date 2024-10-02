<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomReservationResource\Pages;
use App\Filament\Resources\ClassroomReservationResource\RelationManagers\LessonsRelationManager;
use App\Models\ClassroomReservation;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ClassroomReservationResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $model = ClassroomReservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 10;

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasAnyRole(['teacher']);
    }

    public static function canCreate(): bool
    {
        return Filament::auth()->user()->can('create', static::getModel());
    }

    public static function canView($record): bool
    {
        return Filament::auth()->user()->can('view', $record);
    }

    public static function canEdit($record): bool
    {
        return Filament::auth()->user()->can('update', $record);
    }

    public static function canDelete($record): bool
    {
        return Filament::auth()->user()->can('delete', $record);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('classroom_id')
                    ->relationship('classroom', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('teacher_id')
                    ->label('Teacher')
                    ->options(function () {
                        return \App\Models\Teacher::with('user')->get()->pluck('user.name', 'id');
                    })
                    ->searchable()
                    ->default(null),
                Forms\Components\DateTimePicker::make('booked_from')
                    ->required(),
                Forms\Components\DateTimePicker::make('booked_to')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('classroom.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booked_from')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booked_to')
                    ->dateTime()
                    ->sortable(),
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
            LessonsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassroomReservations::route('/'),
            'create' => Pages\CreateClassroomReservation::route('/create'),
            'edit' => Pages\EditClassroomReservation::route('/{record}/edit'),
        ];
    }
}
