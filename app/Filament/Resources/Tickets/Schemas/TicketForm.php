<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ticket Details')
                    ->schema([
                        TextInput::make('subject')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('content')
                            ->required()
                            ->columnSpanFull(),
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
                        Select::make('assigned_to')
                            ->relationship('assignee', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ])
                    ->columns(2),
                Section::make('Ticket Settings')
                    ->schema([
                        Select::make('status_id')
                            ->relationship('status', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('priority')
                            ->options([
                                1 => 'Low',
                                2 => 'Medium',
                                3 => 'High',
                            ])
                            ->default(1)
                            ->required(),
                        TextInput::make('category')
                            ->nullable(),
                        DateTimePicker::make('due_date')
                            ->native(false)
                            ->nullable(),
                        Toggle::make('is_visible_to_client')
                            ->default(true),
                        Toggle::make('allow_client_comments')
                            ->default(true),
                        KeyValue::make('custom_fields')
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
