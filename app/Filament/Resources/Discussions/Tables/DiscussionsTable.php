<?php

namespace App\Filament\Resources\Discussions\Tables;

use DeepCopy\Filter\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class DiscussionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.email')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('project.name')
                    ->label('Project')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('visibility')
                    ->sortable(),
                TextColumn::make('category')
                    ->sortable(),
                TextColumn::make('author.name')
                    ->label('Created By')
                    ->sortable(),
                IconColumn::make('is_visible_to_client')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('customer')
                    ->relationship('customer', 'email')
                    ->multiple()
                    ->preload(),
                SelectFilter::make('project')
                    ->relationship('project', 'name')
                    ->multiple()
                    ->preload(),
                ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
