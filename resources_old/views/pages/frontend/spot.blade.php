@extends('pages.frontend.layouts.app')

@section('content')
    <style>
        .spot-price {
            position: absolute;
            font-size: 20px;
            letter-spacing: 1px;
            line-height: 53px;
            color: #0e2a4e;
            font-weight: 400;
            width: 202px;
            height: 55px;
            border-radius: 8px;
            background-color: #ffffff;
            left: 16px;
            /* bottom: 160px; */
            bottom: 205px;
            text-align: center;
            margin-bottom: 0;
        }

        .spot-title h3 {
            font-size: 32px;
            line-height: 40px;
            color: #0e2a4e;
            font-weight: 700;
            font-family: "Playfair Display";
            margin-bottom: 0px!important;
        }

        .spot-title h3 a {
            color: #0e2a4e !important;
            transition: .5s;
        }

        .spot-title h3 a:hover {
            color: #db3c3c !important;
        }

        .image:hover img {
            transform: scale(1.1);
        }

        .image img {
            transition: .5s;

        }

        .spot-price {
            width: 340px;
            padding-left: 20px;
        }

        @media only screen and (max-width: 767px) {

            .spot-title h3 {
                font-size: 28px;
                line-height: 40px;
                color: #0e2a4e;
                font-weight: 700;
                font-family: "Playfair Display";
                margin-bottom: 20px;
            }

        }
    </style>

    <!-- Page Title -->
    {{-- <section class="page-title" style="background-image: url(assets_2_2/images/main-slider/page-title.jpg);"> --}}
    <section class="page-title" style="background-image: url({{ asset('storage/' . $banner_image->banner_image) }});">
        <div class="auto-container">
            <div class="text-center">
                <h1>Spots</h1>
            </div>
        </div>
    </section>

    <section class="spots-section light-bg mx-60 py-5 border-shape-top border-shape-bottom"
        style="padding-bottom: 110px!important; background-color: #d3d3d3!important;">
        <div class="auto-container">

            <div class="title-box text-center pb-5">
                <div class="sub-title mb-4">Enchanting Event Spots</div>
                <div class="text">
                    Discover our exquisite event venues—each spot is thoughtfully designed to elevate your special moments.
                    Whether it's the warmth of “Godhuli,” the elegance of “Sugondha,” or the beauty of “Dokhina,” our
                    locations offer the perfect backdrop for unforgettable gatherings nestled in nature.
                </div>
            </div>


            <div class="row">
                @foreach ($spots as $spot)
                    <div class="col-lg-6 col-md-6 mb-5">
                        <div class="card h-100 shadow-sm border-0 spot-img">
                            <div class="image" style="overflow: hidden;">
                                <a href="{{ route('frontend.spot_details', $spot->id) }}">
                                    <img src="{{ asset('storage/' . $spot->image) }}" class="card-img-top"
                                        alt="{{ $spot->title }}" style="border: 8px solid #fff;">
                                </a>
                            </div>
                            {{-- <div class="spot-price">BDT {{ number_format($spot->price, 2) }} </div> --}}

                            @php
                                $originalPrice = $spot->price;
                                $discountedPrice = $originalPrice * 0.95; // 5% discount
                            @endphp

                            <div class="spot-price" style="display: flex; align-items: center; gap: 10px;">
                                <span style="text-decoration: line-through; color: #888;">
                                    BDT {{ number_format($originalPrice, 2) }}
                                </span>
                                <span style="color: #e74c3c; font-weight: bold;">
                                    BDT {{ number_format($discountedPrice, 2) }}
                                </span>
                            </div>

                            {{-- <div class="spot-price"
                                style="display: flex; flex-direction: column; align-items: flex-start; gap: 5px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="text-decoration: line-through; color: #888;">
                                        BDT {{ number_format($originalPrice, 2) }}
                                    </span>
                                    <span style="color: #e74c3c; font-weight: bold;">
                                        BDT {{ number_format($discountedPrice, 2) }}
                                    </span>
                                </div>
                                <span style="text-align: left; font-size: 0.9em; color: #e74c3c; font-weight: 500;">
                                    Limited Time Offer
                                </span>
                            </div> --}}



                            <div class="card-body spot-title">
                                <h3>
                                    <a href="{{ route('frontend.spot_details', $spot->id) }}">
                                        {{ $spot->title }}
                                    </a>
                                </h3>

                                {{-- <p class="card-text">BDT {{ $spot->price }}</p> --}}

                                <p class="card-text">Limited Time Offer</p>

                                {{-- <span>Limited Time Offer</span> --}}

                                <div class="link-btn">
                                    <a href="{{ route('frontend.spot_details', $spot->id) }}"
                                        class="theme-btn btn-style-one btn-md">
                                        <span>spot details</span>
                                    </a>
                                </div>

                            </div>
                        </div>

                        {{-- <div class="link-btn" style="margin-top: 20px;">
                            <a href="{{ route('frontend.spot_details', $spot->id) }}"
                                class="theme-btn btn-style-one btn-md">
                                <span>spot details</span>
                            </a>
                        </div> --}}

                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
