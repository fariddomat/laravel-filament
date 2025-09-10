<?php

namespace App\Filament\Resources\LeadSources\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeadSourceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
            ]);
    }
}
