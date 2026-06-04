@extends('layout.master')

@section('title')
    {{ __('My Transactions') }} - {{ $settings->site_title ?? '' }}
@endsection

@section('content')
<section class="orders">
  <div class="mx-auto container text-center m-4">
    <div class="px-4 md:px-0">
      <div class="bg-white border rounded-lg overflow-hidden mx-auto mr-2">
        <div class="text-left px-3 flex items-center justify-between">
          <div class="flex items-center">
            <svg viewBox="0 0 24 24" class="mr-2" style="width: 24px; height: 24px">
              <path fill="currentColor" d="M11 15H17V17H11V15M9 7H7V9H9V7M11 13H17V11H11V13M11 9H17V7H11V9M9 11H7V13H9V11M21 5V19C21 20.1 20.1 21 19 21H5C3.9 21 3 20.1 3 19V5C3 3.9 3.9 3 5 3H19C20.1 3 21 3.9 21 5M19 5H5V19H19V5M9 15H7V17H9V15Z"></path>
            </svg>
            <h2 class="text-lg text-black py-2 font-normal fb"> My Transactions</h2>
          </div>
        </div>
        <hr>

        {{-- Safety: ensure $transactions variable exists and is iterable --}}
        @php
          if (!isset($transactions) || !is_iterable($transactions)) {
              $transactions = collect([]);
          }
        @endphp

        @forelse($transactions as $transaction)
          <div class="orders-list border-b-2 m-2">
            <div class="sm:flex">
              <div class="w-full sm:w-1/2">
                <p class="px-3 py-1 text-left">
                  <span class="font-bold">{{ __('Transaction ID') }}: </span> {{ $transaction->id }}
                </p>
                <p class="px-3 py-1 text-left">
                  <span class="font-bold">{{ __('Date') }}: </span>
                  {{ optional($transaction->created_at)->format('d M Y H:i') ?? 'N/A' }}
                </p>
                <p class="px-3 py-1 text-left">
                  <span class="font-bold">{{ __('Order / Package') }}: </span>
                  {{ $transaction->order_description ?? 'N/A' }}
                </p>
                <p class="px-3 py-1 text-left">
                  <span class="font-bold">{{ __('Amount') }}: </span>
                  {{ isset($transaction->amount) ? number_format($transaction->amount, 2) : '0.00' }}
                </p>
              </div>

              <div class="w-full sm:w-1/2">
                <p class="px-3 py-1 text-left">
                  <span class="font-bold">{{ __('Payment Method') }}: </span>
                  {{ !empty($transaction->payment_method) ? $transaction->payment_method : 'N/A' }}
                </p>
                <p class="px-3 py-1 text-left">
                  <span class="font-bold">{{ __('Status') }}: </span>
                  @php $status = $transaction->status ?? 'unknown'; @endphp
                  <span class="{{ $status === 'success' ? 'text-green-500' : ($status === 'pending' ? 'text-yellow-500' : 'text-red-500') }}">
                    {{ ucfirst($status) }}
                  </span>
                </p>

                @if(!empty($transaction->details) && is_iterable($transaction->details))
                  @php $details = $transaction->details; @endphp
                  @foreach($details as $key => $value)
                    <p class="px-3 py-1 text-left">
                      <span class="font-bold">{{ ucfirst(str_replace('_',' ',$key)) }}: </span> {{ $value }}
                    </p>
                  @endforeach
                @endif
              </div>
            </div>
          </div>
        @empty
          <div class="box-form mx-auto w-36 order-not-found">
            <h4 class="fb-normal text-base">No transactions found!</h4>
          </div>
        @endforelse

        <div class="mt-3">
           {{-- Pagination: only call links() if the $transactions object supports it --}}
           @if(method_exists($transactions, 'links'))
               {{ $transactions->links('pagination::bootstrap-5') }}
           @endif
        </div>
      </div>
    </div>
  </div>
</section>
@endsection