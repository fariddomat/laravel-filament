<?php

namespace App\Filament\Resources\Taxes\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TaxForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tax Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('rate')
                            ->numeric()
                            ->suffix('%')
                            ->required(),
                        TextInput::make('country')
                            ->maxLength(255)
                            ->nullable(),
                        Toggle::make('is_active')
                            ->default(true),
                        Select::make('created_by')
                            ->relationship('createdBy', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        KeyValue::make('custom_fields')
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->columns()
                    ->columnSpanFull(),
            ]);
    }
}
