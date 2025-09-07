<?php

namespace App\Filament\Resources\Blogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BlogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->getStateUsing(function ($record, $livewire) {
                    $locale = $livewire->activeLocale ?? app()->getLocale();
                    $translation = $record->getTranslation('name', $locale);
                    return $translation ?: $record->getTranslation('name', config('app.fallback_locale')) ?: 'N/A';
                }),
                TextColumn::make('description')->searchable()->limit(50)
                    ->tooltip(function ($record) {
                        return $record->description;
                    })
                    ->getStateUsing(function ($record, $livewire) {
                        $locale = $livewire->activeLocale ?? app()->getLocale();
                        $translation = $record->getTranslation('description', $locale);
                        return $translation ?: $record->getTranslation('description', config('app.fallback_locale')) ?: 'N/A';
                    }),
                TextColumn::make('publish_date')
                    ->date()
                    ->sortable(),
                IconColumn::make('show')
                    ->boolean(),
                TextColumn::make('state'),
                ImageColumn::make('image'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])->headerActions([
                // ...
                // \LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher::make(),
            ]);
    }
}
