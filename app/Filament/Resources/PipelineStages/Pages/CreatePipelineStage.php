<?php

namespace App\Filament\Resources\PipelineStages\Pages;

use App\Filament\Resources\PipelineStages\PipelineStageResource;
use App\Models\PipelineStage;
use Filament\Resources\Pages\CreateRecord;

class CreatePipelineStage extends CreateRecord
{
    protected static string $resource = PipelineStageResource::class;
       protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['position'] = PipelineStage::max('position') + 1;

        return $data;
    }
}
