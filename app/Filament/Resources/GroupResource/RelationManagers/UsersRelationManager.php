<?php

namespace App\Filament\Resources\GroupResource\RelationManagers;

use App\Models\GroupRole;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->searchable()
                    ->options(function () {
                        $group = $this->ownerRecord;

                        // Get the IDs of users who are already members of the group
                        $existingUserIds = $group->users->pluck('id')->toArray();

                        // Fetch users that are not already members of the group
                        return User::whereNotIn('id', $existingUserIds)
                            ->pluck('name', 'id');
                    })
                    ->hidden(fn($record) => $record !== null) // Hide when editing
                    ->disabled(fn($record) => $record !== null) // Disable when editing
                    ->required(),

                Forms\Components\Select::make('group_role_id')
                    ->label('Role')
                    ->searchable()
                    ->options(function () {
                        return GroupRole::pluck('name', 'id');
                    })
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pivot.role.name')
                    ->label('Role')
                    ->sortable()
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add member')
                    ->action(function ($data) {
                        // Attach the user to the group and include the role in the pivot table
                        $this->ownerRecord->users()->attach($data['user_id'], [
                            'group_role_id' => $data['group_role_id'],
                        ]);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit Member')
                    ->mutateFormDataUsing(function (array $data, $record) {
                        // Update the pivot table with the new role
                        $this->ownerRecord->users()->updateExistingPivot($record->id, [
                            'group_role_id' => $data['group_role_id'],
                        ]);
                        return $data;
                    }),
                Tables\Actions\DeleteAction::make()
                    ->label('Remove Member')
                    ->action(function ($record) {
                        // Detach the user from the group
                        $this->ownerRecord->users()->detach($record->id);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
