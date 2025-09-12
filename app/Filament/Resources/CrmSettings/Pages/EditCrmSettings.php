<?php

namespace App\Filament\Resources\CrmSettings\Pages;

use App\Filament\Resources\CrmSettings\CrmSettingsResource;
use App\Models\Setting;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
class EditCrmSettings extends EditRecord
{
        use Translatable;

    protected static string $resource = CrmSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // DeleteAction::make(),
            // LocaleSwitcher::make(),

        ];
    }
protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record;
        $data = array_merge($data, $record->toArray());  // Loads full payload, including nested translations
        $locales = ['en', 'ar'];  // Add your locales here
        foreach ($locales as $locale) {
            if (!isset($data['payload']['company_name'][$locale])) {
                $data['payload']['company_name'][$locale] = '';
            }
            if (!isset($data['payload']['description'][$locale])) {
                $data['payload']['description'][$locale] = '';
            }
            if (!isset($data['payload']['address'][$locale])) {
                $data['payload']['address'][$locale] = '';
            }
        }

        return $data;
    }
    // Fixed: Removed specific type hints to match Filament's parent method signature
    protected function handleRecordUpdate(Model $record, array $data):Model
    {
        // Extract and save payload (nested translations are preserved)
        $record->update([
            'payload' => $data['payload'] ?? [],
        ]);

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();

        return $record;
    }
}
