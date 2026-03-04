@extends('pages.frontend.layouts.app')

@section('content')

    <style>
        /* .image-wrapper {
                                        position: relative;
                                        overflow: hidden;
                                    }

                                    .image-wrapper img {
                                        transition: transform 0.3s ease;
                                    }

                                    .image-wrapper:hover img {
                                        transform: scale(1.05);
                                    }

                                    .image-overlay {
                                        position: absolute;
                                        top: 0;
                                        left: 0;
                                        height: 100%;
                                        width: 100%;
                                        background: rgba(0, 0, 0, 0.4);
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        opacity: 0;
                                        transition: opacity 0.3s ease;
                                    }

                                    .image-wrapper:hover .image-overlay {
                                        opacity: 1;
                                    }

                                    .image-overlay i {
                                        color: #fff;
                                        font-size: 2rem;
                                    } */

        .news-block-two .inner-box:hover .image img {
            transform: none;
        }

        .news-block-two .image img {
            transition: none;
        }

        @media only screen and (max-width: 767px) {

            .news-block-two h3 {
                font-size: 28px;
                color: #0e2a4e;
                font-weight: 800;
                font-family: "Playfair Display";
                padding-top: 30px;
                padding-bottom: 25px;
            }

        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">



    <!-- Page Title -->
    {{-- <section class="page-title" style="background-image: url(assets_2_2/images/main-slider/page-title.jpg);"> --}}
    <section class="page-title" style="background-image: url({{ asset('storage/' . $banner_image->banner_image) }});">
        <div class="auto-container">
            <div class="text-center">
                <h1>Spot Details</h1>
            </div>
        </div>
    </section>


    <!-- Sidebar Page Container -->
    <div class="sidebar-page-container light-bg mx-60 border-shape-top border-shape-bottom"
        style="background-color: #d3d3d3!important;">
        <div class="auto-container">
            <div class="row">
                <!-- Blog Content -->
                <div class="col-lg-12 pr-lg-5">
                    <div class="news-block-two style-two blog-single-post">

                        <div class="inner-box">

                            {{-- Related Spot Detail Images --}}
                            @if ($spot->spot_detail->count())
                                <div class="pt-5">
                                    {{-- <h4 class="pb-3">More from this Spot</h4> --}}

                                    <div class="single-items-carousel">
                                        <!-- Main Carousel -->
                                        <div class="swiper-container single-item-with-pager-carousel">
                                            <div class="swiper-wrapper">
                                                @foreach ($spot->spot_detail as $detail)
                                                    <div class="swiper-slide">
                                                        <div class="image">
                                                            <a href="{{ asset(Storage::url($detail->image_path)) }}"
                                                                data-fancybox="gallery">
                                                                <img src="{{ $detail->image_path ? asset(Storage::url($detail->image_path)) : asset('assets/images/resource/default.jpg') }}"
                                                                    alt="Detail Image">
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="h_10 w_10"></div>

                                        <!-- Thumbnail Carousel -->
                                        <div class="swiper-container single-item-with-pager-thumb">
                                            <div class="swiper-wrapper">
                                                @foreach ($spot->spot_detail as $detail)
                                                    <div class="swiper-slide">
                                                        <div class="thumb">
                                                            <img src="{{ $detail->image_path ? asset(Storage::url($detail->image_path)) : asset('assets/images/resource/default.jpg') }}"
                                                                alt="Thumbnail">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-5">
                                <div class="pricing  px-1">BDT {{ $spot->price }} </div>
                                <h3 style="padding-top: 5px;">{{ $spot->title }}</h3>
                            </div>

                            {!! $spot->description !!}


                        </div>


                    </div>
                </div>


            </div>
        </div>
    </div>



@endsection
