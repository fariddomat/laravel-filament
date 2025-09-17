<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Invoice Details')
                    ->schema([
                        TextInput::make('invoice_number')
                            ->required()
                            ->unique()
                            ->maxLength(255),
                        Select::make('customer_id')
                            ->relationship('customer', 'email')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('project_id')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        DatePicker::make('issue_date')
                            ->native(false)
                            ->required(),
                        DatePicker::make('due_date')
                            ->native(false)
                            ->required(),
                        TextInput::make('total_amount')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        TextInput::make('paid_amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),
                        TextInput::make('currency')
                            ->default('USD')
                            ->required(),
                        TextInput::make('discount')
                            ->numeric()
                            ->prefix('$')
                            ->nullable(),
                        Select::make('tax_id')
                            ->relationship('tax', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Textarea::make('notes')
                            ->columnSpanFull()
                            ->nullable(),
                    ])
                    ->columns()
                    ->columnSpanFull(),
                Section::make('Invoice Items')
                    ->schema([
                        Repeater::make('items')
                            ->schema([
                                TextInput::make('description')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('quantity')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('unit_price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required(),
                                TextInput::make('total')
                                    ->numeric()
                                    ->prefix('$')
                                    ->disabled()
                                    ->dehydrated(false),
                            ])
                            ->columns(4)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Invoice Settings')
                    ->schema([
                        Select::make('status_id')
                            ->relationship('status', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Select::make('created_by')
                            ->relationship('createdBy', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        KeyValue::make('custom_fields')
                            ->nullable()
                            ->columnSpanFull(),
                        Toggle::make('is_visible_to_client')
                            ->default(true),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
