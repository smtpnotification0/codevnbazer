@if (session('message'))
    <script type="text/javascript">
        window.onload = function() {
            toastr.{{ session('message_type') }}("{{ session('message') }}");
        }
    </script>
@endif
<!DOCTYPE html>
<html lang="en">

<head>
<head>
    <script>
    </script>
</head>
<meta charset="utf-8">
    <title>@yield('title', 'Site Title')</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $settings->seo_description }}" />
    <meta name="keywords" content="{{ $settings->seo_keywords }}" />

    @if (!empty($settings->favicon))
        {{-- Favicon --}}
        <link rel="shortcut icon" type="image/png" href="{{ get_image($settings->favicon) }}">
    @endif

    @if ($settings->enable_pwa)
        @PWA
    @endif

    {{-- Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Site Title')">
    <meta property="og:description" content="{{ $settings->seo_description }}">
    @if (!empty($settings->fb_og_image))
        <meta property="og:image" content="{{ get_image($settings->fb_og_image) }}">
    @endif

    {{-- Twitter --}}
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'Site Title')">
    <meta property="twitter:description" content="{{ $settings->seo_description }}">
    @if (!empty($settings->twitter_og_image))
        <meta property="twitter:image" content="{{ get_image($settings->twitter_og_image) }}">
    @endif

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('assets/template/css/bootstrap/bootstrap.min.css') }}">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="{{ asset('assets/template/fonts/fontawesome/css/all.min.css') }}">

    {{-- Toastr --}}
    <link rel="stylesheet" href="{{ asset('assets/template/js/toastr/toastr.min.css') }}">

    {{-- Custom --}}
    <link rel="stylesheet" href="{{ asset('assets/template/css/styles.css') }}?<?=rand(0000,9999)?>">

    <style>
        :root {
            --theme-color: {{ $settings->theme_color }};
            --logo-color: {{ $settings->logo_color }};
            --background-color: {{ $settings->background_color }};
            --primary-font-color: {{ $settings->font_color }};
            --navigation-background-color: {{ $settings->navigation_background_color }};
            --navigation-font-color: {{ $settings->navigation_font_color }};
            --footer-color: {{ $settings->footer_color }};
            --footer-font-color: {{ $settings->footer_font_color }};
            --content-box-color: {{ $settings->content_box_color }};

            @if ($settings->background_image)
                --background-image: linear-gradient(350deg, rgb(244, 249, 255), rgba(237, 244, 255, 0.79)),
                    url(data:image/png;base64,UklGRtQEAABXRUJQVlA4TMgEAAAvj8FjAAVFA4Bqa9f7P14MQ4yIiBgSsRgW316rZvat/3ZOEf135LaNJGVOc3Nplk5c8QfO8avRaXkRWzSJMwnO2STKiUVTMWJoE02L4ldsGHwygXeHpUKw1OFMGJK3gS8r5EeuWk4KaKnosum7bvrOZhcthXAYqjpaIL8GxVvYZiz66o7BwCiFkAxsgV03/LowWxj5Am15/DrU00fDYaTpjyo+n9GrATIcB7BecDyzXnCHJoaPLPqzQl8cvwrlcaKgO47Eh1gG5XEaxodgr/wzyA/BHbLyL8s/2EVUfjEL8ENwVGIID8EZ6sL4NSaoN1KxzkgcLQHJlRSWNjtQZ7pRzS7WPjtmS0gXKZ0wzQXX6PKNFPFccMErVSFIbYqhxhvVCsRtUfzIB5a7vkSTr18DSoPjcfRuIhipDAQXwsn31lFsA/WRJoIzUrlzkUJIE8E+8oBzImhjwUBpIJiDp0Xxa9MO3zREOJvgWU0Fm4aIRibB1EyC/wnmx+SmBbENnsY4Lz944nJQCA4DwRnnhfm4nzrAtFzgwdPYzwvjQHBUCCZeDb+OxDKfnTIkuTWdiimp3lrZ7pHnM8JRbq1sgVgVguO1lRXCohDM6Put6VQI7rdWVhLkOQZhwr4UflVrpAldZivtUFaBDUOZxItgrfvQIP4owyiGUY+hake9y4xISsHF8UL4HcAGG9XqPQBbvAeD/SRQTB4KSAODh5Isxi7DsQ5+lG3e3GHy5sI7eozB5DEeRo+R1sFvHR78Ovit445qHfyyDYNYit/1Dre87g73l5Ufr5/fu/6/8Ae+WQc/EMtJjc9f957PX2d8/hbTd30d/F7Yn8b37E/Ty/rTTOvg16Fa/nrS6+a3Ypjf0DS/8Uvnt3XwO9mxUm8w+RvN6G/Aj9aISg4d42MqMPobzeRvBJ3gC7qF8DsbUpW53AxXqQmS0f9TCC5IcntYdJWNZ/D/xOj/JUjXDxWyQnAlbOdS+J1SvMkfj6Dwx7MYjHU38seTxh9PI3/cWfzxrPDHweaP+yLnMvit4z7vX2B+PvCoFyI07K/VSE6/+Caa/bXUBuqTZn9N9GtojmI17K8hjXoiDn5R/I5WPNbrlbKLtU03MdOtW+2IZbZtKW243xm4yUTwbL+zTwWH4X5nkykGxH7rSNN0a7TV6NJFZkX/g2Ed/JSX2OLpsOw/O8v+80m+6wTf959FN815Oi37z86y/3yQl8HV+Lr4nf1HD+m9h4jSPy4f4LqgAQNdcg6L43dWKCgGHzX7l+VnYnxZfsZnMgjGAvVcHr+TTBcN/TPv1LtBEzs6l8HvZXc7mCxVX3lHVU13VGi8U18Hv9+cO/X/cOTet/+593nuffufez++Mb/tf+5dmXvf/ufe65flt/3PvT819779z72/f+59+597H+fet/+591Huffufe3efwW/7n3v/dz33vv3PvY9y79v/3DuOa/ufe3cfwG/7n3v349r+5963/7n3NeTet/+593vuffufe/+Y3Pv2P/cOGgzb/9z79j/3XpaRey/btNz79j/3Dg8M2//cO8wEb/9z72+fe9/+595Bh2H7n3vf/ufev3ruffufex/k3rf/ufcPzL1v/3Pvv2659+1/7n1Fufftf+59+597PwE=);
            @endif
        }
    </style>

    <style>
        @if (!$settings->footer_menu)
            .sticky-footer-container {
                display: none;
            }

            body {
                margin-bottom: 0px;
            }
        @endif
    </style>

    @stack('style')


    {{-- extra --}}
    <link rel="stylesheet" href="{{ asset('assets/template/css/tailwindcss.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/template/css/custom-styles.css') }}?<?=rand(0000,9999)?>"> 

    {!! $settings->header_tags !!}
</head>

<body>
<div class="body-bg">
@auth
<div class="header">
    <div class="container m-auto p-2 py-3 md:py-5 md:px-0">
        <nav class="flex items-center justify-between">
            <a href="/" class="">
                <img alt="Logo" data-nuxt-img="" srcset="{{ get_image($settings->logo) }} 1x, {{ get_image($settings->logo) }} 2x" class="w-28 md:w-48 logo" src="{{ get_image($settings->logo) }}">
            </a>
            <div class="relative">
                <div class="flex items-center">
                    <nav class="text-left hidden md:block">
                        <div class="w-full flex-grow flex items-center lg:w-auto">
                            <div class="text-sm flex-grow animated jackinthebox mx-auto">
                                <a href="/#topup" class="block inline-block text-md font-bold mx-2 p-1 rounded-lg fb-normal link"> Topup 
                                </a>
                                <a href="/contactus" class="block inline-block text-md font-bold mx-2 p-1 rounded-lg fb-normal link"> Contact Us 
                                </a>
                            </div>
                        </div>
                    </nav>
                    <a href="{{ route('account') }}" class="router-link-active router-link-exact-active flex items-center text-md px-4 py-2 shadow-md hover:shadow-2xl border rounded-full text-black bg-pink-500 text-white font-primary" aria-current="page">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet w-4 h-4"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"></path><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>
                        </svg>
                        <span class="ml-1">{{ Auth::user()->balance }}৳</span>
                    </a>
                    <div class="flex items-center cursor-pointer px-2 duration-75 w-16 profile">
                        <img src="{{ Auth::user()->picture }}" class="rounded-full">
                    </div>
                </div>
                <div id="userMenu" class="hidden bg-white rounded shadow-md absolute mt-12 top-0 right-0 min-w-full overflow-auto z-30">
                    <nav class="flex fixed items-center justify-between h-16 bg-white text-gray-700 border-b border-gray-200 z-10 gosizi-navlist" style="position: fixed; bottom: 0px;">
                      <div class="z-10 fixed inset-0 transition-opacity">
                        <div tabindex="0" class="absolute inset-0 bg-black opacity-50 nav-overlay"></div>
                      </div>
                      <aside class="transform top-0 right-0 w-64 bg-white fixed h-full overflow-auto ease-in-out transition-all duration-300 z-30 translate-x-0">
                        <button id="userButton" class="flex items-center focus:outline-none p-3">
                          <img src="{{ Auth::user()->picture }}" backgroundcolor="#D81C4B" color="#fff" style="height: 50px;">

                          <div>
                            <div class="text-left w-full">
                              <span class="px-3 font-normal font-primary">Hi, 
                                {{ Auth::user()->name }}</span>
                            </div>
                            <div class="text-left">
                              <span class="px-3">{{ Auth::user()->email }}</span>
                            </div>
                          </div>
                        </button>
                        <div class="w-full mx-auto text-center">
                        <a href="{{ route('logout') }}" class="inline-block">
                          <button type="button" class="align-middle bg-pink-500 rounded-full mx-auto text-center hover:bg-pink-400 text-center px-1 py-2 text-white text-sm font-semibold rounded-lg inline-block shadow-lg px-6 mb-2 d-block btn-primary gosizi-btn">
                            <!---->
                            <span class="flex items-center justify-center p-0">
                              <span class="mr-2">
                                <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="power-off" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 0.83rem;">
                                  <path fill="currentColor" d="M388.5 46.3C457.9 90.3 504 167.8 504 256c0 136.8-110.8 247.7-247.5 248C120 504.3 8.2 393 8 256.4 7.9 168 54 90.3 123.5 46.3c5.8-3.7 13.5-1.8 16.9 4.2l11.8 20.9c3.1 5.5 1.4 12.5-3.9 15.9C92.8 122.9 56 185.1 56 256c0 110.5 89.5 200 200 200s200-89.5 200-200c0-70.9-36.8-133.1-92.3-168.6-5.3-3.4-7-10.4-3.9-15.9l11.8-20.9c3.3-6.1 11.1-7.9 16.9-4.3zM280 276V12c0-6.6-5.4-12-12-12h-24c-6.6 0-12 5.4-12 12v264c0 6.6 5.4 12 12 12h24c6.6 0 12-5.4 12-12z"></path>
                                </svg>
                              </span>
                              <span class="no-underline text-xs">Logout</span>
                            </span>
                            <!---->
                          </button>
                      </a>
                        </div>
                        <hr>
                        <a href="{{asset('account')}}" class="text-gray-900 no-underline">
                          <span class="flex items-center p-4 font-primary">
                            <span class="mr-2">
                              <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6">
                                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                              </svg>
                            </span> My Account </span>
                        </a>
                        <a href="{{asset('orders')}}" class="text-gray-900 no-underline">
                          <span class="flex items-center p-4 font-primary">
                            <span class="mr-2">
                              <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                                <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                              </svg>
                            </span> My Orders </span>
                        </a>
                        <a href="{{asset('codes')}}" class="text-gray-900 no-underline">
                          <span class="flex items-center p-4 font-primary">
                            <span class="mr-2">
                              <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                              </svg>
                            </span>
                            <span> My Codes </span>
                          </span>
                        </a>
                        {{-- ✅ My Transaction --}}
<a href="{{ asset('transactions') }}" class="text-gray-900 no-underline">
  <span class="flex items-center p-4 font-primary">
    <span class="mr-2">
      {{-- List icon --}}
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
      </svg>
    </span>
    My Transaction
  </span>
</a>
                        <a href="{{asset('add-funds')}}" class="text-gray-900 no-underline">
                          <span class="flex items-center p-4 font-primary">
                            <span class="mr-2">
                              <svg class="w-6 h-6" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M3 0V3H0V5H3V8H5V5H8V3H5V0H3M10 3V5H19V7H13C11.9 7 11 7.9 11 9V15C11 16.1 11.9 17 13 17H19V19H5V10H3V19C3 20.1 3.89 21 5 21H19C20.1 21 21 20.1 21 19V16.72C21.59 16.37 22 15.74 22 15V9C22 8.26 21.59 7.63 21 7.28V5C21 3.9 20.1 3 19 3H10M13 9H20V15H13V9M16 10.5A1.5 1.5 0 0 0 14.5 12A1.5 1.5 0 0 0 16 13.5A1.5 1.5 0 0 0 17.5 12A1.5 1.5 0 0 0 16 10.5Z"></path>
                              </svg>
                            </span> Add Fund </span>
                        </a>
                        <a href="{{ asset('contact-us') }}" class="text-gray-900 no-underline">
  <span class="flex items-center p-4 font-primary">
    <span class="mr-2">
      {{-- Info icon (from Contact Us) --}}
      <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        viewBox="0 0 24 24" class="w-6 h-6">
        <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
    </span>
    Contact Us
  </span>
</a>
                        <hr>
                        <div class="w-full mx-auto text-center mt-3">
                          <a href="https://wa.me/+88{{ $settings->whatsapp_number }}" target="_blank" class="align-middle bg-pink-500 rounded-full mx-auto text-center hover:bg-pink-400 text-center px-1 py-2 text-white text-sm font-semibold rounded-lg inline-block shadow-lg w-32 px-6 mb-2 d-block btn-primary gosizi-btn">
                            <span class="flex items-center justify-center">
                              <span class="mr-2">
                                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="20" width="20" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M12 2C6.486 2 2 6.486 2 12v4.143C2 17.167 2.897 18 4 18h1a1 1 0 0 0 1-1v-5.143a1 1 0 0 0-1-1h-.908C4.648 6.987 7.978 4 12 4s7.352 2.987 7.908 6.857H19a1 1 0 0 0-1 1V18c0 1.103-.897 2-2 2h-2v-1h-4v3h6c2.206 0 4-1.794 4-4 1.103 0 2-.833 2-1.857V12c0-5.514-4.486-10-10-10z"></path>
                                </svg>
                              </span>
                              <span class="no-underline">Support</span>
                            </span>
                          </a>
                        </div>
                      </aside>
                    </nav>
                  </div>
            </div>
        </nav>
    </div>
</div>
@else
<div class="header">
  <div class="container m-auto p-2 py-3 md:py-5 md:px-0">
    <nav class="flex items-center justify-between">
      <a href="/" class="">
        <img src="{{ get_image($settings->logo) }}"  alt="{{ $settings->site_name }}" data-nuxt-img="" srcset="{{ get_image($settings->logo) }} 1x, {{ get_image($settings->logo) }} 2x" class="w-28 md:w-48 logo">
      </a>
      <div class="relative">
        <div class="flex items-center">
          <nav class="text-left hidden md:block">
            <div class="w-full flex-grow flex items-center lg:w-auto">
              <div class="text-sm flex-grow animated jackinthebox mx-auto">
                <a href="#topup" class="block inline-block text-md font-bold mx-2 p-1 rounded-lg fb-normal link"> Topup </a>
                <a href="page/contact-us.html" class="block inline-block text-md font-bold mx-2 p-1 rounded-lg fb-normal link"> Contact Us </a>
              </div>
            </div>
          </nav>
          <div class="flex items-center">
            <a href="/login" class="btn-pro  btn-register rounded ml-2 border-2 border-pink-500 bg-pink-500 text-white"> login </a>
          </div>
        </div>
       <!---->
      </div>
    </nav>
  </div>
</div>
@endauth

@yield('content')
    
   

    

 <footer data-v-4c1ace0e="" class="mb-16 md:mb-0 text-gray-200 border-t-2 footer-bg">
            <section data-v-4c1ace0e="" class="container mx-auto pb-8">
              <div data-v-4c1ace0e="">
                <div data-v-4c1ace0e="" class="m-auto flex flex-wrap">
                  <div data-v-4c1ace0e="" class="w-full md:w-4/6 m-auto flex flex-wrap my-0">
                    <div data-v-4c1ace0e="" class="w-full md:w-1/3 px-5 md:px-0">
                      <div data-v-4c1ace0e="" class="text-lg fb mt-10 uppercase text-white font-normal tracking-wider footer-title">STAY CONNECTED</div>
                      <div data-v-4c1ace0e="" class="m-auto flex flex-wrap ff-bf mt-2 footer_nav">
                        <div data-v-4c1ace0e="" class="w-full mt-1 md:mt-2">
                          <a data-v-4c1ace0e="" class="flex ff-bf ffont-medium" href="#" style="line-height: 17px;">
                            <span data-v-4c1ace0e="" class="text-xs">
                             <p class="footer_description">কোন সমস্যায় পড়লে  telegram  এ যোগাযোগ করবেন। তাহলে দ্রুত সমাধান পেয়ে যাবেন।</p>
                            </span>
                          </a>
                          <div data-v-4c1ace0e="" class="mt-1 md:mt-2">
                            <div data-v-4c1ace0e="" class="flex flex-wrap">

                                 
                              <div data-v-4c1ace0e="" class="social_icon mx-2 my-3 ml-0" data-aos="zoom-in" data-aos-duration="500">
                                <a data-v-4c1ace0e="" href="{{ $settings->facebook_link }}" target="_blank" aria-label="Social Icon" rel="noopener">
                                  <svg data-v-4c1ace0e="" viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                    <path data-v-4c1ace0e="" d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                  </svg>
                                </a>
                              </div>
                                
                                              <div data-v-4c1ace0e="" class="social_icon mx-2 my-3" data-aos="zoom-in" data-aos-duration="500">
                                <a data-v-4c1ace0e="" href="{{ $settings->messenger_link }}" target="_blank" aria-label="Social Icon" rel="noopener">
                                  <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                  <path d="M12 2C6.48 2 2 6.19 2 11.5c0 3.67 2.14 6.85 5.37 8.44V22l4.02-2.21c.86.21 1.75.32 2.61.32 5.52 0 10-4.19 10-9.5S17.52 2 12 2zm2.92 9.59l-3.14 3.33-2.25-2.4-5.51 5.87L9.64 8.91l3.14-3.33 2.25 2.4 5.51-5.87L14.92 11.59z"></path>
                                </svg>

                                </a>
                              </div>
                

                     
                              <div data-v-4c1ace0e="" class="social_icon mx-2 my-3" data-aos="zoom-in" data-aos-duration="500">
                                <a data-v-4c1ace0e="" href="{{ $settings->youtube_link }}" target="_blank" aria-label="Social Icon" rel="noopener">
                                  <svg data-v-4c1ace0e="" viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                    <path data-v-4c1ace0e="" d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path>
                                    <polygon data-v-4c1ace0e="" points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>
                                  </svg>
                                </a>
                              </div>
                              <div data-v-4c1ace0e="" class="social_icon mx-2 my-3" data-aos="zoom-in" data-aos-duration="500">
                                <a data-v-4c1ace0e="" href="mailto:{{ $settings->email_address }}" target="_blank" aria-label="Social Icon" rel="noopener">
                                  <svg data-v-4c1ace0e="" viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                    <path data-v-4c1ace0e="" d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline data-v-4c1ace0e="" points="22,6 12,13 2,6"></polyline>
                                  </svg>
                                </a>
                              </div>
                                
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div data-v-4c1ace0e="" class="w-full md:w-2/6 footer_nav pt-5 px-5 md:px-0">
                    <div data-v-4c1ace0e="" class="md:ml-20">
                      <div data-v-4c1ace0e="" class="text-lg fb mt-1 uppercase text-white font-normal tracking-wider pb-3 footer-title">SUPPORT CENTER</div>

                      <div data-v-f9030ba7="" class="ff-bf ffont-medium">
                        <a data-v-f9030ba7="" href="{{ $settings->whatsapp_number }}" target="_blank" class="rounded-md p-3 mt-2 md:mt-4 flex footer-contact-icon1 border">
                          <div data-v-f9030ba7="" class="footer-contact-icon">
                            <svg data-v-f9030ba7="" height="34" color="#dfdfdf" width="34" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="whatsapp" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-whatsapp fa-w-14 fa-2x mr-2 pl-2"><path data-v-f9030ba7="" fill="currentColor" d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z" class=""></path>
                            </svg>
                          </div>
                          <div data-v-f9030ba7="" class="ml-2 pl-2" style="border-left: 2px solid rgb(177, 177, 177);">
                            <p data-v-f9030ba7="" class="text-primary text-opacity-70 text-xs font-normal" style="color: rgb(255, 255, 255);">
                              Help line [{{ $settings->support_time }}] 
                            </p>
                            <span data-v-f9030ba7="" class="number">
                              Whatsapp HelpLine 
                            </span>
                          </div>
                        </a>
                        <a data-v-f9030ba7="" href="{{ $settings->telegram_link }}" target="_blank" class="rounded-md p-3 mt-2 md:mt-4 flex footer-contact-icon1 border">
                          <div class="footer-contact-icon" data-v-f9030ba7="">
                            <svg stroke="currentColor" fill="currentColor" height="34" color="#dfdfdf" width="34" stroke-width="0" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" data-v-f9030ba7=""><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294.26.006.549-.1.868-.32 2.179-1.471 3.304-2.214 3.374-2.23.05-.012.12-.026.166.016.047.041.042.12.037.141-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8.154 8.154 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629.093.06.183.125.27.187.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.426 1.426 0 0 0-.013-.315.337.337 0 0 0-.114-.217.526.526 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09z" data-v-f9030ba7=""></path>
                            </svg>
                          </div>
                          <div class="ml-2 pl-2" style="border-left:2px solid #b1b1b1;" data-v-f9030ba7="">
                            <span class="number" data-v-f9030ba7="">
                              টেলিগ্রামে সাপোর্ট
                            </span>
                          </div>
                        </a>
                      </div>
            
                    </div>
                  </div>
                </div>
              </div>
            </section>
<div style="border-top:2px solid #c1bcbc1c; background-color:  {{ $settings->footer_color ?? '#0b1150' }};">
  <div class="pb-5 px-5 m-auto pt-5 text-sm flex flex-col items-center justify-center text-white text-center" style="font-family: 'Segoe UI', sans-serif;">
    <div class="mt-2 text-center fb tracking-wide" data-v-4c1ace0e="" style="color:#c5c5c5; opacity:0.9; font-weight:600;">
      © Copyright 2025. All Rights Reserved. Developer
      </a>
    </div>
  </div>
</div>




            @if (Auth::guest()) 
            <div class="sticky-footer-container">
                    <div class="sticky-footer-item">
                        <a href="/">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <span><svg data-v-7cfb45cd="" width="25" height="25" viewBox="0 0 42 42" class="inline-block mb-1"><g data-v-7cfb45cd="" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><path data-v-7cfb45cd="" d="M21.0847458,3.38674884 C17.8305085,7.08474576 17.8305085,10.7827427 21.0847458,14.4807396 C24.3389831,18.1787365 24.3389831,22.5701079 21.0847458,27.6548536 L21.0847458,42 L8.06779661,41.3066256 L6,38.5331279 L6,26.2681048 L6,17.2542373 L8.88135593,12.4006163 L21.0847458,2 L21.0847458,3.38674884 Z" fill="currentColor" fill-opacity="0.1"></path> <path data-v-7cfb45cd="" d="M11,8 L33,8 L11,8 Z M39,17 L39,36 C39,39.3137085 36.3137085,42 33,42 L11,42 C7.6862915,42 5,39.3137085 5,36 L5,17 L7,17 L7,36 C7,38.209139 8.790861,40 11,40 L33,40 C35.209139,40 37,38.209139 37,36 L37,17 L39,17 Z" fill="currentColor"></path> <path data-v-7cfb45cd="" d="M22,27 C25.3137085,27 28,29.6862915 28,33 L28,41 L16,41 L16,33 C16,29.6862915 18.6862915,27 22,27 Z" stroke="currentColor" stroke-width="2" fill="currentColor" fill-opacity="0.1"></path> <rect data-v-7cfb45cd="" fill="currentColor" transform="translate(32.000000, 11.313708) scale(-1, 1) rotate(-45.000000) translate(-32.000000, -11.313708) " x="17" y="10.3137085" width="30" height="2" rx="1"></rect> <rect data-v-7cfb45cd="" fill="currentColor" transform="translate(12.000000, 11.313708) rotate(-45.000000) translate(-12.000000, -11.313708) " x="-3" y="10.3137085" width="30" height="2" rx="1"></rect></g></svg></span>
                                <span>Home</span>
                            </div>
                        </a>
                    </div>
                
                
                                            
                
                    <div class="sticky-footer-item">
                        <a href="https://youtu.be/">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <span><svg data-v-7cfb45cd="" viewBox="0 0 24 24" width="25" height="25" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="inline-block mb-1"><path data-v-7cfb45cd="" d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path> <polygon data-v-7cfb45cd="" points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg></span>
                                <span>Tutorial</span>
                            </div>
                        </a>
                    </div>
                                            
                
                    <div class="sticky-footer-item">
                        <a href="https://wa.me/{{ $settings->whatsapp_number }}">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <span><i class="fa-brands fa-whatsapp fa-2xl"></i></span>
                                <span>Whatsapp</span>
                            </div>
                        </a>
                    </div>
                
                
                                            
                
                    <div class="sticky-footer-item">
                        <a href="login">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <span><i class="fa-solid fa-circle-user"></i></span>
                                <span>Account</span>
                            </div>
                        </a>
                    </div>
            </div>
         @else
            <div class="sticky-footer-container">
                    <div class="sticky-footer-item">
                        <a href="/">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <span><svg data-v-7cfb45cd="" width="25" height="25" viewBox="0 0 42 42" class="inline-block mb-1"><g data-v-7cfb45cd="" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><path data-v-7cfb45cd="" d="M21.0847458,3.38674884 C17.8305085,7.08474576 17.8305085,10.7827427 21.0847458,14.4807396 C24.3389831,18.1787365 24.3389831,22.5701079 21.0847458,27.6548536 L21.0847458,42 L8.06779661,41.3066256 L6,38.5331279 L6,26.2681048 L6,17.2542373 L8.88135593,12.4006163 L21.0847458,2 L21.0847458,3.38674884 Z" fill="currentColor" fill-opacity="0.1"></path> <path data-v-7cfb45cd="" d="M11,8 L33,8 L11,8 Z M39,17 L39,36 C39,39.3137085 36.3137085,42 33,42 L11,42 C7.6862915,42 5,39.3137085 5,36 L5,17 L7,17 L7,36 C7,38.209139 8.790861,40 11,40 L33,40 C35.209139,40 37,38.209139 37,36 L37,17 L39,17 Z" fill="currentColor"></path> <path data-v-7cfb45cd="" d="M22,27 C25.3137085,27 28,29.6862915 28,33 L28,41 L16,41 L16,33 C16,29.6862915 18.6862915,27 22,27 Z" stroke="currentColor" stroke-width="2" fill="currentColor" fill-opacity="0.1"></path> <rect data-v-7cfb45cd="" fill="currentColor" transform="translate(32.000000, 11.313708) scale(-1, 1) rotate(-45.000000) translate(-32.000000, -11.313708) " x="17" y="10.3137085" width="30" height="2" rx="1"></rect> <rect data-v-7cfb45cd="" fill="currentColor" transform="translate(12.000000, 11.313708) rotate(-45.000000) translate(-12.000000, -11.313708) " x="-3" y="10.3137085" width="30" height="2" rx="1"></rect></g></svg></span>
                                <span>Home</span>
                            </div>
                        </a>
                    </div>
                
                
                     
                                            
                    <div class="sticky-footer-item">
                        <a href="/add-funds">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <span><svg data-v-7cfb45cd="" width="25" height="25" viewBox="0 0 24 24" class="w-6 h-6 inline-block mb-1">
  <g data-v-7cfb45cd="" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
    <path data-v-7cfb45cd="" fill="currentColor" d="M3 0V3H0V5H3V8H5V5H8V3H5V0H3M10 3V5H19V7H13C11.9 7 11 7.9 11 9V15C11 16.1 11.9 17 13 17H19V19H5V10H3V19C3 20.1 3.89 21 5 21H19C20.1 21 21 20.1 21 19V16.72C21.59 16.37 22 15.74 22 15V9C22 8.26 21.59 7.63 21 7.28V5C21 3.9 20.1 3 19 3H10M13 9H20V15H13V9M16 10.5A1.5 1.5 0 0 0 14.5 12A1.5 1.5 0 0 0 16 13.5A1.5 1.5 0 0 0 17.5 12A1.5 1.5 0 0 0 16 10.5Z"></path>
  </g>
</svg></span>
                                <span>Add Money</span>
                            </div>
                        </a>
                    </div>
                
                           
                    <div class="sticky-footer-item">
                        <a href="/orders">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <span><svg data-v-7cfb45cd="" width="25" height="25" viewBox="0 0 24 24" class="w-6 h-6 inline-block mb-1">
  <g data-v-7cfb45cd="" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor">
    <path data-v-7cfb45cd="" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
  </g>
</svg></span>
                                <span>My Orders</span>
                            </div>
                        </a>
                    </div>
                    
                    <div class="sticky-footer-item">
                        <a href="/codes">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <span><svg data-v-7cfb45cd="" width="24" height="24" viewBox="0 0 24 24" class="css-i6dzq1 inline-block mb-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><rect data-v-7cfb45cd="" x="3" y="3" width="7" height="7"></rect> <rect data-v-7cfb45cd="" x="14" y="3" width="7" height="7"></rect> <rect data-v-7cfb45cd="" x="14" y="14" width="7" height="7"></rect> <rect data-v-7cfb45cd="" x="3" y="14" width="7" height="7"></rect></svg></span>
                                <span>My Codes</span>
                            </div>
                        </a>
                    </div>
                
                    <div class="sticky-footer-item">
                        <a href="/account">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <span><i class="fa-solid fa-circle-user"></i></span>
                                <span>Account</span>
                            </div>
                        </a>
                    </div>
          </div>
       @endif
 </footer>
 
{{-- Custom PWA Install Banner (Responsive: Mobile Slim Updated, Desktop Card) --}}
@if ($settings->enable_pwa)
<div id="pwa-install-banner" class="pwa-install-banner" style="display: none;">
    <div class="pwa-desktop-content">
        <div class="pwa-card-header">
            <span class="pwa-card-title">Install App</span>
            <button class="pwa-close-btn" id="pwa-close-desktop">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <p class="pwa-card-desc">Install our app for a better experience</p>
        <button class="pwa-install-btn-large" id="pwa-btn-desktop">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Install Now
        </button>
    </div>

    <div class="pwa-mobile-content">
        <div class="pwa-mobile-left">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            <span class="pwa-mobile-text">Install App</span>
        </div>
        <div class="pwa-mobile-right">
            <button class="pwa-mobile-btn" id="pwa-btn-mobile">Install</button>
            <button class="pwa-mobile-close" id="pwa-close-mobile">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
    </div>
</div>

<style>
    .pwa-install-banner {
        position: fixed;
        background-color: {{ $settings->theme_color }};
        color: white;
        z-index: 999999;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    /* --- Desktop Styles --- */
    @media (min-width: 769px) {
        .pwa-install-banner {
            bottom: 40px;
            right: 40px;
            width: 380px;
            padding: 24px;
            border-radius: 16px;
        }
        .pwa-mobile-content { display: none; }
        .pwa-desktop-content { display: block; }
        
        .pwa-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .pwa-card-title { font-size: 22px; font-weight: 700; }
        .pwa-card-desc { font-size: 16px; margin-bottom: 24px; opacity: 0.95; }
        .pwa-install-btn-large {
            width: 100%;
            background: white;
            color: {{ $settings->theme_color }};
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .pwa-close-btn { background: transparent; border: none; cursor: pointer; }
    }

    /* --- Mobile Styles (Padding Updated) --- */
    @media (max-width: 768px) {
        .pwa-install-banner {
            bottom: 95px;
            left: 10px;
            right: 10px;
            /* ওপর-নিচে ১ পিক্সেল বাড়ানো হয়েছে (১০ থেকে ১২ করা হয়েছে) */
            padding: 12px 14px; 
            border-radius: 10px;
        }
        .pwa-desktop-content { display: none; }
        .pwa-mobile-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .pwa-mobile-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .pwa-mobile-text { font-weight: 600; font-size: 15px; }
        .pwa-mobile-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .pwa-mobile-btn {
            background: white;
            color: black;
            border: none;
            padding: 5px 14px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
        }
        .pwa-mobile-close { background: transparent; border: none; cursor: pointer; }
    }
</style>

<script>
    (function() {
        let deferredPrompt;
        const banner = document.getElementById('pwa-install-banner');
        
        if (localStorage.getItem('pwa_closed_at') > Date.now()) return;

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            banner.style.display = 'block';
        });

        const installApp = async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') banner.style.display = 'none';
                deferredPrompt = null;
            } else {
                alert('ব্রাউজার মেনু থেকে "Add to Home Screen" এ ক্লিক করুন।');
            }
        };

        const closeBanner = () => {
            banner.style.display = 'none';
            localStorage.setItem('pwa_closed_at', Date.now() + (24 * 60 * 60 * 1000));
        };

        document.getElementById('pwa-btn-desktop').addEventListener('click', installApp);
        document.getElementById('pwa-btn-mobile').addEventListener('click', installApp);
        document.getElementById('pwa-close-desktop').addEventListener('click', closeBanner);
        document.getElementById('pwa-close-mobile').addEventListener('click', closeBanner);
    })();
