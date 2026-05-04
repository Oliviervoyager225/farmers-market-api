<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Catégories';

    protected static ?string $modelLabel = 'Catégorie';

    protected static ?string $pluralModelLabel = 'Catégories';

    protected static ?int $navigationSort = 1;

    private static array $iconOptions = [
        '🌱' => '🌱   Plante / Croissance',
        '🌾' => '🌾   Céréales',
        '🌿' => '🌿   Herbes',
        '🍀' => '🍀   Légumineuses',
        '🌻' => '🌻   Tournesol',
        '🫘' => '🫘   Légumes secs',
        '🌽' => '🌽   Maïs',
        '🍅' => '🍅   Maraîchage',
        '🥬' => '🥬   Légumes feuilles',
        '🥑' => '🥑   Fruits tropicaux',
        '🍫' => '🍫   Cacao',
        '☕' => '☕   Café',
        '🌴' => '🌴   Palmier',
        '🎋' => '🎋   Bambou / Autres',
        '🧪' => '🧪   Chimie / Synthèse',
        '🛡️' => '🛡️   Protection cultures',
        '🐛' => '🐛   Insecticides',
        '🍄' => '🍄   Fongicides',
        '🪨' => '🪨   Amendements sol',
        '⚗️' => '⚗️   Biostimulants',
        '💧' => '💧   Irrigation',
        '♻️' => '♻️   Bio / Organique',
        '🌡️' => '🌡️   Météo / Climat',
        '🐄' => '🐄   Élevage bovin',
        '🐓' => '🐓   Volaille',
        '🐟' => '🐟   Aquaculture',
        '🔬' => '🔬   Analyses / Tests',
        '🚜' => '🚜   Matériel agricole',
        '📦' => '📦   Emballage',
        '⭐' => '⭐   Spécial / Premium',
    ];

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('icon')
                ->label('Icône')
                ->options(self::$iconOptions)
                ->searchable()
                ->nullable()
                ->placeholder('Choisir une icône…')
                ->columnSpan(1),

            TextInput::make('name')
                ->label('Nom')
                ->required()
                ->maxLength(100)
                ->unique(ignoreRecord: true)
                ->placeholder('Ex: Engrais, Insecticides…')
                ->helperText(fn () => 'Existants : ' . Category::orderBy('name')->pluck('name')->implode(', '))
                ->columnSpan(1),

            Textarea::make('description')
                ->label('Description')
                ->rows(2)
                ->nullable()
                ->maxLength(500)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('icon')
                    ->label('')
                    ->width('48px'),

                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('products_count')
                    ->label('Produits')
                    ->getStateUsing(function (Category $record): int {
                        $direct = $record->products()->count();
                        $fromChildren = $record->children()
                            ->withCount('products')
                            ->get()
                            ->sum('products_count');

                        return $direct + $fromChildren;
                    })
                    ->badge()
                    ->color('success'),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(60)
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Créée le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
