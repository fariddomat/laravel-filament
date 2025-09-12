<!-- resources/views/filament/pages/settings.blade.php -->
<x-filament-panels::page>
    {{ $this->form }}
    <div class="mt-4">
        {{ $this->getFormActions() }}
    </div>
</x-filament-panels::page>
