@extends('layout.master')
@section('title')
Register
@endsection
@section('content')
<div class="login">
  <div class="secondary-section">
    <div class="login-form mx-auto">
      <div class="w-auto px-0 md:px-3 pt-5 pb-1">
        <h1 class="text-2xl font-bold"> Register</h1>
        <div class="text-center my-3">
                       <div data-v-8b45d494="" class="social-login">
            <a href="{{ url('auth/redirect') }}" type="button" class="focus:outline-none focus-visible:outline-0 disabled:cursor-not-allowed disabled:opacity-75 flex-shrink-0 font-medium rounded-md text-sm gap-x-1.5 px-2.5 py-2.5 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 text-gray-900 dark:text-white bg-white hover:bg-gray-50 disabled:bg-white dark:bg-gray-900 dark:hover:bg-gray-800/50 dark:disabled:bg-gray-900 focus-visible:ring-2 focus-visible:ring-primary-500 dark:focus-visible:ring-primary-400 inline-flex items-center">
              <svg data-v-67dc65cc="" width="18" height="18" xmlns="http://www.w3.org/2000/svg" class="mr-2" data-v-8b45d494="">
                <g data-v-67dc65cc="" fill="#000" fill-rule="evenodd" data-v-8b45d494="">
                  <path data-v-67dc65cc="" d="M9 3.48c1.69 0 2.83.73 3.48 1.34l2.54-2.48C13.46.89 11.43 0 9 0 5.48 0 2.44 2.02.96 4.96l2.91 2.26C4.6 5.05 6.62 3.48 9 3.48z" fill="#EA4335" data-v-8b45d494=""></path>
                  <path data-v-67dc65cc="" d="M17.64 9.2c0-.74-.06-1.28-.19-1.84H9v3.34h4.96c-.1.83-.64 2.08-1.84 2.92l2.84 2.2c-1.7-1.57 2.68-3.88 2.68-6.62z" fill="#4285F4" data-v-8b45d494=""></path>
                  <path data-v-67dc65cc="" d="M3.88 10.78A5.54 5.54 0 0 1 3.58 9c0-.62.11-1.22.29-1.78L.96 4.96A9.008 9.008 0 0 0 0 9c0 1.45.35 2.82.96 4.04l2.92-2.26z" fill="#FBBC05" data-v-8b45d494=""></path>
                  <path data-v-67dc65cc="" d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.84-2.2c-.76.53-1.78.9-3.12.9-2.38 0-4.4-1.57-5.12-3.74L.97 13.04C2.45 15.98 5.48 18 9 18z" fill="#34A853" data-v-8b45d494=""></path>
                  <path data-v-67dc65cc="" fill="none" d="M0 0h18v18H0z" data-v-8b45d494=""></path>
                </g>
              </svg>  Login with Google </a>
          </div>
                    <div class="flex justify-between items-center pt-5">
            <hr class="w-1/5 px-2">
            <h1 class="text-gray-500 w-3/5 font-primary px-2 text-sm"> Or sign up with credentials</h1>
            <hr class="w-1/5 px-2">
          </div>
        </div>
        <form method="POST" action="{{ route('signup') }}">
          @csrf
          <div class="relative py-1">
        <label class="label-title">Full Name</label>
        <input type="text" placeholder="Name" class="form-input relative block w-full disabled:cursor-not-allowed disabled:opacity-75 focus:outline-none border-0 rounded-md placeholder-gray-400 dark:placeholder-gray-500 text-sm px-2.5 py-2.5 shadow-sm bg-transparent text-gray-900 dark:text-white ring-1 ring-inset dark:ring-black-900 focus:ring-2 focus:ring-black-900 dark:focus:ring-black-900" id="username" value="" name="name">
        @error('name')<p style='color: red;'>{{ $message }}</p>@enderror
                        <span id="username-alert"></span>
                              </div>
      <!---->
      <div class="relative py-1">
        <label class="label-title">Phone</label>
        <input type="text" placeholder="Phone" class="form-input py-1 block w-full disabled:cursor-not-allowed disabled:opacity-75 focus:outline-none border-0 rounded-md placeholder-gray-400 dark:placeholder-gray-500 text-sm px-2.5 py-2.5 shadow-sm bg-transparent text-gray-900 dark:text-white ring-1 ring-inset dark:ring-black-900 focus:ring-2 focus:ring-black-900 dark:focus:ring-black-900" id="phone" value="" name="phone">
        @error('phone')<p style='color: red;'>{{ $message }}</p>@enderror
                        <span id="phone-alert"></span>
                              </div>
      <!---->
      <div class="relative py-1">
        <label class="label-title">Email</label>
        <input type="text" placeholder="Email" class="form-input relative block w-full disabled:cursor-not-allowed disabled:opacity-75 focus:outline-none border-0 rounded-md placeholder-gray-400 dark:placeholder-gray-500 text-sm px-2.5 py-2.5 shadow-sm bg-transparent text-gray-900 dark:text-white ring-1 ring-inset dark:ring-black-900 focus:ring-2 focus:ring-black-900 dark:focus:ring-black-900" id="email" value="" name="email">
        @error('email')<p style='color: red;'>{{ $message }}</p>@enderror
                        <span id="email-alert"></span>
                              </div>
      <!---->
      <div class="relative py-1">
        <label class="label-title">Password</label>
        <input type="password" autocomplete="off" placeholder="Password" class="form-input relative block w-full disabled:cursor-not-allowed disabled:opacity-75 focus:outline-none border-0 rounded-md placeholder-gray-400 dark:placeholder-gray-500 text-sm px-2.5 py-2.5 shadow-sm bg-transparent text-gray-900 dark:text-white ring-1 ring-inset dark:ring-black-900 focus:ring-2 focus:ring-black-900 dark:focus:ring-black-900" id="password" value="" name="password">
        @error('password')<p style='color: red;'>{{ $message }}</p>@enderror
                        <span id="password-alert"></span>
                              </div>
      <!---->
      <div class="relative py-1">
        <label class="label-title">Confirm Password</label>
        <input type="password" autocomplete="off" placeholder="Password" class="form-input relative block w-full disabled:cursor-not-allowed disabled:opacity-75 focus:outline-none border-0 rounded-md placeholder-gray-400 dark:placeholder-gray-500 text-sm px-2.5 py-2.5 shadow-sm bg-transparent text-gray-900 dark:text-white ring-1 ring-inset dark:ring-red-400 focus:ring-2 focus:ring-black-500 dark:focus:ring-black-400" id="confirm_password" value="" name="password_confirmation">
        @error('password_confirmation')<p style='color: red;'>{{ $message }}</p>@enderror
                        <span id="confirm_password-alert"></span>
                              </div>
      <!---->
      <div class="text-center">
        <input type="hidden" name="terms" value="1" id="terms" checked required>
        <button type="submit" class="justify-center focus:outline-none disabled:cursor-not-allowed disabled:opacity-75 flex-shrink-0 font-medium rounded-md text-sm gap-x-1.5 px-2.5 py-2.5 shadow-sm text-white dark:text-gray-900 bg-pink-500 hover:bg-pink-600 disabled:bg-primary-500 dark:bg-primary-400 dark:hover:bg-primary-500 dark:disabled:bg-primary-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-500 dark:focus-visible:outline-primary-400 inline-flex items-center my-2 w-full text-center">Register</button>
      </div>
        </form>
      </div>
      <!---->
        <div class="mb-5 text-center subtitle-4 font-primary font-normal game-name"> Already member? <a href="{{ url('login') }}" class="text-pink-500 font-primary font-normal">Login</a> Now </div>
    </div>
  </div>
</div>
@endsection