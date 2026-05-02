<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\DebtResource\Pages;
use App\Models\Debt;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DebtResource extends Resource
{
    protected static ?string $model = Debt::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Dettes';

    protected static ?string $modelLabel = 'Dette';

    protected static ?string $pluralModelLabel = 'Dettes';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('farmer.firstname')
                    ->label('Agriculteur')
                    ->formatStateUsing(fn ($record) => $record->farmer->full_name ?? '—')
                    ->searchable(),

                TextColumn::make('original_amount_fcfa')
                    ->label('Montant initial (FCFA)')
                    ->numeric(thousandsSeparator: ' ')
                    ->sortable(),

                TextColumn::make('remaining_amount_fcfa')
                    ->label('Restant (FCFA)')
                    ->numeric(thousandsSeparator: ' ')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'success' => 'paid',
                        'warning' => 'partial',
                        'danger'  => 'unpaid',
                    ]),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'unpaid'  => 'Non payée',
                        'partial' => 'Partielle',
                        'paid'    => 'Soldée',
                    ]),
            ])
            ->actions([])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDebts::route('/'),
        ];
    }
}
