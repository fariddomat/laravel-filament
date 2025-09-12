<?php

namespace App\Filament\Resources\CrmSettings\Tables;

use App\Models\Setting;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CrmSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payload.company_name')
                    ->label('Company Name')
                    ->getStateUsing(function ($record, $livewire) {
                        $locale = $livewire->activeLocale ?? app()->getLocale();
                        $translation = $record->payload['company_name'][$locale] ?? null;
                        return $translation ?: ($record->payload['company_name'][config('app.fallback_locale')] ?? 'N/A');
                    }),
                TextColumn::make('payload.description')
                    ->label('Description')
                    ->limit(50)  // Truncate for table display
                    ->getStateUsing(function ($record, $livewire) {
                        $locale = $livewire->activeLocale ?? app()->getLocale();
                        $translation = $record->payload['description'][$locale] ?? null;
                        return $translation ?: ($record->payload['description'][config('app.fallback_locale')] ?? 'N/A');
                    }),
                TextColumn::make('payload.email')
                    ->label('Email')
                    ->getStateUsing(fn (Setting $record) => $record->payload['email'] ?? 'N/A'),  // Non-translatable
                TextColumn::make('payload.currency')
                    ->label('Currency')
                    ->getStateUsing(fn (Setting $record) => $record->payload['currency'] ?? 'N/A'),  // Non-translatable
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
