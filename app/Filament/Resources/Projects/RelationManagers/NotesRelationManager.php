<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Hidden::make('created_by')
                            ->default(auth()->id()),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                Select::make('visibility')
                    ->options([
                        'internal' => 'Internal',
                        'client' => 'Client',
                    ])
                    ->default('internal')
                    ->required(),
                KeyValue::make('custom_fields')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->columns([
                Tables\Columns\TextColumn::make('content')
                    ->limit(100)
                    ->searchable(),
                Tables\Columns\TextColumn::make('visibility')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'internal' => 'gray',
                        'client' => 'blue',
                        default => 'gray'
                    }),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Author')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('visibility')
                    ->options([
                        'internal' => 'Internal',
                        'client' => 'Client',
                    ]),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}
