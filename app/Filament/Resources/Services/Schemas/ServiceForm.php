<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                DatePicker::make('publish_date')
                    ->required(),
                Toggle::make('show')
                    ->required(),
                Select::make('state')
                    ->options(['active' => 'Active', 'pending' => 'Pending'])
                    ->required(),
                FileUpload::make('image')
                    ->image()
                    ->required(),
            ]);
    }
}
