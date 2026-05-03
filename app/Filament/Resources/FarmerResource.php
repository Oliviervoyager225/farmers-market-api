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
            \Filament\Schemas\Components\Section::make('Profile Photo')
                ->schema([
                    \Filament\Schemas\Components\Grid::make(2)
                        ->schema([
                            \Filament\Schemas\Components\View::make('filament.components.profile-photo-placeholder')
                                ->columnSpan(1),
                        ])
                ]),

            \Filament\Schemas\Components\Section::make('Personal Information')
                ->schema([
                    \Filament\Schemas\Components\Grid::make(2)
                        ->schema([
                            TextInput::make('firstname')
                                ->label('First Name *')
                                ->required()
                                ->live(onBlur: true)
                                ->maxLength(100),
                            TextInput::make('lastname')
                                ->label('Last Name *')
                                ->required()
                                ->live(onBlur: true)
                                ->maxLength(100),
                        ]),
                    
                    \Filament\Schemas\Components\Grid::make(2)
                        ->schema([
                            TextInput::make('phone')
                                ->label('Phone *')
                                ->tel()
                                ->required()
                                ->maxLength(30),
                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->maxLength(255),
                        ]),

                    \Filament\Schemas\Components\Grid::make(2)
                        ->schema([
                            TextInput::make('state')
                                ->label('State')
                                ->maxLength(100),
                            TextInput::make('city')
                                ->label('City')
                                ->maxLength(100),
                        ]),

                    \Filament\Schemas\Components\Grid::make(2)
                        ->schema([
                            TextInput::make('identifier')
                                ->label('Farmer ID / Identifier *')
                                ->required()
                                ->maxLength(50)
                                ->unique(ignoreRecord: true),
                        ]),

                    TextInput::make('address')
                        ->label('Address')
                        ->maxLength(255),

                    \Filament\Forms\Components\Textarea::make('bio')
                        ->label('Bio')
                        ->placeholder('Brief description about this farmer...')
                        ->rows(3),
                ]),

            \Filament\Schemas\Components\Section::make('Catégorie de production')
                ->schema([
                    \Filament\Forms\Components\CheckboxList::make('categories')
                        ->label('Sélectionnez une ou plusieurs catégories')
                        ->options([
                            'vegetale' => '🌱  Production végétale — Cultures, fruits, légumes, arboriculture',
                            'animale'  => '🐄  Production animale — Élevage, aviculture, pisciculture',
                        ])
                        ->live()
                        ->columns(1),
                ]),

            \Filament\Schemas\Components\Section::make('Spécialités / Métier')
                ->schema([
                    \Filament\Forms\Components\CheckboxList::make('specialties')
                        ->label('Métiers exercés')
                        ->options([
                            'Agriculteur'    => 'Agriculteur',
                            'Maraîcher'      => 'Maraîcher',
                            'Arboriculteur'  => 'Arboriculteur',
                            'Riziculteur'    => 'Riziculteur',
                            'Cacaoculteur'   => 'Cacaoculteur',
                            'Caféiculteur'   => 'Caféiculteur',
                            'Horticulteur'   => 'Horticulteur',
                            'Pépiniériste'   => 'Pépiniériste',
                            'Éleveur'        => 'Éleveur',
                            'Aviculteur'     => 'Aviculteur',
                            'Boviniculteur'  => 'Boviniculteur',
                            'Porciniculteur' => 'Porciniculteur',
                            'Pisciculteur'   => 'Pisciculteur',
                            'Apiculteur'     => 'Apiculteur',
                        ])
                        ->columns(4),
                ]),

            \Filament\Schemas\Components\Section::make('Farm Details')
                ->schema([
                    \Filament\Schemas\Components\Grid::make(2)
                        ->schema([
                            TextInput::make('farm_size')
                                ->label('Farm Size (acres)')
                                ->numeric(),
                            TextInput::make('experience')
                                ->label('Experience (years)')
                                ->numeric(),
                        ]),
                    
                    \Filament\Schemas\Components\Grid::make(2)
                        ->schema([
                            Select::make('certification')
                                ->label('Certification')
                                ->options([
                                    'Bio Certified' => 'Bio Certified',
                                    'Fairtrade' => 'Fairtrade',
                                    'FSSAI' => 'FSSAI',
                                    'Organic India' => 'Organic India',
                                    'None' => 'None',
                                ]),
                            Select::make('primary_market')
                                ->label('Primary Market')
                                ->options([
                                    'Local Market' => 'Local Market',
                                    'State Market' => 'State Market',
                                    'National Market' => 'National Market',
                                    'Export' => 'Export',
                                ]),
                        ]),

                    TextInput::make('credit_limit_fcfa')
                        ->label('Limite de Crédit (FCFA) *')
                        ->numeric()
                        ->default(0)
                        ->required(),
                ]),

            \Filament\Schemas\Components\Section::make('Système')
                ->schema([
                    Select::make('operator_id')
                        ->label('Opérateur')
                        ->options(
                            User::where('role', 'operator')->pluck('name', 'id')
                        )
                        ->searchable()
                        ->required()
                        ->default(auth()->id()),
                ])->collapsed(),
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
