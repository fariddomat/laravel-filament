<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Customer;
use App\Models\Quote;
use Illuminate\Support\Facades\DB;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?string $navigationGroup = 'Dashboard'; // Group with dashboard

    protected static ?int $sort = 1; // Order in dashboard

    // Restrict to super_admin or users with view_dashboard_stats permission
    public static function canView(): bool
    {
        return auth()->user()->hasPermissionTo('widget_StatsOverviewWidget');
    }

    protected function getStats(): array
    {
        $totalCustomers = Customer::count();
        $totalQuotes = Quote::count();

        return [
            Stat::make(__('dashboard.stats.customers'), $totalCustomers)
                ->description(__('dashboard.stats.total_customers'))
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            Stat::make(__('dashboard.stats.quotes'), $totalQuotes)
                ->description(__('dashboard.stats.total_quotes'))
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

        ];
    }
}
