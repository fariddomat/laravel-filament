<?php

namespace App\Filament\Resources\Quotes\Schemas;

use App\Models\Customer;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class QuoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Selection')
                    ->schema([
                        Select::make('customer_id')
                            ->relationship('customer', null)
                            ->getOptionLabelFromRecordUsing(fn (Customer $record) => $record->first_name . ' ' . $record->last_name)
                            ->searchable(['first_name', 'last_name'])
                            ->preload()
                            ->default(request()->has('customer_id') ? request()->get('customer_id') : null)
                            ->required()
                            ->label('Customer'),
                    ])
                    ->columnSpanFull()
                    ->hidden(fn () => !auth()->user()->hasRole('super_admin')),
                Section::make('Quote Items')
                    ->schema([
                        Repeater::make('quoteProducts')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->disableOptionWhen(function ($value, $state, Get $get) {
                                        return collect($get('../*.product_id'))
                                            ->reject(fn ($id) => $id === $state)
                                            ->filter()
                                            ->contains($value);
                                    })
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, $livewire) {
                                        $product = Product::find($get('product_id'));
                                        $set('price', $product ? number_format($product->price, 2, '.', '') : null);
                                        self::updateTotals($get, $livewire);
                                    })
                                    ->required()
                                    ->label('Product'),
                                TextInput::make('price')
                                    ->required()
                                    ->numeric()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, $livewire) {
                                        self::updateTotals($get, $livewire);
                                    })
                                    ->prefix('$')
                                    ->minValue(0),
                                TextInput::make('quantity')
                                    ->integer()
                                    ->default(1)
                                    ->required()
                                    ->live()
                                    ->minValue(1)
                                    ->label('Quantity'),
                            ])
                            ->live()
                            ->afterStateUpdated(fn (Get $get, $livewire) => self::updateTotals($get, $livewire))
                            ->afterStateHydrated(fn (Get $get, $livewire) => self::updateTotals($get, $livewire))
                            ->deleteAction(
                                fn (Action $action) => $action->after(fn (Get $get, $livewire) => self::updateTotals($get, $livewire))
                            )
                            ->reorderable(false)
                            ->columns(3)
                            ->addActionLabel('Add Product'),
                    ])
                    ->columnSpanFull(),
                Section::make('Totals')
                    ->schema([
                        TextInput::make('subtotal')
                            ->numeric()
                            ->readOnly()
                            ->prefix('$')
                            ->default(0.00)
                            ->formatStateUsing(fn ($state) => number_format(floatval($state), 2, '.', '')),
                        TextInput::make('taxes')
                            ->suffix('%')
                            ->required()
                            ->numeric()
                            ->default(20)
                            ->live(true)
                            ->afterStateUpdated(fn (Get $get, $livewire) => self::updateTotals($get, $livewire))
                            ->minValue(0)
                            ->maxValue(100),
                        TextInput::make('total')
                            ->numeric()
                            ->readOnly()
                            ->prefix('$')
                            ->default(0.00)
                            ->formatStateUsing(fn ($state) => number_format(floatval($state), 2, '.', '')),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function updateTotals(Get $get, $livewire): void
    {
        // Use 'data' as default state path; safely handle if getFormStatePath is unavailable
        $statePath = method_exists($livewire, 'getFormStatePath') ? $livewire->getFormStatePath() : 'data';

        $products = data_get($livewire, $statePath . '.quoteProducts', []);
        $selectedProducts = collect($products)
            ->filter(fn ($item) => !empty($item['product_id']) && !empty($item['quantity']));

        $subtotal = $selectedProducts->reduce(function ($carry, $product) {
            $price = floatval($product['price'] ?? 0);
            $quantity = intval($product['quantity'] ?? 0);
            return $carry + ($price * $quantity);
        }, 0);

        $taxRate = floatval(data_get($livewire, $statePath . '.taxes', 0));
        $total = $subtotal + ($subtotal * ($taxRate / 100));

        data_set($livewire, $statePath . '.subtotal', number_format($subtotal, 2, '.', ''));
        data_set($livewire, $statePath . '.total', number_format($total, 2, '.', ''));
    }
}
