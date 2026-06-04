@extends('layout.master')
@section('title')
    {{ __('Add Fund') }} {{ __('-') }} {{ $settings->site_title }}
@endsection
@section('content')
<div class="deposits">
<!--- add funds --->
<div class="mx-auto my-10 mxa-4 overflow-hidden" style="max-width: 700px; overflow: hidden !important;">

<!--- add funds form --->
    <div class="rounded flex-no-shrink bg-white" style="border-radius: 0.375rem; border-width: 1px;">
      <div class="text-left px-3 flex items-center">
        <h2 class="text-lg text-black py-2 font-normal fb">  Add Money </h2>
      </div>
      <hr>
    <form method="POST" action="{{ route('deposit') }}" id="depositForm">
    @csrf
      <div class="p-2 md:p-3 add_money_form">
        <label class="">
          <div class="flex content-center items-center justify-between text-sm">
            <p class="block font-medium text-gray-700 dark:text-gray-200 font-primary">Enter the amount</p>
          </div>
          <div class="mt-1 relative">
            <div class="relative">
              <input type="number" name="amount" id="amount"  placeholder="Amount" class="form-input relative block w-full disabled:cursor-not-allowed disabled:opacity-75 focus:outline-none border-0 rounded-md placeholder-gray-400 dark:placeholder-gray-500 text-sm px-3.5 py-2.5 shadow-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">
            </div>
          </div>
          <p class="text-red-500"></p>
        </label>
        <div class="text-center">
          <button type="submit" id="submitBtn" class="justify-center focus:outline-none disabled:cursor-not-allowed disabled:opacity-75 flex-shrink-0 font-medium rounded-md text-sm gap-x-1.5 px-2.5 py-2.5 shadow-sm text-white dark:text-gray-900 bg-pink-500 hover:bg-pink-600 disabled:bg-pink-500 dark:bg-pink-400 dark:hover:bg-pink-500 dark:disabled:bg-pink-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-500 dark:focus-visible:outline-primary-400 inline-flex items-center my-2 w-full text-center">
            <span id="buttonText">Click Here To Add Money</span>
            <span id="buttonSpinner" class="hidden ml-2">
              <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
          </button>
        </div>
      </div>
  </form>
    </div>
<!--- /add funds form --->

@if(!empty($settings->add_money_video_link ))
<!--- help video --->
    <div class="rounded bg-white my-4 md:my-10" style="border-radius: 0.375rem; border-width: 1px;">
      <div class="text-left px-3 flex items-center">
        <svg viewBox="0 0 24 24" class="text-green-100 mr-2" style="width: 22px; height: 22px;">
          <path fill="currentColor" d="M3 4V16H21V4H3M3 2H21C22.1 2 23 2.89 23 4V16C23 16.53 22.79 17.04 22.41 17.41C22.04 17.79 21.53 18 21 18H14V20H16V22H8V20H10V18H3C2.47 18 1.96 17.79 1.59 17.41C1.21 17.04 1 16.53 1 16V4C1 2.89 1.89 2 3 2M10.84 8.93C11.15 8.63 11.57 8.45 12 8.45C12.43 8.46 12.85 8.63 13.16 8.94C13.46 9.24 13.64 9.66 13.64 10.09C13.64 10.53 13.46 10.94 13.16 11.25C12.85 11.56 12.43 11.73 12 11.73C11.57 11.73 11.15 11.55 10.84 11.25C10.54 10.94 10.36 10.53 10.36 10.09C10.36 9.66 10.54 9.24 10.84 8.93M10.07 12C10.58 12.53 11.28 12.82 12 12.82C12.72 12.82 13.42 12.53 13.93 12C14.44 11.5 14.73 10.81 14.73 10.09C14.73 9.37 14.44 8.67 13.93 8.16C13.42 7.65 12.72 7.36 12 7.36C11.28 7.36 10.58 7.65 10.07 8.16C9.56 8.67 9.27 9.37 9.27 10.09C9.27 10.81 9.56 11.5 10.07 12M6 10.09C6.94 7.7 9.27 6 12 6C14.73 6 17.06 7.7 18 10.09C17.06 12.5 14.73 14.18 12 14.18C9.27 14.18 6.94 12.5 6 10.09Z"></path>
        </svg>
        <h2 class="text-lg text-black py-2 font-normal fb">  {{ __('How to add money') }} </h2>
      </div>
      <hr>
      <div class="flex-no-shrink p-2 mr-3 flex md:flex-row items-center flex-wrap justify-center sm:flex-col">
        <div class="w-full" align="center">
          <iframe src="{{ $settings->add_money_video_link }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" style="width: 100%; height: auto; min-height: 240px; min-width: 340px;"></iframe>
        </div>
      </div>
    </div>
<!--- /help video --->
@endif
 

</div>
<!--- /add funds --->
@endsection
@push('js')
<script>
document.getElementById('depositForm').addEventListener('submit', function(e) {
    // Show loading spinner and change button text
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('buttonText').textContent = 'Processing...';
    document.getElementById('buttonSpinner').classList.remove('hidden');
});

function payNow(pay_now_id) {
    $.ajax({
        url: '../inc/logged/pay_now.php',
        type: 'post',
        data: {
            pay_now_id: pay_now_id
        },
        beforeSend: function() {
            $("#pay-now #pay-now-" + pay_now_id).prop("disabled", true);
            $("#pay-now #pay-now-" + pay_now_id).html(
                "Pay  <span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span>"
            );
        },
        success: function(response) {
            $("#checkout_response").html(response);
            var spanText = $("#checkout_response span").text();
            if (spanText !== "") {
                alert(spanText);
            }

            $("#pay-now #pay-now-" + pay_now_id).prop("disabled", false);
            $("#pay-now #pay-now-" + pay_now_id).html("Pay");
        }
    });
}
</script>
@endpush