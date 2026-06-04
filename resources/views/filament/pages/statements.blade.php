<x-filament::page>
    <h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-2xl">Balance Info</h2>
    <div class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Available Balance Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h2 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Available Balance</h2>
                <p class="text-xl font-bold">{{ number_format($availableBalance, 2) }}</p>
            </div>

        </div>
    </div>

     <h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-2xl">Today's Orders</h2>
    <div class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Today's Orders Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h2 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Total Orders</h2>
                <p class="text-xl font-bold">{{ $todaysOrders }}</p>
            </div>

            <!-- Today's Completed Orders Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h2 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Completed Orders</h2>
                <p class="text-xl font-bold">{{ $todaysCompletedOrders }}</p>
            </div>

            <!-- Today's Cancelled Orders Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h2 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Cancelled Orders</h2>
                <p class="text-xl font-bold">{{ $todaysCancelledOrders }}</p>
            </div>

            <!-- Today's Processing Orders Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h2 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Processing Orders</h2>
                <p class="text-xl font-bold">{{ $todaysProcessingOrders }}</p>
            </div>
        </div>
    </div>

    <h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-2xl">User's Info</h2>
    <div class="space-y-4">
       <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Total Users Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h2 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Total Users</h2>
                <p class="text-xl font-bold">{{ $totalUsers }}</p>
            </div>
            <!-- New Users Today Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h2 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">New Users (Today)</h2>
                <p class="text-xl font-bold">{{ $newUsersToday }}</p>
            </div>
        </div>
    </div>

    <h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-2xl">Voucher's Info</h2>
    <div class="space-y-4">
       <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <!-- Available Codes Card -->
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <h2 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Available</h2>
        <p class="text-xl font-bold">{{ $availableCodes }}</p>
    </div>

    <!-- Sold Codes Card -->
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <h2 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Sold</h2>
        <p class="text-xl font-bold">{{ $soldCodes }}</p>
    </div>

    <!-- Today's Sold Codes Card -->
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <h2 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Today's Sold</h2>
        <p class="text-xl font-bold">{{ $todaysSoldCodes }}</p>
    </div>



<style>
    @media (min-width: 768px) {
    .md\:grid-cols-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}
</style>

    <h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-2xl">Billing Info</h2>
    <div class="space-y-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            <!-- Today Billing -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Today</h3>
                <p class="text-xl font-bold">{{ number_format($todaysCompletedBilling, 2) }}</p>
            </div>

            <!-- Yesterday's Billing -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Yesterday</h3>
                <p class="text-xl font-bold">{{ number_format($yesterdaysCompletedBilling, 2) }}</p>
            </div>

            <!-- This Week's Billing -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">This Week</h3>
                <p class="text-xl font-bold">{{ number_format($thisWeekCompletedBilling, 2) }}</p>
            </div>

            <!-- Last Week's Billing -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Last Week</h3>
                <p class="text-xl font-bold">{{ number_format($lastWeekCompletedBilling, 2) }}</p>
            </div>

            <!-- This Month's Billing -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">This Month</h3>
                <p class="text-xl font-bold">{{ number_format($thisMonthCompletedBilling, 2) }}</p>
            </div>

            <!-- Last Month's Billing -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Last Month</h3>
                <p class="text-xl font-bold">{{ number_format($lastMonthCompletedBilling, 2) }}</p>
            </div>

            <!-- This Year's Billing -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">This Year</h3>
                <p class="text-xl font-bold">{{ number_format($thisYearCompletedBilling, 2) }}</p>
            </div>

            <!-- Last Year's Billing -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Last Year</h3>
                <p class="text-xl font-bold">{{ number_format($lastYearCompletedBilling, 2) }}</p>
            </div>
        </div>
    </div>


<h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-2xl">Profit Info</h2>
<div class="space-y-4">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        <!-- Today's Profit -->
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Today</h3>
            <p class="text-xl font-bold">{{ number_format($todaysCompletedProfit, 2) }}</p>
        </div>

        <!-- Yesterday's Profit -->
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Yesterday</h3>
            <p class="text-xl font-bold">{{ number_format($yesterdaysCompletedProfit, 2) }}</p>
        </div>

        <!-- This Week's Profit -->
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">This Week</h3>
            <p class="text-xl font-bold">{{ number_format($thisWeekCompletedProfit, 2) }}</p>
        </div>

        <!-- Last Week's Profit -->
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Last Week</h3>
            <p class="text-xl font-bold">{{ number_format($lastWeekCompletedProfit, 2) }}</p>
        </div>

        <!-- This Month's Profit -->
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">This Month</h3>
            <p class="text-xl font-bold">{{ number_format($thisMonthCompletedProfit, 2) }}</p>
        </div>

        <!-- Last Month's Profit -->
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Last Month</h3>
            <p class="text-xl font-bold">{{ number_format($lastMonthCompletedProfit, 2) }}</p>
        </div>

        <!-- This Year's Profit -->
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">This Year</h3>
            <p class="text-xl font-bold">{{ number_format($thisYearCompletedProfit, 2) }}</p>
        </div>

        <!-- Last Year's Profit -->
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="label text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Last Year</h3>
            <p class="text-xl font-bold">{{ number_format($lastYearCompletedProfit, 2) }}</p>
        </div>

    </div>
</div>
</x-filament::page>