</script>
@endif
    


    
    <script src="{{asset('assets/template/js/jquery-3.7.1.min.js')}}"></script>

    
    <script src="{{asset('assets/template/js/bootstrap/bootstrap.bundle.min.js')}}"></script>

    
    <script src="{{asset('assets/template/js/toastr/toastr.min.js')}}"></script>
    
    <script>
        $(document).ready(function() {
  $('.profile').click(function(event) {
    event.stopPropagation();
    $('#userMenu').toggleClass('hidden');
  });
  $('.nav-overlay').click(function(event) {
    event.stopPropagation();
    $('#userMenu').addClass('hidden');
  });

});
        $(document).ready(function() {
            $('#accountButton').click(function() {
                $('.right-side-menu').toggleClass('active');
                $('#overlay').toggle();
            });

            $('#closeButton').click(function() {
                $('.right-side-menu').removeClass('active');
                $('#overlay').hide();
            });

            $('#overlay').click(function() {
                $('.right-side-menu').removeClass('active');
                $('#overlay').hide();
            });
        });
    </script>
    <script>  
        const fab = document.getElementById('fab');  
        const support = document.getElementById('support');  
        const extraFab = document.getElementById('extraFab');  
        const whatsappFab = document.getElementById('whatsappFab');  
        const fabIcon = document.getElementById('fabIcon');fab.addEventListener('click', () => {  
        fab.classList.toggle('open');  
        support.classList.toggle('hide');  
        extraFab.style.opacity = fab.classList.contains('open') ? '1' : '0';  
        whatsappFab.style.opacity = fab.classList.contains('open') ? '1' : '0';  
        fabIcon.src = fab.classList.contains('open') ? "https://img.icons8.com/ios-filled/50/FFFFFF/plus-math.png" : "https://img.icons8.com/ios-filled/50/FFFFFF/phone.png";  
    });  
</script>

@stack('js')

    {{ $settings->footer_js }}
</div>
</body>

