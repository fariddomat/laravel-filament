<?php

namespace App\Filament\Resources\Discussions\Pages;

use App\Filament\Resources\Discussions\DiscussionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditDiscussion extends EditRecord
{
    protected static string $resource = DiscussionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
