@extends('pages.frontend.layouts.app')

@section('content')
    <style>
        .filter-tabs li {

            color: #fff;

        }

    </style>
<style>
.stable-booking-wrapper {
    position: absolute;
    bottom: 40px; /* আগে -45 ছিল, এখন উপরে উঠে যাবে */
    width: 100%;
    z-index: 999;
}

/* Make select same height & look as input */
.booking-input[type="date"],
.booking-input select,
select.booking-input {
    height: 55px !important;
    line-height: 55px;
    padding-top: 0;
    padding-bottom: 0;
}

/* Remove default arrow spacing issue */
select.booking-input {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

.stable-booking-box {
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    padding: 25px 30px;
    border-radius: 18px;
    border: 1px solid rgba(255,255,255,0.25);
    box-shadow: 0 15px 40px rgba(0,0,0,0.25);
}

/* Input + Select same design */
.booking-input {
    width: 100%;
    height: 55px;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.4);
    background: rgba(255,255,255,0.25);
    color: #fff;
    padding: 10px 15px;
    font-weight: 500;
}
/* Force label to stay on top */
.stable-booking-box label{
    display: block;       /* inline না, block হবে */
    width: 100%;
    color: #fff;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 8px;
}
.booking-input:focus {
    outline: none;
    border-color: #DA3C3C;
    box-shadow: 0 0 10px rgba(218,60,60,0.4);
}

.booking-input option {
    color: #000;
}

/* Label */
.stable-booking-box label{
    color: #fff;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 6px;
}

