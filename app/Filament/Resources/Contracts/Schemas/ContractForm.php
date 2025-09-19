<?php

namespace App\Filament\Resources\Contracts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContractForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contract Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('content')
                            ->nullable()
                            ->columnSpanFull(),
                        Select::make('customer_id')
                            ->relationship('customer', 'email')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default(function ($livewire) {
                                if ($livewire instanceof \Filament\Resources\RelationManagers\RelationManager) {
                                    if ($livewire->ownerRecord instanceof \App\Models\Customer) {
                                        return $livewire->ownerRecord->id;
                                    } elseif ($livewire->ownerRecord instanceof \App\Models\Project) {
                                        return $livewire->ownerRecord->customer_id;
                                    }
                                }
                                return null;
                            })
                            ->disabled(fn ($livewire) => $livewire instanceof \Filament\Resources\RelationManagers\RelationManager),
                        Select::make('project_id')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->default(function ($livewire) {
                                if ($livewire instanceof \Filament\Resources\RelationManagers\RelationManager && $livewire->ownerRecord instanceof \App\Models\Project) {
                                    return $livewire->ownerRecord->id;
                                }
                                return null;
                            })
                            ->disabled(fn ($livewire) => $livewire instanceof \Filament\Resources\RelationManagers\RelationManager),
                        Select::make('quote_id')
                            ->relationship('quote', 'id')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Select::make('contract_template_id')
                            ->relationship('template', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Select::make('status_id')
                            ->relationship('status', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->columns(2),
                Section::make('Contract Settings')
                    ->schema([
                        DatePicker::make('start_date')
                            ->native(false)
                            ->nullable(),
                        DatePicker::make('end_date')
                            ->native(false)
                            ->nullable(),
                        TextInput::make('value')
                            ->numeric()
                            ->prefix('$')
                            ->nullable(),
                        TextInput::make('currency')
                            ->default('USD')
                            ->maxLength(3),
                        Toggle::make('is_signed')
                            ->default(false),
                        DatePicker::make('signed_at')
                            ->native(false)
                            ->nullable(),
                        Select::make('signed_by')
                            ->relationship('signedBy', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        TextInput::make('signature_path')
                            ->nullable()
                            ->maxLength(255),
                        Toggle::make('is_visible_to_client')
                            ->default(false),
                        Textarea::make('notes')
                            ->nullable()
                            ->columnSpanFull(),
                        KeyValue::make('custom_fields')
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
