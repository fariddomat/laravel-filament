<?php

namespace App\Filament\Resources\Sales\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sale Details')
                    ->schema([
                        TextInput::make('sale_number')
                            ->required()
                            ->unique()
                            ->maxLength(255),
                        DatePicker::make('sale_date')
                            ->native(false)
                            ->required(),
                        TextInput::make('total_amount')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        TextInput::make('currency')
                            ->default('USD')
                            ->maxLength(3),
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
                        Select::make('contract_id')
                            ->relationship('contract', 'title')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Select::make('quote_id')
                            ->relationship('quote', 'id')
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
                Section::make('Sale Settings')
                    ->schema([
                        TextInput::make('commission')
                            ->numeric()
                            ->prefix('$')
                            ->nullable(),
                        Select::make('commission_user_id')
                            ->relationship('commissionUser', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        TextInput::make('discount')
                            ->numeric()
                            ->prefix('$')
                            ->nullable(),
                        Select::make('tax_id')
                            ->relationship('tax', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
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
