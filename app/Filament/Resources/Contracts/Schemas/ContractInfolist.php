<?php

namespace App\Filament\Resources\Contracts\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ContractInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer_id')
                    ->numeric(),
                TextEntry::make('project_id')
                    ->numeric(),
                TextEntry::make('quote_id')
                    ->numeric(),
                TextEntry::make('title'),
                TextEntry::make('contract_template_id')
                    ->numeric(),
                TextEntry::make('start_date')
                    ->date(),
                TextEntry::make('end_date')
                    ->date(),
                TextEntry::make('value')
                    ->numeric(),
                TextEntry::make('currency'),
                IconEntry::make('is_signed')
                    ->boolean(),
                TextEntry::make('signed_at')
                    ->dateTime(),
                TextEntry::make('signed_by')
                    ->numeric(),
                TextEntry::make('signature_path'),
                TextEntry::make('created_by')
                    ->numeric(),
                TextEntry::make('status_id')
                    ->numeric(),
                IconEntry::make('is_visible_to_client')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->dateTime(),
            ]);
    }
}
