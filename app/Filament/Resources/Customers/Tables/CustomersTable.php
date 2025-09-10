<?php

namespace App\Filament\Resources\Customers\Tables;

use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Quotes\Pages\CreateQuote;
use App\Models\Customer;
use App\Models\PipelineStage;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->hidden(!auth()->user()), //->isAdmin()
                TextColumn::make('first_name')
                    ->label('Name'),
                TextColumn::make('first_name')
                    ->label('Name')
                    ->formatStateUsing(function ($record) {
                        $tagsList = view('customer.tagsList', ['tags' => $record->tags])->render();

                        return $record->first_name . ' ' . $record->last_name . ' ' . $tagsList;
                    })
                    ->html()
                    ->searchable(['first_name', 'last_name']),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('phone_number')
                    ->searchable(),
                TextColumn::make('leadSource.name'),
                TextColumn::make('pipelineStage.name'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // TrashedFilter::make(),
            ])
            ->recordActions([ActionGroup::make([

                    EditAction::make()->hidden(fn ($record) => $record->trashed()),
                    DeleteAction::make(),
                    RestoreAction::make(),
                    Action::make('Move to Stage')
                        ->hidden(fn ($record) => $record->trashed())
                        ->icon('heroicon-m-pencil-square')
                        ->form([
                            Select::make('pipeline_stage_id')
                                ->label('Status')
                                ->options(PipelineStage::pluck('name', 'id')->toArray())
                                ->default(function (Customer $record) {
                                    $currentPosition = $record->pipelineStage->position;
                                    return PipelineStage::where('position', '>', $currentPosition)->first()?->id;
                                }),
                            Textarea::make('notes')
                                ->label('Notes')
                        ])
                        ->action(function (Customer $customer, array $data): void {
                            $customer->pipeline_stage_id = $data['pipeline_stage_id'];
                            $customer->save();

                            $customer->pipelineStageLogs()->create([
                                'pipeline_stage_id' => $data['pipeline_stage_id'],
                                'notes' => $data['notes'],
                                'user_id' => auth()->id()
                            ]);

                            Notification::make()
                                ->title('Customer Pipeline Updated')
                                ->success()
                                ->send();
                        }),
                    Action::make('Add Task')
                        ->icon('heroicon-s-clipboard-document')
                        ->form([
                            RichEditor::make('description')
                                ->required(),
                            Select::make('user_id')
                                ->preload()
                                ->searchable()
                                ->relationship('employee', 'name'),
                            DatePicker::make('due_date')
                                ->native(false),

                        ])
                        ->action(function (Customer $customer, array $data) {
                            $customer->tasks()->create($data);

                            Notification::make()
                                ->title('Task created successfully')
                                ->success()
                                ->send();
                        }),
                    Action::make('Create Quote')
                        ->icon('heroicon-m-book-open')
                        ->url(function ($record) {
                            return CreateQuote::getUrl(['customer_id' => $record->id]);
                        })
                ])
            ])
            ->recordUrl(function ($record) {
                // If the record is trashed, return null
                if ($record->trashed()) {
                    // Null will disable the row click
                    return null;
                }

                // Otherwise, return the edit page URL
                return EditCustomer::getUrl([$record->id]);
            })
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
