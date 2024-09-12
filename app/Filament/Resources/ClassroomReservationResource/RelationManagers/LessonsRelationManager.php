<?php

namespace App\Filament\Resources\ClassroomReservationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Lesson Name'),

                Forms\Components\TextInput::make('duration')
                    ->numeric()
                    ->required()
                    ->label('Duration (minutes)'),

                Forms\Components\Select::make('teacher_id')
                    ->options(function () {
                        return \App\Models\Teacher::with('user')->get()->pluck('user.name', 'id');
                    })
                    ->searchable()
                    ->required()
                    ->label('Teacher'),

                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name')
                    ->searchable()
                    ->required()
                    ->label('Team'),

                Forms\Components\Select::make('classroom_reservation_id')
                    ->options(function () {
                        return \App\Models\ClassroomReservation::with('classroom')->get()->pluck('classroom.name', 'id');
                    })
                    ->searchable()
                    ->required()
                    ->label('Classroom'),

                Forms\Components\DateTimePicker::make('starts_at')
                    ->required()
                    ->label('Start Time'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('team.name')
                    ->label('Team')
                    ->sortable(),

                Tables\Columns\TextColumn::make('teacher.user.name')
                    ->label('Teacher')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Lesson Name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('classroomReservation.classroom.name')
                    ->label('Classroom')
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration (minutes)')
                    ->sortable(),

                Tables\Columns\TextColumn::make('starts_at')
                    ->label('Start Time')
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
