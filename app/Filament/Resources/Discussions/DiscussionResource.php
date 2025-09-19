<?php

namespace App\Filament\Resources\Discussions;

use App\Filament\Resources\Discussions\Pages\CreateDiscussion;
use App\Filament\Resources\Discussions\Pages\EditDiscussion;
use App\Filament\Resources\Discussions\Pages\ListDiscussions;
use App\Filament\Resources\Discussions\Schemas\DiscussionForm;
use App\Filament\Resources\Discussions\Tables\DiscussionsTable;
use App\Models\Discussion;
use BackedEnum;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiscussionResource extends Resource
{
    protected static ?string $model = Discussion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Tabs::make('Discussion Management')
                    ->tabs([
                        Tabs\Tab::make('Discussion Details')
                            ->schema(DiscussionForm::configure($form)->getComponents()),
                        Tabs\Tab::make('Discussion Replies')
                            ->schema([
                                Repeater::make('replies')
                                    ->relationship('replies')
                                    ->schema([
                                        Select::make('discussion_id')
                                            ->relationship('discussion', 'subject')
                                            ->default(fn ($livewire) => $livewire->record ? $livewire->record->id : null)
                                            ->disabled()
                                            ->dehydrated(true)
                                            ->required(),
                                        Textarea::make('content')
                                            ->required()
                                            ->columnSpanFull(),
                                        Toggle::make('is_client_reply')
                                            ->default(false)
                                            ->disabled(fn () => !auth()->user()->hasRole('super_admin')),
                                        KeyValue::make('custom_fields')
                                            ->nullable()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                        $data['created_by'] = auth()->id();
                                        if (!auth()->user()->hasRole('super_admin') && $this->ownerRecord && !$this->ownerRecord->is_visible_to_client) {
                                            $data['is_client_reply'] = false;
                                        }
                                        return $data;
                                    })
                                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data, $record): array {
                                        if (!auth()->user()->hasRole('super_admin') && auth()->id() !== $record->created_by) {
                                            \Filament\Notifications\Notification::make()
                                                ->title('Unauthorized')
                                                ->body('You can only edit your own replies.')
                                                ->danger()
                                                ->send();
                                            return [];
                                        }
                                        if (!auth()->user()->hasRole('super_admin') && $this->ownerRecord && !$this->ownerRecord->is_visible_to_client) {
                                            $data['is_client_reply'] = false;
                                        }
                                        return $data;
                                    }),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return DiscussionsTable::configure($table);
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
            'index' => ListDiscussions::route('/'),
            'create' => CreateDiscussion::route('/create'),
            'edit' => EditDiscussion::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
