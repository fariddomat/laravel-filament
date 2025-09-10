<?php

namespace App\Filament\Resources\PipelineStages\Tables;

use App\Models\PipelineStage;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PipelineStagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                IconColumn::make('is_default')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('position')
            ->reorderable('position')
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('Set Default')
                    ->icon('heroicon-o-star')
                    ->hidden(fn ($record) => $record->is_default)
                    ->requiresConfirmation(function (Action $action, $record) {
                        $action->modalDescription('Are you sure you want to set this as the default pipeline stage?');
                        $action->modalHeading('Set "' . $record->name . '" as Default');

                        return $action;
                    })
                    ->action(function (PipelineStage $record) {
                        PipelineStage::where('is_default', true)->update(['is_default' => false]);

                        $record->is_default = true;
                        $record->save();
                    }),
                EditAction::make(),
                DeleteAction::make()
                    ->action(function ($data, $record) {
                        if ($record->customers()->count() > 0) {
                            Notification::make()
                                ->danger()
                                ->title('Pipeline Stage is in use')
                                ->body('Pipeline Stage is in use by customers.')
                                ->send();

                            return;
                        }

                        Notification::make()
                            ->success()
                            ->title('Pipeline Stage deleted')
                            ->body('Pipeline Stage has been deleted.')
                            ->send();

                        $record->delete();
                    })
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
