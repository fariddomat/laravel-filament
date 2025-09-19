<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Filament\Resources\Discussions\DiscussionResource;
use App\Filament\Resources\Discussions\Schemas\DiscussionForm;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DiscussionsRelationManager extends RelationManager
{
    protected static string $relationship = 'discussions';

    public function form(Schema $form): Schema
    {
        return DiscussionForm::configure($form);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('subject')
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('visibility')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Created By')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_visible_to_client')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('project')
                    ->relationship('project', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['created_by'] = auth()->id();
                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->url(fn ($record) => DiscussionResource::getUrl('edit', ['record' => $record]))
                    ->openUrlInNewTab(false)
                    ->mutateFormDataUsing(function (array $data, $record): array {
                        if (!auth()->user()->hasRole('super_admin') && auth()->id() !== $record->created_by) {
                            \Filament\Notifications\Notification::make()
                                ->title('Unauthorized')
                                ->body('You can only edit your own discussions.')
                                ->danger()
                                ->send();
                            return [];
                        }
                        return $data;
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}
