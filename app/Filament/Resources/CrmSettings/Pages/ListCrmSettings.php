<?php

namespace App\Filament\Resources\CrmSettings\Pages;

use App\Filament\Resources\CrmSettings\CrmSettingsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;

class ListCrmSettings extends ListRecords
{
    use Translatable;

    protected static string $resource = CrmSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            LocaleSwitcher::make(),

        ];
    }
}
