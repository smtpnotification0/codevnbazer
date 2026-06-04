<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CustomersChart extends ChartWidget
{
    protected static ?string $heading = 'Users per month';

    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $currentYear = now()->year;

        $monthlyCustomerCounts = User::whereYear('created_at', $currentYear)
            ->where('user_type', 'user')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as customer_count')
            )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        $datasets = [
            [
                'label' => 'Users',
                'data' => $monthlyCustomerCounts->pluck('customer_count')->toArray(),
                'fill' => 'start',
            ],
        ];

        $labels = $monthlyCustomerCounts->pluck('month')->map(function ($month) {
            return date('M', mktime(0, 0, 0, $month, 1));
        })->toArray();

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }
}
