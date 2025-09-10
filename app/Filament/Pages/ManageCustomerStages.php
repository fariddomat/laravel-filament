<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Customer;
use App\Models\PipelineStage;
use BackedEnum;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Livewire\Attributes\On;

class ManageCustomerStages extends Page
{

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxStack;

    protected string $view = 'filament.pages.manage-customer-stages';

    protected ?string $heading = 'Customer Board';

    protected static ?string $navigationLabel = 'Customer Board';



    #[On('statusChangeEvent')]
    public function changeRecordStatus($id, $pipeline_stage_id): void
    {
        $customer = Customer::findOrFail($id); // Use findOrFail for safety
        $customer->pipeline_stage_id = $pipeline_stage_id;
        $customer->save();

        // Log the change
        $customer->pipelineStageLogs()->create([
            'pipeline_stage_id' => $pipeline_stage_id,
            'notes' => null,
            'user_id' => auth()->id(),
        ]);

        // Notify with translatable customer name
        $customerName = $customer->first_name. ' ' . $customer->last_name;

        Notification::make()
            ->title($customerName . ' Pipeline Stage Updated')
            ->success()
            ->send();
    }

    protected function getViewData(): array
    {
        $statuses = $this->statuses();
        $records = $this->records();

        $statuses = $statuses->map(function ($status) use ($records) {
            $status['group'] = $this->getId();
            $status['kanbanRecordsId'] = "{$this->getId()}-{$status['id']}";
            $status['records'] = $records->filter(function ($record) use ($status) {
                return $this->isRecordInStatus($record, $status);
            });

            return $status;
        });

        return [
            'records' => $records,
            'statuses' => $statuses,
        ];
    }

    protected function statuses(): Collection
    {
        return PipelineStage::query()
            ->orderBy('position')
            ->get()
            ->map(function (PipelineStage $stage) {
                return [
                    'id' => $stage->id,
                    'title' => $stage->name ?? $stage->name, // Fallback
                ];
            });
    }

    protected function records(): Collection
    {
        return Customer::query()
            ->with('pipelineStage') // Eager-load for performance
            ->get()
            ->map(function (Customer $item) {
                return [
                    'id' => $item->id,
                    'title' => $item->first_name .' ' . $item->last_name,
                    'status' => $item->pipeline_stage_id,
                ];
            });
    }

    protected function isRecordInStatus($record, $status): bool
    {
        return $record['status'] === $status['id'];
    }
}
