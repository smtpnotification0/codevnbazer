@extends('layout.master')
@section('title')
    {{ __('My Orders') }} {{ __('-') }} {{ $settings->site_title }}
@endsection

@section('content')
<section class="orders">
  <div class="mx-auto container mx-auto text-center m-4">
    <div class="pxa-4 md:px-0">
      <div class="bg-white border rounded-lg overflow-hidden mx-auto mr-2">

        <div class="text-left px-3 flex items-center justify-between">
          <div class="flex items-center">
            <svg viewBox="0 0 24 24" class="mr-2" style="width: 24px; height: 24px">
              <path fill="currentColor"
                d="M11 15H17V17H11V15M9 7H7V9H9V7M11 13H17V11H11V13M11 9H17V7H11V9M9 11H7V13H9V11M21 5V19C21 20.1 20.1 21 19 21H5C3.9 21 3 20.1 3 19V5C3 3.9 3.9 3 5 3H19C20.1 3 21 3.9 21 5Z">
              </path>
            </svg>
            <h2 class="text-lg text-black py-2 font-normal fb"> My Orders</h2>
          </div>
        </div>

        <hr>

        @forelse ($orders as $order)
        @php
            $accountInfo = !empty($order->account_info_to)
                ? (array) $order->account_info_to
                : [];

            $copyText = "Order ID: " . ($order->order_id_to ?? $order->id) . "\n";
            $copyText .= "Package: " . ($order->variation->title ?? '') . "\n";
            $copyText .= "Price: " . price($order->amount) . "\n";

            if(isset($accountInfo['account_type']))
                $copyText .= "Account Type: {$accountInfo['account_type']}\n";

            if(isset($accountInfo['game_account']))
                $copyText .= "Game Account: {$accountInfo['game_account']}\n";

            if(isset($accountInfo['player_id']))
                $copyText .= "Player ID: {$accountInfo['player_id']}\n";
        @endphp

        <div class="orders-list border-b-2 m-2">
          <div class="sm:flex">

            {{-- LEFT --}}
            <div class="w-full sm:w-1/2">
              <p class="px-3 py-1 text-left">
                <span class="font-bold">Order ID: </span>
                {{ $order->order_id_to ?? $order->id }}

                {{-- COPY BUTTON --}}
                <button
                    id="copy"
                    class="ml-2 text-xs px-2 py-1 border rounded"
                    data-text="{{ $copyText }}">
                    Copy
                </button>
              </p>

              <p class="px-3 py-1 text-left">
                <span class="font-bold">Date: </span>
                {{ custom_date($order) }}
              </p>

              <p class="px-3 py-1 text-left">
                <span class="font-bold">Package: </span>
                {{ $order->variation->title ?? '' }}
              </p>
            </div>

            {{-- RIGHT --}}
            <div class="w-full sm:w-1/2">

              @if(isset($accountInfo['account_type']))
                <p class="px-3 py-1 text-left">
                  <span class="font-bold">Account Type: </span>
                  {{ $accountInfo['account_type'] }}
                </p>
              @endif

              @if(isset($accountInfo['game_account']))
                <p class="px-3 py-1 text-left">
                  <span class="font-bold">Game Account: </span>
                  {{ $accountInfo['game_account'] }}
                </p>
              @endif

              @if(isset($accountInfo['player_id']))
                <p class="px-3 py-1 text-left">
                  <span class="font-bold">Player ID: </span>
                  {{ $accountInfo['player_id'] }}
                </p>
              @endif

              <p class="px-3 py-1 text-left">
                <span class="font-bold">Price: </span>
                {{ price($order->amount) }}
              </p>

              @php
                $status = strtolower($order->status);
                $statusColor = ($status === 'cancel') ? 'red' : $settings->theme_color;
              @endphp

              <p class="px-3 py-1 text-left">
                <span class="font-bold">Status: </span>
                <span style="color: {{ $statusColor }};">
                  <span class="order-status">{{ $status }}</span>
                </span>
              </p>

              @if ($order->delivery_message)
                <p class="px-3 py-1 text-left">
                  <i class="fas fa-info-circle" style="color:red;"></i>
                  {{ $order->delivery_message }}
                </p>
              @endif

            </div>
          </div>
        </div>
        @empty
        <div class="box-form mx-auto w-36 order-not-found">
          <h4 class="fb-normal text-base">No order found !</h4>
          <a href="../?#topup"
            class="bg-pink-500 border border-pink-500 hover:bg-pink-500 text-white text-xs py-1 px-2 rounded uppercase paglabazar-btn">
            Order Now
          </a>
        </div>
        @endforelse

        <div class="mt-3">
          {{ $orders->links('pagination::bootstrap-5') }}
        </div>

      </div>
    </div>
  </div>
</section>
@endsection

@push('js')
<script>
$(document).on('click', '#copy', async function () {
    let text = $(this).data('text');
    await navigator.clipboard.writeText(text);
    toastr.success('Copied!');
});
</script>
@endpush