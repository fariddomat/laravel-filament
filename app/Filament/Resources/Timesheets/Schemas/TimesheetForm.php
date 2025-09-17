<?php

namespace App\Filament\Resources\Timesheets\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TimesheetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Timesheet Details')
                    ->schema([
                        Select::make('project_id')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('task_id')
                            ->relationship('task', 'description')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        DateTimePicker::make('start_time')
                            ->native(false)
                            ->required(),
                        DateTimePicker::make('end_time')
                            ->native(false)
                            ->nullable(),
                        TextInput::make('hours')
                            ->numeric()
                            ->nullable(),
                        Textarea::make('description')
                            ->columnSpanFull()
                            ->nullable(),
                    ])
                    ->columns()
                    ->columnSpanFull(),
                Section::make('Timesheet Settings')
                    ->schema([
                        Select::make('created_by')
                            ->relationship('createdBy', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        KeyValue::make('custom_fields')
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
