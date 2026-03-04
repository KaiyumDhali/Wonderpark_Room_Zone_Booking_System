@extends('pages.frontend.layouts.app')

@section('content')
    <style>
        .filter-tabs li {

            color: #fff;

        }

        .amenities-block .icon {
            position: absolute;
            left: 0;
            top: 15px;
            font-size: 50px;
            color: #0e2a4e;
        }

        .amenities-block .inner-box {
            position: relative;
            padding-left: 70px;
            margin-bottom: 20px;
        }

        .amenities-block h1 {
            font-size: 22px;
            line-height: 21px;
            color: #0e2a4e;
            font-family: 'GlacialIndifference-Regular';
            margin-bottom: 0;
        }

        .text {
            color: #040404 !important;
        }

        @media only screen and (max-width: 767px) {
            .sec-title {
                font-size: 28px;
                line-height: 35px;
            }

            .sec-title {
                margin-bottom: 15px;
            }

            .amenities-block .icon {
                position: absolute;
                left: 0;
                top: 15px;
                font-size: 40px;
                color: #0e2a4e;
            }

            .inner-box>h3>a {
                font-size: 25px;
            }

        }

        /* offer */

        /* .bg-image {
                                position: relative;
                                width: 100%;
                                height: 100vh;
                                background: url('storage/{{ $offer->image }}') center/cover no-repeat;

                                mask-image: radial-gradient(circle at center, rgba(0, 0, 0, 1) 70%, rgba(0, 0, 0, 0) 100%);
                                -webkit-mask-image: radial-gradient(circle at center, rgba(0, 0, 0, 1) 70%, rgba(0, 0, 0, 0) 100%);
                            }


                            .bg-image::after {
                                content: "";
                                position: absolute;
                                inset: 0;
                                background: radial-gradient(circle at center, rgba(0, 0, 0, 0) 60%, rgba(0, 0, 0, 0.85) 100%);
                            } */


        /* offer */
    </style>

    <!-- Banner Section -->
    <section class="banner-section">
        <div class="swiper-container banner-slider">
            <div class="swiper-wrapper">
                <!-- Slide Item -->
                <div class="swiper-slide" style="background-image: url(storage/{{ $sliders[0]->slider_image }});">
                    <div class="content-outer">
                        <div class="content-box">
                            <div class="inner">
                                <h1 class="banner-title">
                                    {{-- Word Best Resort <br />
                                    In Wild Nature --}}

                                    Welcome to Wonder Park <br />
                                    Where Fun Meets Nature
                                </h1>
                                <div class="text" style="color: #f2f2f2!important;">
                                    {{-- Enjoying the beach and jungle greenry has never been so
                                    easy. In our resort, <br />
                                    you'll get our great service, friendly staff and great
                                    seaside. --}}

                                    Experience thrilling rides, lush greenery, and relaxing moments — all in one magical
                                    destination. <br />
                                    Enjoy the ultimate family adventure with world-class attractions and natural beauty.
                                </div>
                                <div class="video-box">
                                    <div class="video-btn">
                                        <a href="https://www.youtube.com/watch?v=SHnVt0jq27g&t=6s"
                                            class="overlay-link play-now ripple" data-caption="" data-fancybox=""
                                            tabindex="0"><i class="fas fa-play"></i></a>
                                    </div>
                                    <span>Advanture</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Slide Item -->
                <div class="swiper-slide" style="background-image: url(storage/{{ $sliders[1]->slider_image }});">
                    <div class="content-outer">
                        <div class="content-box">
                            <div class="inner">
                                <h1 class="banner-title">
                                    {{-- The Bridges At <br />
                                    Corbet Corssing --}}

                                    Discover Wonder Park <br />
                                    Your Gateway to Joy & Nature
                                </h1>
                                <div class="text" style="color: #f2f2f2!important;">
                                    {{-- Enjoying the beach and jungle greenry has never been so
                                    easy. In our resort, <br />
                                    you'll get our great service, friendly staff and great
                                    seaside. --}}

                                    Escape the city and immerse yourself in a world of fun, freshness, and natural beauty.
                                    <br />
                                    A perfect destination for families, friends, and adventure seekers.
                                </div>
                                <div class="video-box">
                                    <div class="video-btn">
                                        <a href="https://www.youtube.com/watch?v=SHnVt0jq27g&t=6s"
                                            class="overlay-link play-now ripple" data-caption="" data-fancybox=""
                                            tabindex="0"><i class="fas fa-play"></i></a>
                                    </div>
                                    <span>Advanture</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Slide Item -->
                <div class="swiper-slide" style="background-image: url(storage/{{ $sliders[2]->slider_image }});">
                    <div class="content-outer">
                        <div class="content-box">
                            <div class="inner">
                                <h1 class="banner-title">
                                    {{-- The Creeks Of <br />
                                    Luxuary In Nature --}}

                                    Wonder Park <br />
                                    Adventure Awaits You
                                </h1>
                                <div class="text" style="color: #f2f2f2!important;">
                                    {{-- Enjoying the beach and jungle greenry has never been so
                                    easy. In our resort, <br />
                                    you'll get our great service, friendly staff and great
                                    seaside. --}}

                                    Ride, relax, and reconnect with nature — all in one exciting place. <br />
                                    Fun, food, and unforgettable moments await!
                                </div>
                                <div class="video-box">
                                    <div class="video-btn">
                                        <a href="https://www.youtube.com/watch?v=SHnVt0jq27g&t=6s"
                                            class="overlay-link play-now ripple" data-caption="" data-fancybox=""
                                            tabindex="0"><i class="fas fa-play"></i></a>
                                    </div>
                                    <span>Advanture</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="banner-slider-nav style-three">
            <div class="banner-slider-control banner-slider-button-prev">
                <span><i class="fal fa-angle-right"></i></span>
            </div>
            <div class="banner-slider-control banner-slider-button-next">
                <span><i class="fal fa-angle-right"></i></span>
            </div>
        </div>
    </section>
    <!-- End Bnner Section -->

    <!-- Check availability -->
    {{-- <div class="check-availability">
        <div class="auto-container">
            <form class="form border-shape-top">
                <div class="left-side">
                    <ul>
                        <li>
                            <p>check in</p>
                            <input type="text" placeholder="Sep 16, 2021" class="datepicker" />
                        </li>
                        <li>
                            <p>check out</p>
                            <input type="text" placeholder="Sep 16, 2021" class="datepicker" />
                        </li>
                        <li>
                            <p>Room</p>
                            <select>
                                <option data-display="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </li>
                        <li>
                            <p>Adults</p>
                            <select>
                                <option data-display="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </li>
                        <li>
                            <p>Children</p>
                            <select>
                                <option data-display="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </li>
                    </ul>
                </div>
                <div class="right-side">
                    <button type="submit">
                        check <br />
                        availability
                    </button>
                </div>
            </form>
        </div>
    </div> --}}

    <!-- About Us -->
    <section class="about-us-section light-bg mx-60 border-shape-top" style="background-color: #d3d3d3!important;">
        <div class="auto-container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="content-block">
                        <div class="title-box">
                            <div class="sub-title">about us</div>
                            <h2 class="sec-title">
                                We Invite guests to <br />
                                celebrate life
                            </h2>
                        </div>
                        <div class="text" style="text-align: justify;">
                            Welcome to Wonder Park & Eco Resort — a perfect blend of excitement, nature, and relaxation.
                            From thrilling rides and adventurous activities to the serene beauty of our eco-friendly resort,
                            every moment here is crafted to create unforgettable memories for you and your loved ones.
                            Whether you're seeking fun, leisure, or a peaceful escape, we have something special for
                            everyone.
                        </div>
                        <div class="text" style="text-align: justify; margin-bottom: 60px;">
                            Experience exclusive privileges and personalized services every time you visit,
                            making your stay at Wonder Park & Eco Resort truly magical.
                        </div>


                        {{-- <div class="signature">
                            <img src="assets_2/images/resource/image-4.png" alt="" />
                        </div> --}}


                        <div class="link-btn">
                            <a href="{{ route('frontend.about') }}" class="theme-btn btn-style-two btn-lg"
                                style="margin-top: 30px!important;"><span>discover
                                    more <i class="flaticon-right-arrow"></i></span></a>
                        </div>
                        <div class="award" data-parallax='{"x": 20}'>
                            <div class="icon">
                                <img src="assets_2/images/icons/icon-1.png" alt="" />
                            </div>
                            <h4>
                                awarded <br />
                                Resort
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="image img_hover_3">
                        {{-- <img src="assets_2/images/resource/image-1.jpg" alt="" /> --}}
                        <img src="{{ asset('storage/images/home/Untitled-1.jpg') }}" alt=""
                            style="border: 8px solid #fff;" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- amenities -->
    <section class="amenities-section light-bg mx-60 border-shape-bottom" style="background-color: #d3d3d3!important;">
        <div class="auto-container">
            <div class="title-box text-center">
                <div class="sub-title">Amenities</div>
                <h2 class="sec-title">Everything You Need for Fun & Relaxation</h2>
            </div>

            <div class="row">

                <div class="col-lg-4 amenities-block">
                    <div class="inner-box">
                        <div class="icon"><i class="fa fa-check-circle text-success me-2"></i></div>
                        <h1>Amusement Rides</h1>
                        <div class="text">
                            Enjoy thrilling rides for all ages and make every moment unforgettable.
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 amenities-block">
                    <div class="inner-box">
                        <div class="icon"><i class="fa fa-check-circle text-success me-2"></i></div>
                        <h1>Kids Play Zone</h1>
                        <div class="text">
                            A safe and fun area designed for kids to play, explore, and enjoy.
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 amenities-block">
                    <div class="inner-box">
                        <div class="icon"><i class="fa fa-check-circle text-success me-2"></i></div>
                        <h1>Boating</h1>
                        <div class="text">
                            Experience peaceful boating on our scenic water spots surrounded by nature.
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 amenities-block">
                    <div class="inner-box">
                        <div class="icon"><i class="fa fa-check-circle text-success me-2"></i></div>
                        <h1>Resort Stay</h1>
                        <div class="text">
                            Relax in our luxurious and eco-friendly resort accommodations.
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 amenities-block">
                    <div class="inner-box">
                        <div class="icon"><i class="fa fa-check-circle text-success me-2"></i></div>
                        <h1>Water Slides & Pool</h1>
                        <div class="text">
                            Splash into fun with exciting water slides and our refreshing pool.
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 amenities-block">
                    <div class="inner-box">
                        <div class="icon"><i class="fa fa-check-circle text-success me-2"></i></div>
                        <h1>Picnic Spot</h1>
                        <div class="text">
                            Perfect spots for family picnics and outdoor gatherings amidst nature.
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 amenities-block">
                    <div class="inner-box">
                        <div class="icon"><i class="fa fa-check-circle text-success me-2"></i></div>
                        <h1>Nature Trails</h1>
                        <div class="text">
                            Explore scenic walking paths and connect with the beauty of nature.
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 amenities-block">
                    <div class="inner-box">
                        <div class="icon"><i class="fa fa-check-circle text-success me-2"></i></div>
                        <h1>Live Cultural Events</h1>
                        <div class="text">
                            Enjoy traditional performances and cultural shows during your visit.
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 amenities-block">
                    <div class="inner-box">
                        <div class="icon"><i class="fa fa-check-circle text-success me-2"></i></div>
                        <h1>BBQ & Bonfire Nights</h1>
                        <div class="text">
                            Spend cozy evenings with delicious BBQ and warm bonfire experiences.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- room section -->
    <section class="room-section">
        <div class="auto-container">
            <div class="top-content">
                <div class="title-box text-light">
                    <div class="sub-title">Accomodation</div>
                    <h2 class="sec-title">Room / Suits</h2>
                </div>

            </div>

            <!-- Filter -->
            <div class="filters style-two">
                <ul class="filter-tabs filter-btns" style="color: white;">
                    <li class="filter active" data-role="button" data-filter=".all">All</li>
                    @foreach ($rooms->pluck('room_type')->unique('id') as $room_type)
                        @if ($room_type)
                            <li class="filter" data-role="button" data-filter=".cat-{{ $room_type->id }}">
                                {{ $room_type->type_name }}
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>

            <!-- Sortable Gallery -->
            <div class="sortable-masonry">
                <div class="items-container row">
                    @forelse($rooms as $room)
                        <div class="col-lg-6 room-block-two masonry-item all cat-{{ $room->roomtype_id }}">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ asset('storage/' . $room->thumbnail_image) }}"
                                        alt="{{ $room->room_type->type_name }}">
                                    {{-- ৳ --}}
                                    <div class="text px-1">BDT {{ number_format($room->price_per_night, 2) }}
                                    </div>
                                </div>
                                <h3>
                                    <a style="color: white;" href="{{ route('frontend.room_details', $room->id) }}">
                                        {{ $room->room_type->type_name }} - {{ $room->room_number }}
                                    </a>
                                </h3>
                                <div class="icon-list">
                                    <ul>
                                        <li style="color: white;"><i class="flaticon-bed"></i>{{ $room->capacity }}Guests
                                        </li>
                                        <li style="color: white;"><i class="flaticon-bath-1"></i>1 Bathroom</li>
                                        {{-- <li><i class="flaticon-bath-1"></i>{{ $room->floor }} Floor</li> --}}
                                    </ul>
                                </div>
                                <div class="text-two" style="color: white;">
                                    {{ Str::limit($room->description, 120, '...') }}
                                </div>
                                <div class="link-btn">
                                    <a href="{{ route('frontend.room_details', $room->id) }}"
                                        class="theme-btn btn-style-one btn-md">
                                        <span>room details</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center">No rooms available at the moment.</p>
                    @endforelse
                </div>

            </div>


        </div>
    </section>






    <!-- gallery section -->
    <section class="service-section light-bg mx-60 border-shape-top" style="background-color: #d3d3d3!important;">
        <div class="auto-container">

            <div class="top-content">
                <div class="title-box text-light-dark">
                    <div class="sub-title">Captured Moments</div>
                    <h2 class="sec-title">Wonder Park Gallery</h2>
                </div>
            </div>

            <!--Sortable Galery-->
            <div class="sortable-masonry">
                <div class="items-container row">
                    @foreach ($gallery as $item)
                        <div class="gallery-block-four gallery-overlay masonry-item all cat-1 col-lg-4 col-md-6">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="Gallery Image"
                                        style="border: 8px solid #fff;">
                                </div>
                                <div class="overlay-box">
                                    <div class="overlay-inner">
                                        <div class="content">
                                            <a href="{{ asset('storage/' . $item->image) }}" class="lightbox-image link"
                                                data-fancybox="gallery">
                                                <span class="icon fas fa-eye"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>
            </div>
        </div>
    </section>


    @if ($offer)
        <!-- Offer Section -->
        <section class="cta-section">
            {{-- <div class="bg-image" data-parallax='{"x": 40}'
            style="background-image: url(assets_2/images/resource/image-1.png)"></div> --}}
            <div class="bg-image" data-parallax='{"x": 40}' style="background-image: url(storage/{{ $offer->image }});">
            </div>


            <div class="offer-image">
                <img src="assets_2/images/resource/image-8.png" alt="" />
            </div>
            <div class="auto-container">
                <div class="row">
                    <div class="col-xl-5 offset-xl-7 cta-block">
                        <div class="title-box text-light">
                            <div class="sub-title">Our offer</div>
                            <h2 class="sec-title">{{ $offer->title }}</h2>
                        </div>
                        <div class="text" style="color: #f2f2f2 !important;">
                            {!! $offer->description !!}
                        </div>
                        {{-- <div class="link-btn">
                        <a href="services.php" class="theme-btn btn-style-one"><span>find out more</span></a>
                    </div> --}}
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- testimonials -->
    <section class="testimonials-section light-bg mx-60 border-shape-top" style="background-color: #d3d3d3!important;">
        <div class="auto-container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="content-block"
                        style="background-image: url(storage/images/home/client_review.jpg); border: 8px solid #fff; box-shadow: 0 0 0 8px #ccc;">

                        <div class="title-box text-light">
                            <div class="sub-title" style="color: #0e2a4e;">Testimonials</div>
                            <h2 class="sec-title" style="color:#f2f2f2; text-shadow:2px 2px 8px rgba(0,0,0,0.7);">
                                Clients Review
                            </h2>

                        </div>
                        <div class="link-btn">
                            <a href="#" class="theme-btn btn-style-one">
                                <span>all testimonials</span>
                            </a>
                        </div>

                        <div class="award" data-parallax='{"y": 20}'>
                            <img src="assets_2/images/icons/icon-1.png" alt="" />
                            <h4>
                                awarded <br />
                                restaurant
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="theme_carousel owl-theme owl-carousel owl-dot-style-one"
                        data-options='{"loop": true, "center": false, "margin": 30, "autoheight":true, "lazyload":true, "nav": true, "dots": true, "autoplay": true, "autoplayTimeout": 6000, "smartSpeed": 1000, "responsive":{ "0" :{ "items": "1" }, "480" :{ "items" : "1" }, "600" :{ "items" : "1" }, "768" :{ "items" : "1" } , "992":{ "items" : "1" }, "1200":{ "items" : "1" }}}'>

                        <!-- Review 1 -->
                        <div class="testimonial-block">
                            <h3><span class="quote">“</span>Unforgettable Experience</h3>
                            <div class="text">
                                Wonder Park & Eco Resort is a perfect blend of adventure and relaxation.
                                The rides were thrilling, and the eco-friendly environment made us feel
                                so close to nature. A must-visit destination for families!
                            </div>
                            <div class="author-info">
                                <div class="name">Ayesha Rahman</div>
                            </div>
                        </div>

                        <!-- Review 2 -->
                        <div class="testimonial-block">
                            <h3><span class="quote">“</span>Best Weekend Getaway</h3>
                            <div class="text">
                                The water slides and boating experience were amazing! We enjoyed every moment,
                                from the BBQ nights to the live cultural events. Truly a gem in Bangladesh.
                            </div>
                            <div class="author-info">
                                <div class="name">Md. Karim</div>
                            </div>
                        </div>

                        <!-- Review 3 -->
                        <div class="testimonial-block">
                            <h3><span class="quote">“</span>Wonderful Hospitality</h3>
                            <div class="text">
                                The staff were so friendly and helpful, making our stay at the resort unforgettable.
                                The picnic spots and nature trails are perfect for a relaxing family day.
                            </div>
                            <div class="author-info">
                                <div class="name">Nusrat Jahan</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>




    <div class="light-bg mx-60">
        <div class="auto-container">
            <div class="boder-bottom-two"></div>
        </div>
    </div>


    <!-- funfact section -->
    <section class="funfact-section light-bg mx-60" style="background-color: #d3d3d3!important;">
        <div class="auto-container">
            <div class="row">
                <div class="col-lg-3 funfact-block">
                    <div class="count-outer count-box">
                        <span class="count-text" data-speed="3000" data-stop="200">0</span><span class="plus">+</span>
                    </div>
                    <div class="text">Booking monthly</div>
                </div>
                <div class="col-lg-3 funfact-block">
                    <div class="count-outer count-box">
                        <span class="count-text" data-speed="3000" data-stop="80">0</span><span class="plus">+</span>
                    </div>
                    <div class="text">Visitors daily</div>
                </div>
                <div class="col-lg-3 funfact-block">
                    <div class="count-outer count-box">
                        <span class="count-text" data-speed="3000" data-stop="98">0</span><span class="plus">%</span>
                    </div>
                    <div class="text">Positive feedback</div>
                </div>
                <div class="col-lg-3 funfact-block">
                    <div class="count-outer count-box">
                        <span class="count-text" data-speed="3000" data-stop="10">0</span><span class="plus">+</span>
                    </div>
                    <div class="text">Awards & honors</div>
                </div>
            </div>
        </div>
    </section>




    <!-- news section -->
    <section class="news-section pt-0 light-bg mx-60 border-shape-bottom" style="background-color: #d3d3d3!important;">
        <div class="auto-container">
            <div class="title-box text-center">
                <div class="sub-title">Wonder Park & Eco Resort</div>
                <h2 class="sec-title">News & Stories</h2>
            </div>


            <div class="row">
                @forelse($blogs as $blog)
                    <div class="col-lg-4 news-block">
                        <div class="inner-box">
                            <div class="image">
                                <a href="{{ route('frontend.blog_details', $blog->id) }}">
                                    <img src="{{ $blog->image ? asset(Storage::url($blog->image)) : asset('assets_2/images/resource/news-1.jpg') }}"
                                        alt="{{ $blog->title }}" style="border: 8px solid #fff;">
                                </a>
                            </div>
                            <div class="lower-content">
                                <div class="date">
                                    {{ $blog->created_at->format('d') }} <br />
                                    <span>{{ $blog->created_at->format('F') }}</span>
                                </div>
                                <div class="post-meta" style="visibility: hidden;">Admin / Comments 2</div>
                                <h3>
                                    <a href="{{ route('frontend.blog_details', $blog->id) }}">
                                        {{ \Illuminate\Support\Str::limit($blog->title, 50) }}
                                    </a>
                                </h3>
                                <div class="link-btn">
                                    <a href="{{ route('frontend.blog_details', $blog->id) }}"
                                        class="theme-btn btn-style-three">
                                        <span>Read More</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">No blogs available.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
