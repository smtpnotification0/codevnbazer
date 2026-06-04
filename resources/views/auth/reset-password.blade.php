@extends('layout.master')
@section('title')
Reset Password
@endsection
@section('content')
<div class="login">
  <div class="secondary-section">
    <div class="login-form mx-auto">
      <div class="w-auto px-0 md:px-3 pt-5 pb-1">
        <h1 class="text-2xl font-bold"> Reset Password</h1>
         <form method="POST" action="{{ route('password.update') }}">
          @csrf
          <input type="hidden" name="token" value="{{ $token }}">
          @error('credential')<p style='color: red;'>{{ $message }}</p>@enderror
          <div class="my-2 relative">
            <div class="relative">
              <label class="font-primary font-normal">Email</label>
              <input type="text" placeholder="Enter Email" class="form-input relative block w-full disabled:cursor-not-allowed disabled:opacity-75 focus:outline-none border-0 rounded-md placeholder-gray-400 dark:placeholder-gray-500 text-sm px-2.5 py-2.5 shadow-sm bg-transparent text-gray-900 dark:text-white ring-1 ring-inset dark:ring-black-900 focus:ring-2 focus:ring-black-900 dark:focus:ring-black-900" name="email" value="">
              @error('email')<p style='color: red;'>{{ $message }}</p>@enderror
            </div>
            <div class="relative">
              <label class="font-primary font-normal">New Password</label>
              <input type="password" placeholder="Enter New Password" class="form-input relative block w-full disabled:cursor-not-allowed disabled:opacity-75 focus:outline-none border-0 rounded-md placeholder-gray-400 dark:placeholder-gray-500 text-sm px-2.5 py-2.5 shadow-sm bg-transparent text-gray-900 dark:text-white ring-1 ring-inset dark:ring-black-900 focus:ring-2 focus:ring-black-900 dark:focus:ring-black-900" name="password" value="">
            </div>
            <div class="relative">
              <label class="font-primary font-normal">Confirm Password</label>
              <input type="password" placeholder="Enter Confirm Password" class="form-input relative block w-full disabled:cursor-not-allowed disabled:opacity-75 focus:outline-none border-0 rounded-md placeholder-gray-400 dark:placeholder-gray-500 text-sm px-2.5 py-2.5 shadow-sm bg-transparent text-gray-900 dark:text-white ring-1 ring-inset dark:ring-black-900 focus:ring-2 focus:ring-black-900 dark:focus:ring-black-900" name="password_confirmation" value="">
            </div>
            <!---->
          </div>
          <div class="text-center">
            <button type="submit" class="justify-center focus:outline-none disabled:cursor-not-allowed disabled:opacity-75 flex-shrink-0 font-medium rounded-md text-sm gap-x-1.5 px-2.5 py-2.5 shadow-sm text-white dark:text-gray-900 bg-pink-500 hover:bg-primary-600 disabled:bg-primary-500 dark:bg-primary-400 dark:hover:bg-primary-500 dark:disabled:bg-primary-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-500 dark:focus-visible:outline-primary-400 inline-flex items-center my-2 w-full text-center"name="signin">Update Password</button>
          </div>
        </form>
      </div>
      <!---->
      <div class="mb-5 text-center subtitle-4 font-primary font-normal game-name"> Remember you password? <a href="/login" class="text-pink-500 font-primary font-normal">Login</a> Now </div>
    </div>
  </div>
</div>
@endsection