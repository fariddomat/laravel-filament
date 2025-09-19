<?php

namespace App\Filament\Resources\Notes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NotesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('noteable_type')
                    ->label('Attached To')
                    ->formatStateUsing(fn ($record) => class_basename($record->noteable_type) . ' #' . $record->noteable_id)
                    ->sortable(),
                TextColumn::make('content')
                    ->limit(100)
                    ->searchable(),
                TextColumn::make('visibility')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'internal' => 'gray',
                        'client' => 'blue',
                        default => 'gray'
                    }),
                TextColumn::make('author.name')
                    ->label('Author')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('visibility')
                    ->options([
                        'internal' => 'Internal',
                        'client' => 'Client',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
