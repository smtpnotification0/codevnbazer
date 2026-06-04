@extends('layout.master')
@section('title')
{{ $product->title }} {{ __('-') }} {{ $settings->site_title }}
@endsection
@section('content')
<!--- checkout page--->
<div>
   <div class="p-2 container m-auto checkout_page">
      <div class="bg-white border rounded-md">
         <div class="flex">
            <div>
               <img class="rounded-3xl p-2 w-24 h-24" src="{{ asset('uploads') }}/{{ $product->image }}" alt="{{ $product->title }}">
            </div>
            <div class="flex items-center">
               <div>
                  <h2 class="text-lg capitalize">{{ $product->title }}</h2>
                  <div class="text-gray-400 text-sm text-left">
                     <span>{{ productType($product->type) }} </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div>
         <form method="POST" action="{{ route('topup.buynow') }}" class="md:flex gap-2">
           @csrf
                     <input type="hidden" name="variation_id" id="variation_id" value="">
                     <input type="hidden" name="variation_price" id="variation_price" value="">
                     @if ($settings->wallet)
                     <input type="hidden" name="payment_method" id="payment_method" value="wallet">
                     @else
                     <input type="hidden" name="payment_method" id="payment_method" value="payment_gateway">
                     @endif
                     
            <section class="w-full md:w-2/3 mt-2">
               <div class="bg-white border rounded-md">
                  <div class="text-left px-3 flex items-center">
                     <div class="_order_header_step_circle mr-2">1</div>
                     <h2 class="text-lg text-black py-2 font-normal fb"> Select Recharge </h2>
                  </div>
                  <hr>
                  <div class="p-1 md:p-4 inline-grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-2 package-item-outer w-full">
                   @foreach ($product->variations as $variation)
                     <button type="button" class="sm-device-package mb-0 w-full drop-shadow-2xl list-group-item flex content-between p-2 active:order-0 variation_list @if ($variation->stock < 1 || ($product->isVoucher() && count($variation->vouchers) < 1)) stockout @endif" style="font-size: 11px; position: relative; overflow: hidden; display: flex; justify-content: space-between; align-items: center; height: 50px;"
                        id="{{ $variation->id }}" data-price="{{ $variation->price }}">
                        <div class="w-full flex flex-wrap">
                           <div class="flex items-center">
                              <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa h-2 mr-2 w-4 text-gray-300 fa-circle fa-w-16 fa-2x">
                                 <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z" class=""></path>
                              </svg>
                              <span class="text-xs font-primary">{{ $variation->title }}</span>
                           </div>
                           @if ($variation->stock < 1 || ($product->isVoucher() && count($variation->vouchers) < 1))
                           <h6 class="bg-red-500 ml-2 rounded-full text-white px-2" style="font-size: 8px; padding-top: 3px; padding-bottom: 0px; max-width: 70px;"> STOCK OUT </h6>
                           @endif
                           <!---->
                        </div>
                        <div class="font-bold fb-normal" style="color: var( --theme-color); min-width: 46px; float: right; text-align: right;">{{ price($variation->price) }} </div>
                     </button>
                     @endforeach
                                          
                   </div>
                  <!---->
                    <!--- tutorial ---> 
                    @if($product->has_tutorial) 
                    <div class="ml-4 mt-2 md:mt-0">
                      <div>
                        <p class="_body2 mb-3">
                          <a href=" {{$product->tutorial_link}}" target="_blank" class="text-left text-lg flex items-start info-text blink_me" style="color: rgb(0, 0, 238);">
                            <span class="text-lg flex" style="font-family: initial;">
                              {{$product->tutorial_text}}
                            </span>
                            <svg stroke="currentColor" fill="none" stroke-width="0" viewBox="0 0 24 24" height="22" width="22" xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                          </a>
                        </p>
                      </div>
                    </div> 
                    @endif
                    <!--- /tutorial --->
                </div>
            </section>
            <div class="w-full md:w-1/3 mt-2">
              @if ($product->isTopup() || $product->isInGame() || $product->isSubscription())
               <!---account info --->
               <section>
                  <div class="border bg-white rounded-md">
                     <div class="text-left px-3 flex items-center">
                        <div class="_order_header_step_circle mr-2">2</div>
                        <h2 class="text-lg text-black py-2 font-bold fb-normal"> {{ __('Account Info') }} </h2>
                     </div>
                     <hr>
                     @if ($product->isTopup())
                     <!--- player id --->
                     <div class="p-3">
                        <div class="relative">
                           <label class="label-title">{{ $product->input }}</label><input name="account_info[player_id]" id="player_id" type="text" placeholder="{{ $product->input }}" class="form-input relative block w-full disabled:cursor-not-allowed disabled:opacity-75 focus:outline-none border-0 rounded-md placeholder-gray-400 dark:placeholder-gray-500 text-sm px-2.5 py-2.5 shadow-sm bg-transparent text-gray-900 dark:text-white ring-1 ring-inset dark:ring-black-900 focus:ring-2 focus:ring-black-900 dark:focus:ring-black-900 player_id checkId" required>
                           @if ($settings->enable_uid_checker)
                           @if ($product->uid_checker == 1)
                           <div class="gamename1">
                               <span id="gamename1">Click to check player name</span>
                           </div>
                           @endif
                           @endif
                        </div>
                     </div>
                     <!--- /player id --->
                     @endif
                     @if ($product->isSubscription())
                     <!--- subscription --->
                     <div class="p-3">
                        <div class="relative">
                           <label class="label-title">{{ $product->input }}</label><input name="account_info[subscription_details]" id="player_id" type="text" placeholder="{{ $product->input }}" class="form-input relative block w-full disabled:cursor-not-allowed disabled:opacity-75 focus:outline-none border-0 rounded-md placeholder-gray-400 dark:placeholder-gray-500 text-sm px-2.5 py-2.5 shadow-sm bg-transparent text-gray-900 dark:text-white ring-1 ring-inset dark:ring-black-900 focus:ring-2 focus:ring-black-900 dark:focus:ring-black-900 player_id checkId" required>
                        </div>
                     </div>
                     <!--- /subscription --->
                     @endif
                     @if ($product->isInGame())
                     <!--- /account --->
                     <div class="p-3">
                        <div class="p-2 pb-0">
                           <label>{{ __('Account Type') }}</label>
                           <select  name="account_info[account_type]" id="game_account_type" class="form-select relative block w-full disabled:cursor-not-allowed disabled:opacity-75 focus:outline-none border-0 rounded-md text-sm px-3.5 py-2.5 shadow-sm bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 pe-11">
                              <option value="Gmail">Gmail</option>
                              <option value="Facebook">Facebook</option>
                           </select>
                        </div>
                        <div class="p-2 pb-0"><label>Enter Email/Number</label><input name="account_info[game_account]" id="game_account" type="text" placeholder="Enter Email/Number" class="form-input relative block w-full disabled:cursor-not-allowed disabled:opacity-75 focus:outline-none border-0 rounded-md placeholder-gray-400 dark:placeholder-gray-500 text-sm px-2.5 py-2.5 shadow-sm bg-transparent text-gray-900 dark:text-white ring-1 ring-inset dark:ring-black-900 focus:ring-2 focus:ring-black-900 dark:focus:ring-black-900" required></div>
                        <div class="p-2 pb-0"><label>Password</label><input name="account_info[game_password]" id="game_password" type="text" placeholder="Enter Password" class="form-input relative block w-full disabled:cursor-not-allowed disabled:opacity-75 focus:outline-none border-0 rounded-md placeholder-gray-400 dark:placeholder-gray-500 text-sm px-2.5 py-2.5 shadow-sm bg-transparent text-gray-900 dark:text-white ring-1 ring-inset dark:ring-black-900 focus:ring-2 focus:ring-black-900 dark:focus:ring-black-900" required></div>
                        <div class="p-2 pb-0"><label>Account Back Up If Have</label><input name="account_info[game_backup]" id="game_backup" type="text" placeholder="Enter Back Up Code" class="form-input relative block w-full disabled:cursor-not-allowed disabled:opacity-75 focus:outline-none border-0 rounded-md placeholder-gray-400 dark:placeholder-gray-500 text-sm px-2.5 py-2.5 shadow-sm bg-transparent text-gray-900 dark:text-white ring-1 ring-inset dark:ring-black-900 focus:ring-2 focus:ring-black-900 dark:focus:ring-black-900"></div>
                        @if (!empty($settings->backup_code_video_link))
                        <div class="w-full text-left ml-1">
                           <!----><a href="{{ $settings->backup_code_video_link }}" target="_blanck" class="text-left text-lg flex items-start info-text" style="color: rgb(0, 0, 238);">কিভাবে ফেসবুক অ্যাকাউন্ট এর ব্যাকআপ কোড বের করবেন?</a>
                        </div>
                        @endif
                     </div>
                     @endif
                     <!--- /account --->
                  </div>
               </section>
               <!---/account info --->
               @endif
               @if ($product->isVoucher())
               <!--- quantity --->
               <section>
                  <div class="flex justify-between align-middle px-3 bg-white rounded-md border quantity-container">
                     <div class="my-auto font-primary"> {{ __('Quantity') }} </div>
                     <div>
                        <label for="{{ __('Quantity') }}" class="sr-only"> {{ __('Quantity') }} </label>
                        <div class="flex items-center border-2 my-2 border-gray-200 rounded-full px-2 quantity-options">
                           <div class="cursor-pointer w-6 h-6 flex items-center justify-center" id="decrease">
                              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-6 w-6">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"></path>
                              </svg>
                           </div>
                           <input value="1" min="1" autocomplete="off" type="number" class="h-10 w-16 border-transparent text-center bg-white [&amp;::-webkit-inner-spin-button]:appearance-none" id="quantity" name="quantity">
                           <div class="cursor-pointer w-6 h-6 flex items-center justify-center" id="increase">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                              </svg>
                           </div>
                        </div>
                     </div>
                  </div>
               </section>
               <!--- /quantity --->
               @endif
               <section>
                  <div class="bg-white border mt-2" style="border-radius: 0.375rem; border-width: 1px;">
                     <div class="text-left px-3 flex items-center">
                        <div class="_order_header_step_circle mr-2">3</div>
                        <h2 class="text-lg text-black py-2 font-bold fb-normal"> Payment Methods </h2>
                     </div>
                     <hr>
                     <div class="flex justify-center py-3 px-2">
                     @if ($settings->wallet)
                        <div class="w-full pm_list" id="wallet">
                           <div class="m-1">
                              <label for="wallet" class="mb-0 w-full list-group-item pt-2 cursor-pointer" style="display: block; font-size: 11px; position: relative; overflow: hidden;">
                                 <span class="absolute left-0 check_selected element-check-label" style="color: rgb(255, 255, 255);">L </span>
                                 <img src="{{ asset('assets/template/images/wallet.png') }}" alt="wallet" class="p-2" style="height: 6rem;">
                                 <input id="wallet" name="send" type="radio" class="absolute" value="1" style="visibility: hidden;">
                                 <div class="bg-gray-300 text-left p-1">
                                    <p class="text-xs p-0 capitalize fb-normal"> {{ __('Wallet Pay') }}
                        @auth
                        {{ __('(') }}{{ $settings->currency_symbol }}<span
                           id='wallet_balance'>{{ amount(Auth::user()->balance) }}</span>
                        {{ __(')') }}
                        @endauth</p>
                                 </div>
                              </label>
                           </div>
                        </div>
                     @endif
               
                        <div class="text-center w-full pm_list" id="payment_gateway">
                           <div class="m-1">
                              <label for="sslcom" class="mb-0 w-full list-group-item pt-2 cursor-pointer" style="display: block; font-size: 11px; position: relative; overflow: hidden;">
                                 <span class="absolute check_selected left-0" style="color: rgb(255, 255, 255);">L </span>
                                 <img src="{{ asset('assets/template/images/bd_payments.png') }}" alt="SSL" class="p-2" style="height: 6rem;">
                                 <input id="sslcom" name="send" type="radio" class="absolute" value="2" style="visibility: hidden;">
                                 <div class="bg-gray-300 text-left">
                                    <p class="text-xs p-1 capitalize fb-normal"> Instant Pay</p>
                                 </div>
                              </label>
                           </div>
                        </div>
                     </div>
                     <div class="row pb-5">
                        <!---->
                        <div class="col-md-12 text-left px-3">
                           <div>
                              <div class="fb-normal text-xs flex items-center" style="color: gray;">
                                 <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="info-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa h-4 w-4 mr-1 fa-info-circle fa-w-16 fa-2x">
                                    <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm0-338c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path>
                                 </svg>
                                 আপনার অ্যাকাউন্ট ব্যালেন্স 
                                 <div style="min-width: 100px;">
                                    <span class="flex items-center">
                                       <p class="pl-2 text-pink-500 font-bold fb cost_alert_bl"> 
                                            {{ $settings->currency_symbol }}
                                          @auth
                                            {{ amount(Auth::user()->balance) }}
                                            @else
                                            0
                                            @endauth
                                         </p>
                                       <div class="border ml-2 p-1 rounded cursor-pointer">
                                          <svg viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                             <path fill="currentColor" d="M2 12C2 16.97 6.03 21 11 21C13.39 21 15.68 20.06 17.4 18.4L15.9 16.9C14.63 18.25 12.86 19 11 19C4.76 19 1.64 11.46 6.05 7.05C10.46 2.64 18 5.77 18 12H15L19 16H19.1L23 12H20C20 7.03 15.97 3 11 3C6.03 3 2 7.03 2 12Z"></path>
                                          </svg>
                                       </div>
                                    </span>
                                 </div>
                              </div>
                              <p class="fb-normal text-xs flex items-center mb-3" style="color: gray;">
                                 <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="info-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa h-4 w-4 mr-1 fa-info-circle fa-w-16 fa-2x">
                                    <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm0-338c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path>
                                 </svg>
                                 প্রোডাক্ট কিনতে আপনার প্রয়োজন <span class="text-pink-500 font-bold fb" style="padding: 0px 4px; font-size: 14px;"> ৳<span id="total_cost">0</span></span> ।
                              </p>
                             @auth
                              <div>
                                  <a href="add-funds" class="align-middle bg-pink-500 hover:bg-pink-400 text-center px-4 py-2 text-white text-sm font-semibold rounded inline-block shadow-lg w-full gosizi-btn" id="add_fund" style="margin-bottom: 10px;display:none;">
                                      ADD FUND
                                  </a>
                                 <button class="align-middle bg-pink-500 hover:bg-pink-400 text-center px-4 py-2 text-white text-sm font-semibold rounded inline-block shadow-lg w-full gosizi-btn buy_now_btn" id="buy_now" type="buy_now" disabled> Buy Now </button>
                              </div>
                             @else
                              <div>
                                 <a href="{{ route('login') }}">
                                 <button class="align-middle bg-pink-500 hover:bg-pink-400 text-center px-4 py-2 text-white text-sm font-semibold rounded inline-block shadow-lg w-full gosizi-btn checkout_login" type="button"> LOG IN </button>
                                 </a>
                             </div>
                            @endauth
                           </div>
                        </div>
                        <!---->
                     </div>
                  </div>
               </section>
            </div>
         </form>
      </div>
      <div class="mt-2 bg-white border rounded-md">
         <h1 class="font-bold p-2"> Rules &amp; Conditions </h1>
         <hr>
         <div class="p-2">
            {!! $product->content !!}
         </div>
      </div>
   </div>
