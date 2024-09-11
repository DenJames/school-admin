<?php

namespace App\Filament\Resources\TeamResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name'),

                // Handle role directly from the 'team_user' pivot relationship.
                Forms\Components\Select::make('pivot.role') // Access the 'role' from the 'team_user' pivot table
                ->options([
                    'admin' => 'Admin',
                    'member' => 'Member',
                    'viewer' => 'Viewer',
                ])
                    ->required()
                    ->default('member'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pivot.role') // Access the pivot table's role field
                ->label('Role')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data, $record) {
                        // Manually update the pivot table with the role
                        $this->ownerRecord->members()->updateExistingPivot($record->id, [
                            'role' => $data['pivot']['role'],
                        ]);

                        return $data; // Return the data after mutating
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
