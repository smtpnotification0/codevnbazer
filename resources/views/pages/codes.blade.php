@extends('layout.master')
@section('title')
    {{ __('My Codes') }} {{ __('-') }} {{ $settings->site_title }}
@endsection
@section('content')
<section class="orders">
  <div class="mx-auto container mx-auto text-center m-4">
    <div class="pxa-4 md:px-0">
      <div class="bg-white border rounded-lg overflow-hidden mx-auto mr-2">
        <div class="text-left px-3 flex items-center justify-between">
          <div class="flex items-center">
            <svg viewBox="0 0 24 24" class="mr-2" style="width: 24px; height: 24px">
              <path fill="currentColor" d="M11 15H17V17H11V15M9 7H7V9H9V7M11 13H17V11H11V13M11 9H17V7H11V9M9 11H7V13H9V11M21 5V19C21 20.1 20.1 21 19 21H5C3.9 21 3 20.1 3 19V5C3 3.9 3.9 3 5 3H19C20.1 3 21 3.9 21 5M19 5H5V19H19V5M9 15H7V17H9V15Z"></path>
            </svg>
            <h2 class="text-lg text-black py-2 font-normal fb"> My Codes</h2>
          </div>
          <a href="https://shop.garena.my/app/100067/idlogin" target="_blank" class="btn theme-btn btn-sm shadow redem-btn"> Redeem Code </a>
        </div>
        <hr>
       @forelse ($codes as $code)
        <div class="orders-list border-b-2 m-2">
          <div class="sm:flex">
            <div class="w-full sm:w-1/2">
              <p class="px-3 py-1 text-left">
                <span class="font-bold">Serial NO: </span> {{ $code->id }}
              </p>
              <p class="px-3 py-1 text-left">
                <span class="font-bold">Date: </span> {{ custom_date($code) }}
              </p>
              <p class="px-3 py-1 text-left">
                <span class="font-bold">Package: </span>
                    @if (!empty($code->variation->title))
                        {{ $code->variation->title }}
                    @endif
              </p>
            </div>
            <div class="w-full sm:w-1/2">
                           
              <p class="px-3 py-1 text-left">
                <span class="font-bold">Price: </span> {{ price($code->amount) }}
              </p>
              <p class="px-3 py-1 text-left">
                <span class="font-bold">{{ __('Status') }}: </span>
                <span class="{{ \App\Constants\OrderStatus::color($code->status) }}">
                  <span class="order-status">{{ strtolower($code->status) }}</span>
                </span>
              </p>
              <div class="w-full">  <p class="px-3 py-1 text-left">
                  <span class="font-bold">Your Code: </span>
                </p>
                <div>
                  <div style="background: rgb(241, 236, 247); margin: 0px 12px; padding: 5px 4px; border-radius: 5px; white-space: pre-line; text-align: left;">{{ $code->voucher_code }} </div>
                  <span data-text="{{ $code->playerid }}" id="copy">
                    <button class="align-middle text-center px-2 py-1 text-sm font-thin inline-block rounded w-38 flex items-center text-center ml-3 mt-2 code-btn copy-icon">
                      <div class="w-38 rounded h-full flex items-center icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                          <title>Copy Code</title>
                          <path d="M19,21H8V7H19M19,5H8A2,2 0 0,0 6,7V21A2,2 0 0,0 8,23H19A2,2 0 0,0 21,21V7A2,2 0 0,0 19,5M16,1H4A2,2 0 0,0 2,3V17H4V3H16V1Z"></path>
                        </svg>
                      </div> Copy Code
                    </button>
                  </span>
                  </div>
                </div>
            @if ($code->delivery_message)
              <p class="px-3 py-1 text-left">
                 <i class="fas fa-info-circle" style="color: red;"></i>
                   {{ $code->delivery_message }}
              </p>
            @endif
            </div>
         </div>
       </div>
      @empty
        <div class="box-form mx-auto w-36 order-not-found">
          <h4 class="fb-normal text-base">No order found !</h4>
          <a href="../?#topup" class="bg-pink-500 border border-red-500 hover:bg-pink-500 text-white text-xs py-1 px-2 md:px-2 rounded uppercase paglabazar-btn"> Order Now </a>
        </div>
      @endforelse
      <div class="mt-3">
          {{ $codes->links('pagination::bootstrap-5') }}
      </div>
         
     </div>
   </div>
 </div>
</section> 
@endsection
@push('js')
<script type="text/javascript">
    $(document).on('click', '#copy', async function() {
        var text = $(this).data("text");
        try {
            await navigator.clipboard.writeText(text);
            toastr.success('Copied!');
        } catch (err) {
            console.error('Failed to copy text:', err);
        }
    });

    $(document).ready(function() {
        $('#status').change(function() {
            var selectedstatus = $(this).val();
            window.location.href = '?status=' + selectedstatus;
        });
    });
</script>
@endpush