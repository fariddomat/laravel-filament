<?php

namespace App\Filament\Resources\Notes\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NoteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Note Details')
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
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
