<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue as ComponentsKeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\KeyValue;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class TicketRepliesRelationManager extends RelationManager
{
    protected static string $relationship = 'ticketReplies';

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Select::make('ticket_id')
                    ->relationship('ticket', 'subject')
                    ->searchable()
                    ->preload()
                    ->required(),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('is_internal')
                    ->default(false)
                    ->disabled(fn () => !auth()->user()->hasRole('super_admin')),
                Toggle::make('is_client_reply')
                    ->default(false)
                    ->disabled(fn () => !auth()->user()->hasRole('super_admin')),
                ComponentsKeyValue::make('custom_fields')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->columns([
                Tables\Columns\TextColumn::make('content')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('ticket.subject')
                    ->label('Ticket')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_internal')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_client_reply')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('ticket')
                    ->relationship('ticket', 'subject')
                    ->multiple()
                    ->preload(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        if (!auth()->user()->hasRole('super_admin') && !$this->ownerRecord->is_visible_to_client) {
                            $data['is_internal'] = true;
                            $data['is_client_reply'] = false;
                        }
                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateFormDataUsing(function (array $data, $record): array {
                        if (!auth()->user()->hasRole('super_admin') && auth()->id() !== $record->user_id) {
                            \Filament\Notifications\Notification::make()
                                ->title('Unauthorized')
                                ->body('You can only edit your own replies.')
                                ->danger()
                                ->send();
                            return [];
                        }
                        if (!auth()->user()->hasRole('super_admin') && !$this->ownerRecord->is_visible_to_client) {
                            $data['is_internal'] = true;
                            $data['is_client_reply'] = false;
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