/* Button */
.booking-btn {
    height: 55px;
    background: linear-gradient(45deg,#DA3C3C,#b92b2b);
    color: #fff;
    font-weight: 600;
    border-radius: 12px;
    border: none;
    transition: 0.3s ease;
}

.booking-btn:hover{
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

/* Proper alignment */
.stable-booking-box .row {
    align-items: end;
}

/* Mobile Fix */
@media (max-width: 991px) {
    .stable-booking-wrapper {
        position: relative;
        bottom: 0;
        margin-top: 30px;
    }

    .stable-booking-box{
        background: rgba(14,42,78,0.95);
        padding: 20px;
    }
}
</style>
    <!-- Banner Section -->
    <section class="banner-section">
        <div class="swiper-container banner-slider">
            <div class="swiper-wrapper">
                <!-- Slide Item -->
                <div class="swiper-slide"
                    style="
                background-image: url(storage/{{ $sliders[0]->slider_image }});
              ">
                    <div class="content-outer">
                        <div class="content-box">
                        <div class="inner">
                            <div class="row align-items-center">

                                <!-- Left Content -->
                                <div class="col-lg-8 ">
                                    <h1 class="banner-title">
                                        Word Best Resort <br />
                                        In Wild Nature
                                    </h1>
                                    <div class="text">
                                        Enjoying the beach and jungle greenry has never been so
                                        easy. In our resort, <br />
                                        you'll get our great service, friendly staff and great
                                        seaside.
                                    </div>
                                </div>

                                <!-- Right Video Box -->
                                <div class="col-lg-2 text-lg-end text-center mt-4 mt-lg-0">
                                    
                                </div>
                                <div class="col-lg-2 text-lg-end text-center mt-4 mt-lg-0">
                                    <div class="video-box">
                                        <div class="video-btn">
                                            <a href="https://www.youtube.com/watch?v=SHnVt0jq27g&t=6s"
                                                class="overlay-link play-now ripple"
                                                data-fancybox="">
                                                <i class="fas fa-play"></i>
                                            </a>
                                        </div>
                                        <span class="d-block mt-2 text-white">Adventure</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                <!-- Slide Item -->
                <div class="swiper-slide"
                    style="
                background-image: url(storage/{{ $sliders[1]->slider_image }});">
                    <div class="content-outer">
                       <div class="content-box">
                            <div class="container">
                                <div class="row align-items-center">
                                    <!-- Left Content -->
                                    <div class="col-lg-8">
                                        <div class="inner">
                                            <h1 class="banner-title">
                                                The Bridges At <br />
                                                Corbet Crossing
                                            </h1>
                                            <div class="text">
                                                Enjoying the beach and jungle greenery has never been so
                                                easy. In our resort, <br />
                                                you'll get our great service, friendly staff and great seaside.
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Video -->
                                      <div class="col-lg-2 text-lg-end text-center mt-4 mt-lg-0">
                                    
                                </div>
                                    <div class="col-lg-2 text-lg-end text-center mt-4 mt-lg-0">
                                        <div class="video-box">
                                            <div class="video-btn">
                                                <a href="https://www.youtube.com/watch?v=SHnVt0jq27g&t=6s"
                                                class="overlay-link play-now ripple" data-caption="" data-fancybox=""
                                                tabindex="0">
                                                    <i class="fas fa-play"></i>
                                                </a>
                                            </div>
                                            <span class="d-block mt-2 text-white">Adventure</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Slide Item -->
                <div class="swiper-slide"
                    style="
                background-image: url(storage/{{ $sliders[2]->slider_image }});
              ">
                    <div class="content-outer">
                        <div class="content-box">
                            <div class="container">
                                <div class="row align-items-center">
                                    <!-- Left Content -->
                                    <div class="col-lg-8">
                                        <div class="inner">
                                            <h1 class="banner-title">
                                                The Creeks Of <br />
                                                Luxury In Nature
                                            </h1>
                                            <div class="text">
                                                Enjoying the beach and jungle greenery has never been so
                                                easy. In our resort, <br />
                                                you'll get our great service, friendly staff and great seaside.
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Video -->
                                      <div class="col-lg-2 text-lg-end text-center mt-4 mt-lg-0">
                                    
                                      </div> 
                                    <div class="col-lg-2 text-lg-end text-center mt-4 mt-lg-0">
                                        <div class="video-box">
                                            <div class="video-btn">
                                                <a href="https://www.youtube.com/watch?v=SHnVt0jq27g&t=6s"
                                                class="overlay-link play-now ripple" data-caption="" data-fancybox=""
                                                tabindex="0">
                                                    <i class="fas fa-play"></i>
                                                </a>
                                            </div>
                                            <span class="d-block mt-2 text-white">Adventure</span>
                                        </div>
                                    </div>
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
        <div class="stable-booking-wrapper">
    <div class="auto-container">
        <div class="stable-booking-box">
           <form action="{{ route('zone.availability') }}" method="GET">
                <div class="row align-items-center">

                    <!-- Check In -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label>Check In</label>
                        <input type="date" name="check_in" class="form-control booking-input" required>
                    </div>

                    <!-- Check Out -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label>Check Out</label>
                        <input type="date" name="check_out" class="form-control booking-input" required>
                    </div>

                    <!-- Static Option Dropdown -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label>View Type</label>
                        <select name="view_type" class=" form-control booking-input" required>
                            <option value="">Select Option</option>
                            <option value="room">Room</option>
                            <option value="zone">Zone</option>
                        </select>
                    </div>

                    <!-- Button -->
                    <div class="col-lg-3 col-md-12 mt-3">
                        <button type="submit" class="btn booking-btn w-100 text-white">
                            Check Availability
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
    </section>
    <!-- Stable Booking Box -->

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
    <section class="about-us-section light-bg mx-60 border-shape-top">
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
                        <div class="text" style="text-align: justify;">
                            Experience exclusive privileges and personalized services every time you visit,
                            making your stay at Wonder Park & Eco Resort truly magical.
                        </div>


                        {{-- <div class="signature">
                            <img src="assets_2/images/resource/image-4.png" alt="" />
                        </div> --}}


                        <div class="link-btn">
                            <a href="{{ route('frontend.about') }}" class="theme-btn btn-style-two btn-lg"><span>discover
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
                        <img src="{{ asset('storage/images/home/Untitled-1.jpg') }}" alt="" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- amenities -->
    <section class="amenities-section light-bg mx-60 border-shape-bottom">
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
                                    <div class="text px-1">BDT {{ number_format($room->price_per_night, 2) }} / Night
                                    </div>
                                </div>
                                <h3>
                                    <a style="color: white;" href="{{ route('frontend.room_details', $room->id) }}">
                                        {{ $room->room_type->type_name }} - {{ $room->room_number }}
                                    </a>
                                </h3>
                                <div class="icon-list">
                                    <ul>
                                        <li style="color: white;"><i class="flaticon-bed"></i>{{ $room->capacity }}
                                            Guests</li>
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






    <!-- service section -->
    <section class="service-section light-bg mx-60 border-shape-top">
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
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="">
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
        <!-- Cta section -->
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
                        <div class="text">
                            {!! $offer->description !!}
                        </div>
                        {{-- <div class="link-btn">
                        <a href="services.php" class="theme-btn btn-style-one"><span>find out more</span></a>
                    </div> --}}
                    </div>
                </div>
            </div>
        </section>
    @endif;

    <!-- testimonials -->
    <section class="testimonials-section light-bg mx-60 border-shape-top">
        <div class="auto-container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="content-block" style="background-image: url(storage/images/home/client_review.jpg);">


                        <div class="title-box text-light">
                            <div class="sub-title" style="color: #0e2a4e;">Testimonials</div>
                            <h2 class="sec-title" style="color: #6d0f0f;">Clients Review</h2>
                        </div>
                        {{-- <div class="link-btn">
                            <a href="testimonials.php" class="theme-btn btn-style-one"><span>all
                                    testimonials</span></a>
                        </div> --}}
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



    <!-- news section -->
    <section class="news-section pt-0 light-bg mx-60 border-shape-bottom">
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
                                        alt="{{ $blog->title }}">
                                </a>
                            </div>
                            <div class="lower-content">
                                <div class="date">
                                    {{ $blog->created_at->format('d') }} <br />
                                    <span>{{ $blog->created_at->format('F') }}</span>
                                </div>
                                {{-- <div class="post-meta">Admin / Comments ({{ $blog->comments_count ?? 0 }})</div> --}}
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
