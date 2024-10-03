<?php

namespace App\Filament\Resources\ClassroomResource\RelationManagers;

use App\Models\ClassroomReservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ReservationsRelationManager extends RelationManager
{
    protected static string $relationship = 'reservations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('booked_from')
                    ->required()
                    ->rules([
                        fn ($get) => function ($attribute, $value, $fail) use ($get) {
                            $classroomId = $this->ownerRecord->id;

                            $conflict = ClassroomReservation::where('classroom_id', $classroomId)
                                ->where(function ($query) use ($value) {
                                    $query->where('booked_from', '<=', $value)
                                        ->where('booked_to', '>=', $value);
                                })
                                ->exists();

                            if ($conflict) {
                                $fail("The classroom is already booked at the selected start time.");
                            }
                        },
                    ]),
                Forms\Components\DateTimePicker::make('booked_to')
                    ->required()
                    ->afterOrEqual('booked_from')
                    ->rules([
                        fn ($get) => function ($attribute, $value, $fail) use ($get) {
                            $classroomId = $this->ownerRecord->id;

                            $conflict = ClassroomReservation::where('classroom_id', $classroomId)
                                ->where(function ($query) use ($value) {
                                    $query->where('booked_from', '<=', $value)
                                        ->where('booked_to', '>=', $value);
                                })
                                ->exists();

                            if ($conflict) {
                                $fail("The classroom is already booked at the selected end time.");
                            }
                        },
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('booked_from')
            ->columns([
                Tables\Columns\TextColumn::make('booked_from')
                    ->label('Booked From')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('booked_to')
                    ->label('Booked To')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn($record) => route('filament.admin.resources.classroom-reservations.edit', $record)),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

