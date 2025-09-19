<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TicketInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('project_id')
                    ->numeric(),
                TextEntry::make('customer_id')
                    ->numeric(),
                TextEntry::make('assigned_to')
                    ->numeric(),
                TextEntry::make('subject'),
                TextEntry::make('priority')
                    ->numeric(),
                TextEntry::make('category'),
                TextEntry::make('due_date')
                    ->dateTime(),
                TextEntry::make('status_id')
                    ->numeric(),
                TextEntry::make('created_by')
                    ->numeric(),
                IconEntry::make('is_visible_to_client')
                    ->boolean(),
                IconEntry::make('allow_client_comments')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
