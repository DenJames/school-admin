<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssignedGradeResource\Pages;
use App\Filament\Resources\AssignedGradeResource\RelationManagers;
use App\Models\AssignedGrade;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AssignedGradeResource extends Resource
{
    protected static ?string $model = AssignedGrade::class;

    protected static ?string $navigationIcon = 'heroicon-s-presentation-chart-bar';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('grade')
                    ->label('Grade')
                    ->options(function () {
                        return [
                            -3 => '-3',
                            0 => '00',
                            2 => '02',
                            4 => '4',
                            7 => '7',
                            10 => '10',
                            12 => '12',
                        ];
                    })
                    ->searchable()
                    ->required()
                    ->default(null)
                    ->afterStateHydrated(function ($component, $state) {
                        $component->state((int) $state);
                    })
                    ->dehydrateStateUsing(fn ($state) => (int) $state),
                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name')
                    ->preload()
                    ->searchable()
                    ->default(null),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('teacher_id')
                    ->label('Teacher')
                    ->options(function () {
                        return Teacher::with('user')->get()->pluck('user.name', 'id');
                    })
                    ->searchable()
                    ->default(null),
                Forms\Components\Select::make('class_category_id')
                    ->relationship('subject', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('comment')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('grade')
                    ->sortable(),
                Tables\Columns\TextColumn::make('team.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.user.name')
                    ->label('Teacher')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment')
                    ->searchable(),
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
            RelationManagers\TeamRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssignedGrades::route('/'),
            'create' => Pages\CreateAssignedGrade::route('/create'),
            'edit' => Pages\EditAssignedGrade::route('/{record}/edit'),
        ];
    }
}
