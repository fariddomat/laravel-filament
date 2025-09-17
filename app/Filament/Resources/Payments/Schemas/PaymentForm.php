<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment Details')
                    ->schema([
                        TextInput::make('transaction_id')
                            ->unique()
                            ->maxLength(255)
                            ->nullable(),
                        Select::make('customer_id')
                            ->relationship('customer', 'email')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('invoice_id')
                            ->relationship('invoice', 'invoice_number')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Select::make('project_id')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        TextInput::make('amount')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        TextInput::make('currency')
                            ->default('USD')
                            ->required(),
                        TextInput::make('payment_method')
                            ->maxLength(255)
                            ->nullable(),
                        DateTimePicker::make('payment_date')
                            ->native(false)
                            ->required(),
                        Textarea::make('notes')
                            ->columnSpanFull()
                            ->nullable(),
                        TextInput::make('receipt_url')
                            ->url()
                            ->nullable(),
                    ])
                    ->columns()
                    ->columnSpanFull(),
                Section::make('Payment Settings')
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
                        Toggle::make('is_refunded')
                            ->default(false),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
