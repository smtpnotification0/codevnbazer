@extends('layout.master')
@section('title')
Account
@endsection
@section('content')
<!--- acoount page--->
<div>
  <div class="p-2">
    <div class="text-center">
      <button>
        <img src="{{ Auth::user()->picture }}" class="user_style_profile">
      </button>
    </div>
    <div class="text-center w-full">
      <span class="capitalize mt-1 theme-color-text font-16">Hi, {{ Auth::user()->name }}</span>
    </div>
    <div class="text-center w-full flex mb-3 md:mb-8 justify-center items-center">
      <span class="capitalize primary-font-color-text font-16">
        <b class="font-bold">Available Balance :  ৳{{ Auth::user()->balance }}</b>
      </span>
      <div class="border ml-2 p-1 rounded cursor-pointer">
        <svg viewBox="0 0 24 24" style="width: 16px; height: 16px;">
          <path fill="currentColor" d="M2 12C2 16.97 6.03 21 11 21C13.39 21 15.68 20.06 17.4 18.4L15.9 16.9C14.63 18.25 12.86 19 11 19C4.76 19 1.64 11.46 6.05 7.05C10.46 2.64 18 5.77 18 12H15L19 16H19.1L23 12H20C20 7.03 15.97 3 11 3C6.03 3 2 7.03 2 12Z"></path>
        </svg>
      </div>
    </div>
    <div style="max-width: 700px; margin: auto;">
      <div class="text-center grid md:grid-cols-4 grid-cols-2 md:gap-4 gap-3 my-2 md:my-5 mb-10 statics-container">
        <div class="bg-white statics">
          <h2 class="text-lg font-normal fb-normal statics-heading">৳{{ Auth::user()->balance }}</h2>
          <h2 class="text-lg primary-font-color-text font-normal fb-normal">Balance</h2>
        </div>
        <div class="bg-white statics">
          <h2 class="text-lg font-normal fb-normall statics-heading">0</h2>
          <h2 class="text-lg primary-font-color-text font-normal fb-normal">Total Order</h2>
        </div>
        <div class="bg-white statics">
          <h2 class="text-lg font-normal fb-normall statics-heading">৳0</h2>
          <h2 class="text-lg primary-font-color-text font-normal fb-normal">Total Spent</h2>
        </div>
        <div class="bg-white statics">
          <h2 class="text-lg font-normal fb-normall statics-heading">25</h2>
          <h2 class="text-lg primary-font-color-text font-normal fb-normal">Support PIN</h2>
        </div>
      </div>
      <div class="w-full text-left bg-white my-4 account-info-container">
        <div class="text-left px-3 flex items-center">
          <svg class="mr-2" fill="#000000" width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M12,1A11,11,0,1,0,23,12,11.013,11.013,0,0,0,12,1Zm0,20a9.641,9.641,0,0,1-5.209-1.674,7,7,0,0,1,10.418,0A9.167,9.167,0,0,1,12,21Zm6.694-3.006a8.98,8.98,0,0,0-13.388,0,9,9,0,1,1,13.388,0ZM12,6a4,4,0,1,0,4,4A4,4,0,0,0,12,6Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,12,12Z" />
          </svg>
          <h2 class="text-lg primary-font-color-text py-2 font-normal fb"> User Information</h2>
        </div>
        <hr>
        <div class="px-4 py-2">
          <h4 class="text-lg primary-font-color-text py-2 font-normal fb">email : {{ Auth::user()->email }}</h2>
          <h4 class="text-lg primary-font-color-text py-2 font-normal fb">Phone : {{ Auth::user()->phone }}</h2>
        </div>
      </div>
    </div>
  </div>
</div>
<!--- /acoount page--->
@endsection