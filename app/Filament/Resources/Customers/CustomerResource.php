<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\RelationManagers\ContractsRelationManager;
use App\Filament\Resources\Customers\RelationManagers\CustomerFeedbackRelationManager;
use App\Filament\Resources\Customers\RelationManagers\DiscussionsRelationManager;
use App\Filament\Resources\Customers\RelationManagers\DocumentsRelationManager;
use App\Filament\Resources\Customers\RelationManagers\InvoicesRelationManager;
use App\Filament\Resources\Customers\RelationManagers\NotesRelationManager;
use App\Filament\Resources\Customers\RelationManagers\PaymentsRelationManager;
use App\Filament\Resources\Customers\RelationManagers\PipelineStagesRelationManager;
use App\Filament\Resources\Customers\RelationManagers\ProjectsRelationManager;
use App\Filament\Resources\Customers\RelationManagers\QuotesRelationManager;
use App\Filament\Resources\Customers\RelationManagers\TagsRelationManager;
use App\Filament\Resources\Customers\RelationManagers\TasksRelationManager;
use App\Filament\Resources\Customers\RelationManagers\TicketRepliesRelationManager;
use App\Filament\Resources\Customers\RelationManagers\TicketsRelationManager;
use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Filament\Resources\Customers\Tables\CustomersTable;
use App\Models\Customer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $recordTitleAttribute = 'first_name';

    public static function getGloballySearchableAttributes(): array
{
    return ['first_name', 'last_name'];
}

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;

    public static function form(Schema $schema): Schema
    {
        return CustomerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            // TagsRelationManager::class,
            // DocumentsRelationManager::class,
            TasksRelationManager::class,
            PipelineStagesRelationManager::class,
            QuotesRelationManager::class,
            ProjectsRelationManager::class,
            InvoicesRelationManager::class,
            PaymentsRelationManager::class,
            NotesRelationManager::class,
            TicketsRelationManager::class,
            // CustomerFeedbackRelationManager::class,
            // TicketRepliesRelationManager::class,
            DiscussionsRelationManager::class,
            ContractsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'edit' => EditCustomer::route('/{record}/edit'),
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
