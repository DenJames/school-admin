<?php

namespace App\Filament\Resources\AssignmentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TeacherRelationManager extends RelationManager
{
    protected static string $relationship = 'teacher';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Display teacher's user details (e.g., name and email)
                Forms\Components\TextInput::make('user.name')
                    ->label('Teacher Name')
                    ->disabled(),  // This field should not be editable

                Forms\Components\TextInput::make('user.email')
                    ->label('Teacher Email')
                    ->disabled(),  // This field should not be editable
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name') // Set the title to the teacher's user name
            ->columns([
                // Display teacher's user details in table columns
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Teacher Name'),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Teacher Email'),
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
