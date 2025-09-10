<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\PipelineStage;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomersByStageWidget extends ChartWidget
{
    // protected static ?string $heading = 'Customers by Pipeline Stage';

    protected static ?string $navigationGroup = 'Dashboard';

    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return auth()->user()->hasPermissionTo('widget_CustomersByStageWidget');
    }

    protected function getData(): array
    {
        $stages = PipelineStage::query()
            ->orderBy('position')
            ->get()
            ->mapWithKeys(function (PipelineStage $stage) {
                return [
                    $stage->id => $stage->name ?? $stage->name,
                ];
            });

        $counts = Customer::query()
            ->groupBy('pipeline_stage_id')
            ->pluck(DB::raw('count(*) as count'), 'pipeline_stage_id')
            ->toArray();

        $labels = $stages->values()->toArray();
        $data = $stages->keys()->map(function ($stageId) use ($counts) {
            return $counts[$stageId] ?? 0;
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('dashboard.stats.customers_by_stage'),
                    'data' => $data,
                    'backgroundColor' => '#f59e0b', // Amber-500 to match theme
                    'borderColor' => '#d97706', // Amber-600
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Bar chart for customers per stage
    }
}
