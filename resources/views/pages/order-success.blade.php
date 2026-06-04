@extends('layout.master')
@section('title')
    {{ __('Order Successful') }} {{ __('-') }} {{ $settings->site_title }}
@endsection

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-3 py-10">
    <div class="w-full max-w-lg bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden animate-[fadeIn_.4s_ease]">

        {{-- Success Banner --}}
        <div class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-6 py-7 text-center">
            <div class="mx-auto w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mb-3 animate-[pop_.5s_ease]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h1 class="text-2xl font-bold">{{ __('Order Successful') }}</h1>
            <p class="text-sm opacity-90 mt-1">{{ __('Your order has been placed successfully.') }}</p>
        </div>

        {{-- Details (NO account_info shown) --}}
        <div class="p-6 space-y-3 text-sm">
            <div class="flex justify-between border-b border-dashed border-gray-200 dark:border-gray-700 pb-2">
                <span class="text-gray-500 dark:text-gray-400">{{ __('Voter / Order ID') }}</span>
                <span class="font-semibold text-gray-900 dark:text-white">{{ $order->order_id_to ?? ('#' . $order->id) }}</span>
            </div>

            <div class="flex justify-between border-b border-dashed border-gray-200 dark:border-gray-700 pb-2">
                <span class="text-gray-500 dark:text-gray-400">{{ __('Package') }}</span>
                <span class="font-semibold text-gray-900 dark:text-white text-right">
                    {{ optional($order->variation)->title ?? optional($order->variation)->name ?? '-' }}
                </span>
            </div>

            <div class="flex justify-between border-b border-dashed border-gray-200 dark:border-gray-700 pb-2">
                <span class="text-gray-500 dark:text-gray-400">{{ __('Product') }}</span>
                <span class="font-semibold text-gray-900 dark:text-white text-right">
                    {{ optional($order->product)->name ?? '-' }}
                </span>
            </div>

            <div class="flex justify-between border-b border-dashed border-gray-200 dark:border-gray-700 pb-2">
                <span class="text-gray-500 dark:text-gray-400">{{ __('Quantity') }}</span>
                <span class="font-semibold text-gray-900 dark:text-white">{{ $order->quantity }}</span>
            </div>

            <div class="flex justify-between border-b border-dashed border-gray-200 dark:border-gray-700 pb-2">
                <span class="text-gray-500 dark:text-gray-400">{{ __('Amount') }}</span>
                <span class="font-semibold text-emerald-600">{{ number_format($order->amount, 2) }} {{ $settings->cur_text ?? '' }}</span>
            </div>

            <div class="flex justify-between border-b border-dashed border-gray-200 dark:border-gray-700 pb-2">
                <span class="text-gray-500 dark:text-gray-400">{{ __('Status') }}</span>
                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">{{ __('Date') }}</span>
                <span class="font-semibold text-gray-900 dark:text-white">{{ $order->created_at->format('d M Y, h:i A') }}</span>
            </div>

            {{-- NOTE: account_info ইচ্ছাকৃতভাবে দেখানো হয়নি --}}
        </div>

        <div class="px-6 pb-6 flex gap-3">
            <a href="{{ route('orders') }}" class="flex-1 text-center bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-lg transition">
                {{ __('My Orders') }}
            </a>
            <a href="{{ url('/') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-800 dark:text-white font-semibold py-2.5 rounded-lg transition">
                {{ __('Home') }}
            </a>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
@keyframes pop  { 0%{transform:scale(.4);opacity:0} 60%{transform:scale(1.15);opacity:1} 100%{transform:scale(1)} }
</style>
@endsection
