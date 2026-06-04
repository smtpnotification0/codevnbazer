<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Orders per month';

    protected static ?int $sort = 1;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $currentYear = now()->year;
        
        $monthlyOrderCounts = Order::whereYear('created_at', $currentYear)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        $datasets = [
            [
                'label' => 'Orders',
                'data' => $monthlyOrderCounts->pluck('order_count')->toArray(),
                'fill' => 'start',
            ],
        ];

        $labels = $monthlyOrderCounts->pluck('month')->map(function ($month) {
            return date('M', mktime(0, 0, 0, $month, 1));
        })->toArray();

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }
}
