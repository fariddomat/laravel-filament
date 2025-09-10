<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\CustomField;
use App\Models\PipelineStage;
use App\Models\Role;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Employee Information')
                    ->schema([
                        Select::make('employee_id')
                            ->options(User::role('Employee')->pluck('name', 'id')->toArray())
                            ->searchable()
                            ->label('Employee')
                    ])
                    ->hidden(fn () => !auth()->user()->hasRole('super_admin')) // Updated to use Spatie role
                    ->columnSpanFull(), // Make full-width
                Section::make('Customer Details')
                    ->schema([
                        TextInput::make('first_name')
                            ->maxLength(255),
                        TextInput::make('last_name')
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('phone_number')
                            ->maxLength(255),
                        Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columns() // Keep internal 2-column layout for fields
                    ->columnSpanFull(), // Make section full-width
                Section::make('Lead Details')
                    ->schema([
                        Select::make('lead_source_id')
                            ->relationship('leadSource', 'name'),
                        Select::make('tags')
                            ->relationship('tags', 'name')
                            ->multiple(),
                        Select::make('pipeline_stage_id')
                            ->relationship('pipelineStage', 'name', function ($query) {
                                $query->orderBy('position', 'asc');
                            })
                            ->default(PipelineStage::where('is_default', true)->first()?->id)
                    ])
                    ->columns(3) // Keep internal 3-column layout for fields
                    ->columnSpanFull(), // Make section full-width
                Section::make('Documents')
                    ->visibleOn('edit')
                    ->schema([
                        Repeater::make('documents')
                            ->relationship('documents')
                            ->hiddenLabel()
                            ->reorderable(false)
                            ->addActionLabel('Add Document')
                            ->schema([
                                FileUpload::make('file_path')
                                    ->required(),
                                Textarea::make('comments'),
                            ])
                            ->columns() // Internal layout
                    ])
                    ->columnSpanFull(), // Make section full-width
                Section::make('Additional fields')
                    ->schema([
                        Repeater::make('fields')
                            ->hiddenLabel()
                            ->relationship('customFields')
                            ->schema([
                                Select::make('custom_field_id')
                                    ->label('Field Type')
                                    ->options(CustomField::pluck('name', 'id')->toArray())
                                    ->disableOptionWhen(function ($value, $state, Get $get) {
                                        return collect($get('../*.custom_field_id'))
                                            ->reject(fn ($id) => $id === $state)
                                            ->filter()
                                            ->contains($value);
                                    })
                                    ->required()
                                    ->searchable()
                                    ->live(),
                                TextInput::make('value')
                                    ->required()
                            ])
                            ->addActionLabel('Add another Field')
                            ->columns() // Internal layout
                    ])
                    ->columnSpanFull(), // Make section full-width
            ]);
    }
}
