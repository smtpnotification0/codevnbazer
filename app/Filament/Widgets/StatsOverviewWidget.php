<?php

namespace App\Filament\Widgets;

use App\Constants\Status;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverviewWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    public function getStats(): array
    {
        $revenueInfo = $this->calculateRevenueIncreaseByDate();
        $orderInfo = $this->calculateOrderIncreaseByDate();
        $userInfo = $this->calculateUserIncreaseByDate();

        return [
            $this->makeStat('Revenue', $revenueInfo),
            $this->makeStat('New customers', $userInfo),
            $this->makeStat('New orders', $orderInfo),
        ];
    }

    private function makeStat(string $label, array $info): Stat
    {
        $color = ($info['increase'] >= 0) ? 'success' : 'danger';
        $icon = ($info['increase'] >= 0) ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $chart = ($info['increase'] >= 0) ? [7, 2, 10, 3, 15, 4, 17] : [17, 16, 14, 15, 14, 13, 12];

        $percentage = $info['percentage'];
        $description = ($info['increase'] >= 0) ? "Increase by {$percentage}% in this month" : "Decrease by {$percentage}% in this month";

        return Stat::make($label, $info['formatted'])
            ->description($description)
            ->descriptionIcon($icon)
            ->chart($chart)
            ->color($color);
    }

    private function calculateRevenueIncreaseByDate(): array
    {
        $currentRevenue = Order::whereMonth('created_at', now()->month)
            ->where('status', Status::COMPLETE)
            ->sum('amount');
        $previousRevenue = Order::whereMonth('created_at', now()->month - 1)
            ->where('status', Status::COMPLETE)
            ->sum('amount');

        $increase = $currentRevenue - $previousRevenue;
        $percentage = ($previousRevenue !== 0) ? (($increase / abs($previousRevenue)) * 100) : 0;
        $formatted = $this->formatRevenue($currentRevenue);

        // Limit the percentage to the range of -100% to +100%
        $percentage = max(-100, min(100, $percentage));

        return [
            'increase' => $increase,
            'percentage' => round($percentage, 2),
            'formatted' => gs()->base_currency . ' ' . $formatted,
        ];
    }

    private function calculateOrderIncreaseByDate(): array
    {
        $currentOrders = Order::whereMonth('created_at', now()->month)->count();
        $previousOrders = Order::whereMonth('created_at', now()->month - 1)->count();

        $increase = $currentOrders - $previousOrders;
        $percentage = ($previousOrders !== 0) ? (($increase / abs($previousOrders)) * 100) : 0;

        // Limit the percentage to the range of -100% to +100%
        $percentage = max(-100, min(100, $percentage));

        return [
            'increase' => $increase,
            'percentage' => round($percentage, 2),
            'formatted' => number_format($currentOrders, 0),
        ];
    }

    private function calculateUserIncreaseByDate(): array
    {
        $currentUsers = User::whereMonth('created_at', now()->month)
            ->where('user_type', 'user')
            ->count();
        $previousUsers = User::whereMonth('created_at', now()->month - 1)
            ->where('user_type', 'user')
            ->count();

        $increase = $currentUsers - $previousUsers;
        $percentage = ($previousUsers !== 0) ? (($increase / abs($previousUsers)) * 100) : 0;

        // Limit the percentage to the range of -100% to +100%
        $percentage = max(-100, min(100, $percentage));

        return [
            'increase' => $increase,
            'percentage' => round($percentage, 2),
            'formatted' => number_format($currentUsers, 0),
        ];
    }

    private function formatRevenue($revenue): string
    {
        $magnitude = abs($revenue);

        if ($magnitude >= 1e12) {
            return number_format($revenue / 1e12, 2) . 'T'; // Trillion
        } elseif ($magnitude >= 1e9) {
            return number_format($revenue / 1e9, 2) . 'B'; // Billion
        } elseif ($magnitude >= 1e6) {
            return number_format($revenue / 1e6, 2) . 'M'; // Million
        } elseif ($magnitude >= 1e3) {
            return number_format($revenue / 1e3, 2) . 'K'; // Thousand
        } else {
            return number_format($revenue, 2);
        }
    }
}
