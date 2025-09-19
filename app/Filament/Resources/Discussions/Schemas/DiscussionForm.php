<?php

namespace App\Filament\Resources\Discussions\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class DiscussionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Discussion Details')
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
                            // ->disabled(fn ($livewire) => $livewire instanceof \Filament\Resources\RelationManagers\RelationManager),
                        ])
                    ->columns(2),
                Section::make('Discussion Settings')
                    ->schema([
                        Select::make('visibility')
                            ->options([
                                'public' => 'Public',
                                'private' => 'Private',
                            ])
                            ->default('public')
                            ->required(),
                        TextInput::make('category')
                            ->nullable(),
                        Toggle::make('is_visible_to_client')
                            ->default(true),
                        KeyValue::make('custom_fields')
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
