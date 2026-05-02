<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\FarmerResource\Pages;
use App\Models\Farmer;
use App\Models\User;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FarmerResource extends Resource
{
    protected static ?string $model = Farmer::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Agriculteurs';

    protected static ?string $modelLabel = 'Agriculteur';

    protected static ?string $pluralModelLabel = 'Agriculteurs';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('identifier')
                ->label('Identifiant')
                ->required()
                ->maxLength(50)
                ->unique(ignoreRecord: true),

            TextInput::make('firstname')
                ->label('Prénom')
                ->required()
                ->maxLength(100),

            TextInput::make('lastname')
                ->label('Nom')
                ->required()
                ->maxLength(100),

            TextInput::make('phone')
                ->label('Téléphone')
                ->tel()
                ->maxLength(30),

            TextInput::make('credit_limit_fcfa')
                ->label('Plafond crédit (FCFA)')
                ->numeric()
                ->default(0)
                ->required(),

            Select::make('operator_id')
                ->label('Opérateur')
                ->options(
                    User::where('role', 'operator')
                        ->pluck('name', 'id')
                )
                ->searchable()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('identifier')
                    ->label('Identifiant')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('firstname')
                    ->label('Prénom')
                    ->searchable(),

                TextColumn::make('lastname')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('Téléphone'),

                TextColumn::make('credit_limit_fcfa')
                    ->label('Plafond (FCFA)')
                    ->numeric(thousandsSeparator: ' ')
                    ->sortable(),

                TextColumn::make('operator.name')
                    ->label('Opérateur')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('operator_id')
                    ->label('Opérateur')
                    ->relationship('operator', 'name'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFarmers::route('/'),
            'create' => Pages\CreateFarmer::route('/create'),
            'edit'   => Pages\EditFarmer::route('/{record}/edit'),
        ];
    }
}
