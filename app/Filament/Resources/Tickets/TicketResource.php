<?php

namespace App\Filament\Resources\Tickets;

use App\Filament\Resources\Tickets\Pages\CreateTicket;
use App\Filament\Resources\Tickets\Pages\EditTicket;
use App\Filament\Resources\Tickets\Pages\ListTickets;
use App\Filament\Resources\Tickets\Pages\ViewTicket;
use App\Filament\Resources\Tickets\Schemas\TicketForm;
use App\Filament\Resources\Tickets\Schemas\TicketInfolist;
use App\Filament\Resources\Tickets\Tables\TicketsTable;
use App\Models\Ticket;
use BackedEnum;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Ticket;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Ticket Management')
                    ->tabs([
                        Tabs\Tab::make('Ticket Details')
                            ->schema(TicketForm::configure($schema)->getComponents()),
                        Tabs\Tab::make('Customer Feedback')
                            ->schema([
                                Repeater::make('customerFeedback')
                                    ->relationship('customerFeedback')
                                    ->schema([
                                        Select::make('customer_id')
                                            ->relationship('customer', 'email')
                                            ->default(fn ($livewire) => $livewire instanceof \Filament\Resources\RelationManagers\RelationManager
                                                ? ($livewire->ownerRecord instanceof \App\Models\Customer
                                                    ? $livewire->ownerRecord->id
                                                    : ($livewire->ownerRecord instanceof \App\Models\Project
                                                        ? $livewire->ownerRecord->customer_id
                                                        : null))
                                                : null)
                                            ->disabled(fn ($livewire) => $livewire instanceof \Filament\Resources\RelationManagers\RelationManager)
                                            ->dehydrated(true)
                                            ->required(),
                                        Select::make('project_id')
                                            ->relationship('project', 'name')
                                            ->default(fn ($livewire) => $livewire instanceof \Filament\Resources\RelationManagers\RelationManager
                                                && $livewire->ownerRecord instanceof \App\Models\Project
                                                ? $livewire->ownerRecord->id
                                                : null)
                                            ->disabled(fn ($livewire) => $livewire instanceof \Filament\Resources\RelationManagers\RelationManager)
                                            ->nullable(),
                                        Select::make('ticket_id')
                                            ->relationship('ticket', 'subject')
                                            ->default(fn ($livewire) => $livewire->record ? $livewire->record->id : null)
                                            ->disabled()
                                            ->dehydrated(true)
                                            ->required(),
                                        TextInput::make('rating')
                                            ->numeric()
                                            ->required()
                                            ->minValue(1)
                                            ->maxValue(5),
                                        Textarea::make('comments')
                                            ->columnSpanFull()
                                            ->nullable(),
                                        Select::make('type')
                                            ->options([
                                                'positive' => 'Positive',
                                                'neutral' => 'Neutral',
                                                'negative' => 'Negative',
                                            ])
                                            ->nullable(),
                                        Select::make('status_id')
                                            ->relationship('status', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->nullable(),
                                        KeyValue::make('custom_fields')
                                            ->nullable()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                        $data['created_by'] = auth()->id();
                                        return $data;
                                    })
                                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data, $record): array {
                                        if (!auth()->user()->hasRole('super_admin') && auth()->id() !== $record->created_by) {
                                            \Filament\Notifications\Notification::make()
                                                ->title('Unauthorized')
                                                ->body('You can only edit your own feedback.')
                                                ->danger()
                                                ->send();
                                            return [];
                                        }
                                        return $data;
                                    }),
                            ]),
                        Tabs\Tab::make('Ticket Replies')
                            ->schema([
                                Repeater::make('ticketReplies')
                                    ->relationship('ticketReplies')
                                    ->schema([
                                        Select::make('ticket_id')
                                            ->relationship('ticket', 'subject')
                                            ->default(fn ($livewire) => $livewire->record ? $livewire->record->id : null)
                                            ->disabled()
                                            ->dehydrated(true)
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
                                        KeyValue::make('custom_fields')
                                            ->nullable()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                        $data['user_id'] = auth()->id();
                                        if (!auth()->user()->hasRole('super_admin') && $this->ownerRecord && !$this->ownerRecord->is_visible_to_client) {
                                            $data['is_internal'] = true;
                                            $data['is_client_reply'] = false;
                                        }
                                        return $data;
                                    })
                                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data, $record): array {
                                        if (!auth()->user()->hasRole('super_admin') && auth()->id() !== $record->user_id) {
                                            \Filament\Notifications\Notification::make()
                                                ->title('Unauthorized')
                                                ->body('You can only edit your own replies.')
                                                ->danger()
                                                ->send();
                                            return [];
                                        }
                                        if (!auth()->user()->hasRole('super_admin') && $this->ownerRecord && !$this->ownerRecord->is_visible_to_client) {
                                            $data['is_internal'] = true;
                                            $data['is_client_reply'] = false;
                                        }
                                        return $data;
                                    }),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TicketInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TicketsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTickets::route('/'),
            'create' => CreateTicket::route('/create'),
            // 'view' => ViewTicket::route('/{record}'),
            'edit' => EditTicket::route('/{record}/edit'),
        ];
    }
}
