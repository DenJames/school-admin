<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Lesson;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AbsencesRelationManager extends RelationManager
{
    protected static string $relationship = 'absences';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('teacher_id')
                    ->label('Teacher')
                    ->options(function () {
                        return Teacher::with('user')->get()->pluck('user.name', 'id');
                    })
                    ->searchable()
                    ->default(fn($record) => $record?->teacher->user->name),
                Forms\Components\Select::make('lesson_id')
                    ->label('Lesson')
                    ->options(function () {
                        return Lesson::with('teacher')
                            ->get()
                            ->mapWithKeys(function ($lesson) {
                                return [$lesson->id => sprintf('%s (%s) (%s)', $lesson->name, $lesson->teacher->user->name, $lesson->starts_at)];
                            });
                    })
                    ->preload()
                    ->searchable()
                    ->default(null),
                Forms\Components\TextInput::make('reason')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('excused')
                    ->required(),
                Forms\Components\DateTimePicker::make('approved_at'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reason')
            ->columns([
                Tables\Columns\TextColumn::make('teacher.user.name')
                    ->label('Teacher')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lesson.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reason')
                    ->searchable(),
                Tables\Columns\IconColumn::make('excused')
                    ->boolean(),
                Tables\Columns\TextColumn::make('approved_at')
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
