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
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Concerns\CanBeHidden;
use Filament\Schemas\Components\Utilities\Get;
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

            // ── Profile Photo ─────────────────────────────────────────────────
            \Filament\Schemas\Components\Section::make('Profile Photo')
                ->schema([
                    \Filament\Schemas\Components\View::make('filament.components.profile-photo-placeholder'),
                ]),

            // ── Personal Information ──────────────────────────────────────────
            \Filament\Schemas\Components\Section::make('Personal Information')
                ->schema([
                    \Filament\Schemas\Components\Grid::make(2)
                        ->schema([
                            TextInput::make('firstname')
                                ->label('First Name')
                                ->required()
                                ->live(onBlur: true)
                                ->placeholder('John')
                                ->maxLength(100),
                            TextInput::make('lastname')
                                ->label('Last Name')
                                ->required()
                                ->live(onBlur: true)
                                ->placeholder('Doe')
                                ->maxLength(100),
                        ]),

                    \Filament\Schemas\Components\Grid::make(2)
                        ->schema([
                            TextInput::make('phone')
                                ->label('Phone')
                                ->tel()
                                ->required()
                                ->placeholder('+225 07 00 00 00')
                                ->maxLength(30),
                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->placeholder('farmer@example.com')
                                ->maxLength(255),
                        ]),

                    \Filament\Schemas\Components\Grid::make(2)
                        ->schema([
                            TextInput::make('state')
                                ->label('State / Région')
                                ->placeholder('Abidjan')
                                ->maxLength(100),
                            TextInput::make('city')
                                ->label('City / Ville')
                                ->placeholder('Cocody')
                                ->maxLength(100),
                        ]),

                    // Identifier : affiché mais disabled (auto-généré)
                    TextInput::make('identifier')
                        ->label('Farmer ID / Identifier')
                        ->disabled()
                        ->dehydrated(false)
                        ->placeholder('AGR-CI-001')
                        ->maxLength(50),

                    TextInput::make('address')
                        ->label('Address')
                        ->placeholder('Village, Commune, Département...')
                        ->maxLength(255),

                    \Filament\Forms\Components\Textarea::make('bio')
                        ->label('Bio')
                        ->placeholder('Brief description about this farmer...')
                        ->rows(4),
                ]),

            // ── Production Category ───────────────────────────────────────────
            \Filament\Schemas\Components\Section::make('Production Category')
                ->schema([
                    ToggleButtons::make('categories')
                        ->label('')
                        ->options([
                            'vegetale' => "🌱 Production végétale\nCrops, fruits, vegetables",
                            'animale'  => "🐄 Production animale\nLivestock, poultry, fishery",
                        ])
                        ->colors([
                            'vegetale' => 'success',
                            'animale'  => 'success',
                        ])
                        ->multiple()
                        ->inline()
                        ->live(),
                ]),

            // ── Specialty / Métier ────────────────────────────────────────────
            \Filament\Schemas\Components\Section::make('Specialty / Métier')
                ->schema([
                    // Spécialités végétales — visible si 'vegetale' sélectionné
                    ToggleButtons::make('specialties_veg')
                        ->label('Vegetale')
                        ->options([
                            'Agriculteur'   => 'Agriculteur',
                            'Maraîcher'     => 'Maraîcher',
                            'Arboriculteur' => 'Arboriculteur',
                            'Riziculteur'   => 'Riziculteur',
                            'Cacaoculteur'  => 'Cacaoculteur',
                            'Caféiculteur'  => 'Caféiculteur',
                            'Horticulteur'  => 'Horticulteur',
                            'Pépiniériste'  => 'Pépiniériste',
                        ])
                        ->multiple()
                        ->inline()
                        ->colors([
                            'Agriculteur'   => 'gray',
                            'Maraîcher'     => 'gray',
                            'Arboriculteur' => 'gray',
                            'Riziculteur'   => 'gray',
                            'Cacaoculteur'  => 'gray',
                            'Caféiculteur'  => 'gray',
                            'Horticulteur'  => 'gray',
                            'Pépiniériste'  => 'gray',
                        ])
                        ->visible(fn (Get $get): bool => in_array('vegetale', (array) $get('categories'))),

                    // Spécialités animales — visible si 'animale' sélectionné
                    ToggleButtons::make('specialties_ani')
                        ->label('Animale')
                        ->options([
                            'Éleveur'        => 'Éleveur',
                            'Aviculteur'     => 'Aviculteur',
                            'Boviniculteur'  => 'Boviniculteur',
                            'Porciniculteur' => 'Porciniculteur',
                            'Pisciculteur'   => 'Pisciculteur',
                            'Apiculteur'     => 'Apiculteur',
                        ])
                        ->multiple()
                        ->inline()
                        ->colors([
                            'Éleveur'        => 'gray',
                            'Aviculteur'     => 'gray',
                            'Boviniculteur'  => 'gray',
                            'Porciniculteur' => 'gray',
                            'Pisciculteur'   => 'gray',
                            'Apiculteur'     => 'gray',
                        ])
                        ->visible(fn (Get $get): bool => in_array('animale', (array) $get('categories'))),

                    // Message par défaut quand rien n'est sélectionné
                    \Filament\Schemas\Components\View::make('filament.components.specialty-hint')
                        ->visible(fn (Get $get): bool => empty((array) $get('categories'))),
                ]),

            // ── Crédit ────────────────────────────────────────────────────────
            \Filament\Schemas\Components\Section::make('Crédit')
                ->schema([
                    TextInput::make('credit_limit_fcfa')
                        ->label('Limite de Crédit (FCFA)')
                        ->required()
                        ->numeric()
                        ->default(50000)
                        ->placeholder('50000'),
                ]),

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
