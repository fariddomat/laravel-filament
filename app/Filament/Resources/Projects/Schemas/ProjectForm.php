<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Models\Status;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Project Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('customer_id')
                            ->relationship('customer', 'email')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Textarea::make('description')
                            ->columnSpanFull()
                            ->nullable(),
                        DatePicker::make('start_date')
                            ->native(false)
                            ->nullable(),
                        DatePicker::make('deadline')
                            ->native(false)
                            ->nullable(),
                        TextInput::make('budget')
                            ->numeric()
                            ->prefix('$')
                            ->nullable(),
                        TextInput::make('total_billed')
                            ->numeric()
                            ->prefix('$')
                            ->nullable(),
                    ])
                    ->columns()
                    ->columnSpanFull(),
                Section::make('Project Settings')
                    ->schema([
                        Select::make('created_by')
                            ->relationship('createdBy', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Select::make('status_id')
                            ->relationship('status', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Select::make('billing_type')
                            ->options([
                                'fixed' => 'Fixed',
                                'hourly' => 'Hourly',
                                'subscription' => 'Subscription',
                            ])
                            ->nullable(),
                        TextInput::make('hourly_rate')
                            ->numeric()
                            ->prefix('$')
                            ->nullable(),
                        Toggle::make('is_visible_to_client')
                            ->default(false),
                        Toggle::make('allow_client_comments')
                            ->default(false),
                        Select::make('members')
                            ->relationship('members', 'name')
                            ->multiple()
                            ->preload()
                            ->nullable(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Custom Fields')
                    ->schema([
                        KeyValue::make('custom_fields')
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Documents')
                    ->visibleOn(['edit', 'view'])
                    ->schema([
                        Repeater::make('documents')
                            ->relationship('documents')
                            ->hiddenLabel()
                            ->reorderable(false)
                            ->addActionLabel('Add Document')
                            ->schema([
                                FileUpload::make('file_path')
                                    ->directory('project-documents')
                                    ->required(),
                                Textarea::make('comments')
                                    ->nullable(),
                            ])
                            ->columns()
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