</div>
<!--- /checkout page--->
@endsection
@push('js')
@if ($settings->enable_uid_checker)
@if ($product->uid_checker == 1)
<script>
    $(document).ready(function () {
        $('.gamename1').on('click', function () {
            var id = $('#player_id').val().trim();

            if (id) {
                var url = "{{ route('uidcheck') }}";

                $.post(url, {
                    id: id,
                    _token: "{{ csrf_token() }}"
                })
                .done(function (response) {
                    const nickname = response.nickname ?? 'No nickname found';
                    $('.gamename1').html('<span> ' + nickname + ' </span>');
                })
                .fail(function () {
                    $('.gamename1').html('<span>Error occurred while checking Player ID.</span>');
                });

            } else {
                $('.gamename1').html('<span>Please enter a valid Player ID.</span>');
            }
        });
    });
</script>
@endif
@endif
<script>
    $(document).ready(function() {
        const $playerId = $('#player_id');
        const $playerIdError = $("#player_id_error");
        const $gameAccount = $('#game_account');
        const $gameAccountError = $("#game_account_error");
        const $gamePassword = $('#game_password');
        const $gamePasswordError = $("#game_password_error");
        const $addMoneyInstruction = $('#add_money_instruction');
        const $wallet = $('#wallet');
        const $walletBalance = $('#wallet_balance');
        const $variationId = $('#variation_id');
        const $variationPrice = $('#variation_price');
        const $totalCost = $('#total_cost');
        const $paymentMethod = $('#payment_method');
        const $quantityInput = $('#quantity');
        const $buyNow = $("#buy_now");
        const $addFund = $("#add_fund");

        // Handle Buy Now button click
        $buyNow.on('click', function(e) {
            // Hide the button for 3 seconds
            $(this).hide();
            setTimeout(() => {
                $(this).show();
            }, 3000);
        });

        function showError($element, message) {
            $element.html(`<div class='alert alert-white alert-p5 m-lr-7'>${message}</div>`);
        }

        function clearError($element) {
            $element.html("");
        }

        function handleInputError($input, $errorElement, message) {
            $input.on('keyup', function() {
                if ($(this).val() === "") {
                    showError($errorElement, message);
                } else {
                    clearError($errorElement);
                }
            });
        }

        handleInputError($playerId, $playerIdError, "Player id required");
        handleInputError($gameAccount, $gameAccountError, "Gmail/number required");
        handleInputError($gamePassword, $gamePasswordError, "Password required");

        $('#payment_gateway').on('click', function() {
            $addMoneyInstruction.show();
        });

        $wallet.on('click', function() {
            $addMoneyInstruction.hide();
        });

        function selectVariation() {
            $('.variation_list').click(function() {
                var clickedVariation = $(this);
                var hasStockoutClass = clickedVariation.hasClass('stockout');

                if (!hasStockoutClass) {
                    $('.variation_list').removeClass('selected_variation');
                    clickedVariation.addClass('selected_variation');
                    $('.variation_list').each(function() {
                        var svg = $(this).find('svg');
                        if ($(this).hasClass('selected_variation')) {
                            svg.attr('data-icon', 'check-circle');
                            svg.html('<path fill="currentColor" d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248 6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path>');
                            svg.css('color', 'var(--theme-color)');
                        } else {
                            svg.attr('data-icon', 'circle');
                            svg.html('<path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>');
                            svg.css('color', '');
                        }
                    });
                    $('#quantity').val("1");
                    $('#variation_id').val(clickedVariation.attr('id'));
                    $('#variation_price').val(clickedVariation.data('price'));
                    enableBuyNow();
                    const unitCost = parseFloat($variationPrice.val());
                    autoSelectPaymentMethod(unitCost);
                    if (unitCost !== "" && unitCost !== "undifnied") {
                        $totalCost.text(unitCost.toFixed(0));
                    } else {
                        $totalCost.text("0");
                    }
                    checkWallet();
                }
            });
        }

        function selectPaymentMethod() {
            $('.pm_list').click(function() {
                var clickedPM = $(this);
                $('.pm_list .check_selected').removeClass('element-check-label');
                clickedPM.find('.check_selected').addClass('element-check-label');
                $('#payment_method').val(clickedPM.attr('id'));
                checkWallet();
            });
        }

        function checkWallet() {
            const variationPrice = parseFloat($variationPrice.val());
            const paymentMethod = $paymentMethod.val();
            const walletBalance = parseFloat($walletBalance.text());

            if ($('#quantity').length) {
                var getQuantity = $('#quantity').val();
            } else {
                var getQuantity = 1;
            }
            var calNewCost = variationPrice * getQuantity;

            if (!isNaN(calNewCost)) {
                if (paymentMethod === "wallet" && calNewCost > walletBalance) {
                    disableBuyNow();
                } else {
                    enableBuyNow();
                }
            }
        }

        function disableBuyNow() {
            $buyNow.prop("disabled", true);
            $addFund.show();
        }

        function enableBuyNow() {
            $buyNow.prop("disabled", false);
            $addFund.hide();
        }

        function handleQuantityChange() {
            $(document).on('click', '.quantity-options div', function() {
                var $quantityInput = $('#quantity');
                var currentValue = parseInt($quantityInput.val());
                
                if ($(this).is('#decrease') && currentValue > 1) {
                    $quantityInput.val(currentValue - 1);
                } else if ($(this).is('#increase')) {
                    $quantityInput.val(currentValue + 1);
                }
                
                const unitCost = parseFloat($variationPrice.val());
                const newQuantity = parseInt($quantityInput.val());
                const newCost = unitCost * newQuantity;

                if (newCost !== "" && newCost !== "undifnied") {
                    $totalCost.text(newCost.toFixed(0));
                } else {
                    $totalCost.text("0");
                }
                autoSelectPaymentMethod(newCost);
            });

            $(document).on('change', '#quantity', function() {
                const unitCost = parseFloat($variationPrice.val());
                const newQuantity = parseInt($quantityInput.val());
                const newCost = unitCost * newQuantity;

                if (newCost !== "" && newCost !== "undifnied") {
                    $totalCost.text(newCost.toFixed(0));
                } else {
                    $totalCost.text("0");
                }
                autoSelectPaymentMethod(newCost);
            });
        }

        function autoSelectPaymentMethod(cost) {
            if ($walletBalance.text() < cost) {
                $('#payment_gateway').click();
            }

            if ($('#payment_method').val() == "wallet" && cost > $walletBalance.text()) {
                disableBuyNow();
            } else {
                enableBuyNow();
            }
        }

        function initializePaymentMethod() {
            if ($walletBalance.text() !== "") {
                if (parseFloat($walletBalance.text()) > 0) {
                    $wallet.click();
                } else {
                    $('#payment_gateway').click();
                }
            } else {
                $wallet.click();
            }
        }

        // Initialize event handlers
        selectVariation();
        selectPaymentMethod();
        handleQuantityChange();
        initializePaymentMethod();
    });
</script>
@endpush