@extends('layout.master')
@section('title')
    {{ $settings->home_title }}
@endsection
@section('content')
    <div>
        <div class="p-2">
    @if ($settings->enable_notice)
        <div class="notice-container container m-auto">
            <div class="alert alert-light notice-style alert-dismissible fade show position-relative" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <div class="notice-heading">{{ $settings->notice_title }}</div>
                <div class="notice-text mb-0">{{ $settings->notice_content }}</div>
            </div>
        </div>
    @endif
    

<!--- slider --->
    @if (count($sliders) > 0)
    <section class="container m-auto">
      <section class="carousel my-4" dir="ltr" aria-label="Gallery" tabindex="0" style="margin-bottom:10px !important;">
        <div class="carousel__viewport">
          <ol class="carousel__track" style="transform: translateX(0px); transition: all 0ms ease 0s;">
            @foreach ($sliders as $slider)
            <li class="carousel__slide">
              <div class="carousel__item">
                 @isset($slider->url)
                 <a href="{{ $slider->url }}" target="_blank">
                  <img src="{{ get_image($slider->image_url) }}" class="rounded-md">
                 </a>
                @else
                  <img src="{{ get_image($slider->image_url) }}" class="rounded-md">
                @endisset
              </div>
            </li>
            @endforeach
          </ol>
        </div>
        <button type="button" class="carousel__prev" aria-label="Navigate to previous slide">
          <svg class="carousel__icon" viewBox="0 0 24 24" role="img" aria-label="Arrow pointing to the left">
            <title>Arrow pointing to the left</title>
            <path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"></path>
          </svg>
        </button>
        <button type="button" class="carousel__next" aria-label="Navigate to next slide">
          <svg class="carousel__icon" viewBox="0 0 24 24" role="img" aria-label="Arrow pointing to the right">
            <title>Arrow pointing to the right</title>
            <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"></path>
          </svg>
        </button>
        <ol class="carousel__pagination">
        @if (count($sliders) > 0)
            @for($i = 1; $i <= count($sliders); $i++)
                <li class="carousel__pagination-item">
                    <button type="button" class="carousel__pagination-button {{ $i == 1 ? 'carousel__pagination-button--active' : '' }}" aria-label="Navigate to slide {{ $i }}"></button>
                </li>
            @endfor
        @endif
        </ol>
      </section>
    </section>
    @endif
          <!--- /slider --->


 <!--- products --->
        @foreach($categorys as $category)
          <section class="my-2" id="topup">
                <div class="container mx-auto">
                  <div class="text-center">
                    <div class="flex items-center justify-center px-4 mt-0 md:mt-2 section-contact-gap pb-4">
                      <h3 class="text-2xl sm:text-3xl text-center font-primary font-bold mx-4 text-secondary-900">
                        {{ $category->title }}
                      </h3>
                    </div>
                  </div>

                  <div class="pb-1 md:pb-10">
                    <div class="md:py-5 md:px-0 grid md:grid-cols-6 sm:grid-cols-4 grid-cols-3 md:gap-8 gap-4">
                    @foreach ($products->where('categorie_id', $category->id) as $product)
                      <div class="single-game-product mb-2 md:mb-6">
                        <a href="{{ route('topup', $product->slug) }}" class="triangle">
                          <div class="cursor-pointer">
                            <div class="inset-0 opacity-25"></div>
                            <div class="inset-0 transform hover:scale-90 transition duration-300">
                              <div class="h-full w-full text-center mx-auto">
                                <img src="{{ get_image($product->image) }}" class="rounded-md">
                              </div>
                            </div>
                          </div>
                          <div>
                            <h1 class="capitalize text-xs text-center pt-3 font-primary font-extralight text-secondary-500">{{$product->title}}</h1>
                          </div>
                        </a>
                      </div>
                    @endforeach            
                    </div>
                  </div>
                </div>
          </section>
        @endforeach
           
          
           
           
           
           

    <script src="{{ asset('assets/template/js/slider.js') }}"></script>
    
</div>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function() {
        function createAndShowModal(url, image_url, content, button_text) {
            const modalHtml = `
                <div id="popup" class="modal" tabindex="-1" role="dialog" align="center">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body" align="left" style="padding: 0px;">
                                ${image_url ? `<img src="{{ url('uploads') }}/${image_url}" class="img-fluid" loading="eager" style="border-radius: 5px 5px 0px 0px;">` : ''}
                                ${content ? `<div class="popup-text" style="padding: 15px 10px; font-weight: 400; font-size: 16px; color: var(--primary-font-color);">${content}</div>` : ''}
                                ${url ? `<a class="btn theme-btn" style="margin: 0px 10px 45px 10px" href="${url}">${button_text}</a>` : ''}
                            </div>
                            <div>
                                <button type="button" class="" style="padding: 8px 20px; width: 40%; border-radius: 40px; background: var(--theme-color); color: #ffffff; position: absolute; bottom: -15px; right: 30%;" data-bs-dismiss="modal" aria-label="Close">✗ CLOSE</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            const modal = $(modalHtml);
            $('body').append(modal);
            modal.modal('show');
        }

        function fetchPopups() {
            const popupRoute = '{{ route('popup') }}';

            fetch(popupRoute)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error fetching popups from ${popupRoute}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const popups = data.popups;
                    popups.forEach(popup => {
                        createAndShowModal(popup.url, popup.image_url, popup.content, popup
                            .button_text);
                    });
                })
                .catch(error => {
                    console.error('Error fetching popups:', error.message);
                });
        }

        @if (!session('first_visit_popup') || !request()->cookie('daily_popup_showed'))
            fetchPopups();
        @endif
      
    });
</script>
@endpush
@push('style')
    <style>
        .notice-style {
            background-color: {{ $settings->notice_background_color }};
            color: {{ $settings->notice_font_color }};
        }

        .notice-style .btn-close {
            font-size: 12px;
        }

        .notice-style .notice-heading {
            font-size: 18px;
            font-weight: 500;
            padding-bottom: 4px;
        }

        .notice-text {
            font-size: 12px;
            font-weight: 400;
            font-family: "Times New Roman", Times, serif;

        }
    </style>
@endpush