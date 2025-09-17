<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class PipelineStagesRelationManager extends RelationManager
{
    protected static string $relationship = 'pipelineStageLogs';

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Select::make('pipeline_stage_id')
                    ->relationship('pipelineStage', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Textarea::make('notes')
                    ->columnSpanFull()
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('pipeline_stage_id')
            ->columns([
                Tables\Columns\TextColumn::make('pipelineStage.name')
                    ->label('Pipeline Stage')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Assigned Employee')
                    ->sortable(),
                Tables\Columns\TextColumn::make('notes')
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('pipelineStage')
                    ->relationship('pipelineStage', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}
