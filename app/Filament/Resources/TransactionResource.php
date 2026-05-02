<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Transactions';

    protected static ?string $modelLabel = 'Transaction';

    protected static ?string $pluralModelLabel = 'Transactions';

    protected static ?int $navigationSort = 4;

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

                TextColumn::make('operator.name')
                    ->label('Opérateur')
                    ->sortable(),

                TextColumn::make('total_fcfa')
                    ->label('Total (FCFA)')
                    ->numeric(thousandsSeparator: ' ')
                    ->sortable(),

                BadgeColumn::make('payment_method')
                    ->label('Paiement')
                    ->colors([
                        'success' => 'cash',
                        'warning' => 'credit',
                    ]),

                BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'success' => 'completed',
                        'warning' => 'pending',
                        'danger'  => 'cancelled',
                    ]),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('payment_method')
                    ->label('Mode paiement')
                    ->options([
                        'cash'   => 'Cash',
                        'credit' => 'Crédit',
                    ]),

                SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'completed' => 'Complétée',
                        'pending'   => 'En attente',
                        'cancelled' => 'Annulée',
                    ]),
            ])
            ->actions([
                DeleteAction::make(),
            ])
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
            'index' => Pages\ListTransactions::route('/'),
        ];
    }
}
