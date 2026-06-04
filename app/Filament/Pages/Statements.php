<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class Statements extends Page
{
    protected static ?string $navigationLabel = 'Statements';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.statements';

    public $availableBalance;
    public $todaysOrders, $todaysCompletedOrders, $todaysCancelledOrders, $todaysProcessingOrders;
    public $newUsersToday, $totalUsers;
    public $availableCodes, $soldCodes, $todaysSoldCodes;
    public $todaysCompletedBilling, $yesterdaysCompletedBilling, $thisWeekCompletedBilling, $lastWeekCompletedBilling, $thisMonthCompletedBilling, $lastMonthCompletedBilling, $thisYearCompletedBilling, $lastYearCompletedBilling;
    public $todaysCompletedProfit, $yesterdaysCompletedProfit, $thisWeekCompletedProfit, $lastWeekCompletedProfit, $thisMonthCompletedProfit, $lastMonthCompletedProfit, $thisYearCompletedProfit, $lastYearCompletedProfit;

    public function mount()
    {
        $this->availableBalance = DB::table('users')->sum('balance');

        $this->todaysOrders = DB::table('orders')
        ->whereDate('created_at', today())
        ->count();

        $this->todaysCompletedOrders = DB::table('orders')
            ->where('status', 'complete')
            ->whereDate('updated_at', today())
            ->count();

        $this->todaysCancelledOrders = DB::table('orders')
            ->where('status', 'cancel')
            ->whereDate('updated_at', today())
            ->count();
        $this->todaysProcessingOrders = DB::table('orders')
            ->where('status', 'processing')
            ->whereDate('updated_at', today())
            ->count();

        $this->newUsersToday = DB::table('users')
        ->whereDate('created_at', today())
        ->count();

    $this->totalUsers = DB::table('users')
        ->count();

         $this->availableCodes = DB::table('vouchers')
        ->where('status', 1)
        ->count();

    $this->soldCodes = DB::table('vouchers')
        ->where('status', 0)
        ->whereNotNull('order_id')
        ->count();

    $this->todaysSoldCodes = DB::table('vouchers')
        ->where('status', 0)
        ->whereNotNull('order_id')
        ->whereDate('updated_at', today())
        ->count();


    $this->todaysCompletedBilling = DB::table('orders')
        ->where('status', 'complete')
        ->whereDate('updated_at', today())
        ->sum('amount');

    $this->yesterdaysCompletedBilling = DB::table('orders')
        ->where('status', 'complete')
        ->whereDate('updated_at', today()->subDay())
        ->sum('amount');

    $this->thisWeekCompletedBilling = DB::table('orders')
        ->where('status', 'complete')
        ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
        ->sum('amount');

    $this->lastWeekCompletedBilling = DB::table('orders')
        ->where('status', 'complete')
        ->whereBetween('updated_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
        ->sum('amount');

    $this->thisMonthCompletedBilling = DB::table('orders')
        ->where('status', 'complete')
        ->whereMonth('updated_at', now()->month)
        ->whereYear('updated_at', now()->year)
        ->sum('amount');

    $this->lastMonthCompletedBilling = DB::table('orders')
        ->where('status', 'complete')
        ->whereMonth('updated_at', now()->subMonth()->month)
        ->whereYear('updated_at', now()->subMonth()->year)
        ->sum('amount');

    $this->thisYearCompletedBilling = DB::table('orders')
        ->where('status', 'complete')
        ->whereYear('updated_at', now()->year)
        ->sum('amount');

    $this->lastYearCompletedBilling = DB::table('orders')
        ->where('status', 'complete')
        ->whereYear('updated_at', now()->subYear()->year)
        ->sum('amount');

$this->todaysCompletedProfit = DB::table('orders')
    ->where('status', 'complete')
    ->whereDate('updated_at', today())
    ->sum('profit');

$this->yesterdaysCompletedProfit = DB::table('orders')
    ->where('status', 'complete')
    ->whereDate('updated_at', today()->subDay())
    ->sum('profit');

$this->thisWeekCompletedProfit = DB::table('orders')
    ->where('status', 'complete')
    ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
    ->sum('profit');

$this->lastWeekCompletedProfit = DB::table('orders')
    ->where('status', 'complete')
    ->whereBetween('updated_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
    ->sum('profit');

$this->thisMonthCompletedProfit = DB::table('orders')
    ->where('status', 'complete')
    ->whereMonth('updated_at', now()->month)
    ->whereYear('updated_at', now()->year)
    ->sum('profit');

$this->lastMonthCompletedProfit = DB::table('orders')
    ->where('status', 'complete')
    ->whereMonth('updated_at', now()->subMonth()->month)
    ->whereYear('updated_at', now()->subMonth()->year)
    ->sum('profit');

$this->thisYearCompletedProfit = DB::table('orders')
    ->where('status', 'complete')
    ->whereYear('updated_at', now()->year)
    ->sum('profit');

$this->lastYearCompletedProfit = DB::table('orders')
    ->where('status', 'complete')
    ->whereYear('updated_at', now()->subYear()->year)
    ->sum('profit');
    }
}
