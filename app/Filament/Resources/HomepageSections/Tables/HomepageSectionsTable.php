<?php

namespace App\Filament\Resources\HomepageSections\Tables;

use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class HomepageSectionsTable
{
    /**
     * Configure the homepage section listing.
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('key')
                    ->label('Section')
                    ->formatStateUsing(
                        fn (mixed $state): string => Str::of(
                            $state instanceof BackedEnum
                                ? (string) $state->value
                                : (string) $state
                        )->headline()->toString()
                    )
                    ->badge(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->sortable(),

                IconColumn::make('is_visible')
                    ->label('Visible')
                    ->boolean(),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
