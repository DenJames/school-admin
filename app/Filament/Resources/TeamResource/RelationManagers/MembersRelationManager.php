<?php

namespace App\Filament\Resources\TeamResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members'; // Refers to the many-to-many 'members' relationship

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->searchable()
                    ->options(User::all()->pluck('name', 'id'))
                    ->required(),

                Forms\Components\Select::make('role')
                    ->label('Role')
                    ->options([
                        'admin' => 'Admin',
                        'member' => 'Member',
                        'viewer' => 'Viewer',
                    ])
                    ->default('member')
                    ->required(),
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

                Tables\Columns\TextColumn::make('pivot.role')
                    ->label('Role')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('New Member')
                    ->action(function ($data) {
                        $this->ownerRecord->members()->attach($data['user_id'], ['role' => $data['role']]);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data, $record) {
                        $this->ownerRecord->members()->updateExistingPivot($record->id, [
                            'role' => $data['role'],
                        ]);
                        return $data;
                    }),
                Tables\Actions\DeleteAction::make()
                    ->action(function ($record) {
                        $this->ownerRecord->members()->detach($record->id); // Remove user from team
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}



