@extends('pages.frontend.layouts.app')

@section('content')
    <style>
        @media only screen and (max-width: 991px) {
            section.welcome-section-six .author-info {
                margin-bottom: 60px;
            }
        }

        @media only screen and (max-width: 767px) {

            .title-box>.sec-title.small {
                font-size: 28px !important;
                line-height: 30px !important;
            }

            section.welcome-section-six .text {
                font-size: 25px;
                line-height: 60px;
                color: #0c141f;
                font-weight: 400;
                font-style: italic;
            }

            .sec-title {
                font-size: 28px;
                line-height: 50px;
            }

            section.about-section-two .title-text {
                font-size: 28px;
            }

        }

        .text-two {
            color: #040404 !important;
        }

        .text-three {
            color: #040404 !important;
        }
    </style>

    <!-- Page Title -->
    {{-- <section class="page-title" style="background-image: url(assets_2/images/main-slider/page-title.jpg);"> --}}
    <section class="page-title" style="background-image: url({{ asset('storage/' . $banner_image->banner_image) }});">

        <div class="auto-container">
            <div class="text-center">
                <h1>About</h1>
            </div>
        </div>
    </section>

    <!-- Welcome Section six -->
    <section class="welcome-section-six light-bg mx-60 border-shape-top" style="background-color: #d3d3d3!important;">
        <div class="auto-container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="title-box">

                        <div class="sub-title">welcome to wonder park & Eco resort</div>
                        {{-- <h2 class="sec-title small mb-30">Our Resort has been <br>present for over 20 years.</h2> --}}
                        <h2 class="sec-title small mb-30">{!! $about_us[0]->title !!}</h2>

                        {{-- <div class="text">We make the best or all our customers.</div>
                        <div class="text-two">Our objective at Bluebell is to bring together our visitor's societies and
                            spirits with our own, communicating enthusiasm and liberality in the food we share. Official
                            Chef and Owner Philippe Massoud superbly creates a blend of Lebanese, Levantine, Mediterranean
                            motivated food blended in with New York mentality. Delightful herbs and flavors consolidate
                            surfaces to pacify wide based palates. </div>
                        <div class="text-three">Official Chef and Owner Philippe Massoud superbly creates a blend of
                            Lebanese, Levantine, Mediterranean motivated food blended in with New York mentality. </div>
                        <div class="author-info">
                            <div class="author-wrap">
                                <div class="name">Kahey Kemey</div>
                                <div class="designation">Resort Manager </div>
                            </div>
                        </div> --}}


                        {!! $about_us[0]->description !!}

                    </div>


                    <div class="author-info">
                        <div class="author-wrap">
                            <div class="author-thumb">
                                <img src="{{ asset('storage/' . $teams[0]->image) }}" alt="{{ $teams[0]->name }}"
                                    style="width:80px; height:80px; border-radius:50%; object-fit:cover;">
                            </div>
                            <div class="name" style="margin-left: 20px;">{{ $teams[0]->name }}</div>
                            <div class="designation" style="margin-left: 20px;">{{ $teams[0]->designation }}</div>
                        </div>
                    </div>



                </div>
                <div class="col-lg-6">
                    <div class="text-lg-end">
                        {{-- <div class="image"><img src="assets_2/images/resource/image-58.jpg" alt=""></div> --}}
                        <div class="image"><img src="{{ asset('storage/' . $about_us[0]->image) }}"
                                style="border: 8px solid #fff;" alt="About Us Image">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Why Choose Us -->
    <section class="why-choose-us-section light-bg mx-60 pt-0 border-shape-bottom"
        style="background-color: #d3d3d3!important;">
        <div class="auto-container">
            <div class="title-box text-center">
                <div class="sub-title">Our Features</div>
                <h2 class="sec-title mb-30">{!! $about_us[1]->title !!}</h2>
                <div class="text">{!! $about_us[1]->description !!}</div>
            </div>
            <div class="row">
                <div class="col-lg-6 why-choose-us-block">
                    <div class="image"><img src="{{ asset('storage/' . $about_us[1]->image) }}"
                            style="border: 8px solid #fff;" alt=""></div>
                    <div class="inner-box">
                        {{-- <div class="image-block"><img src="assets_2/images/resource/image-22.jpg" alt=""></div> --}}
                        <div class="text-three"><span>100000 +</span> Visiters <br> Come Annually</div>
                    </div>
                </div>



                <div class="col-lg-6 why-choose-us-block">
                    <div class="text-two">
                        At Wonder Park, we go beyond just fun — we create memories that last a lifetime. Whether you're here
                        for relaxation or adventure, we have something special for everyone!
                    </div>
                    <div class="icon-list">
                        <ul>
                            <li><i class="flaticon-checkmark"></i>Amusement Rides</li>
                            <li><i class="flaticon-checkmark"></i>Kids Play Zone</li>
                            <li><i class="flaticon-checkmark"></i>Boating</li>
                            <li><i class="flaticon-checkmark"></i>Resort Stay</li>
                            <li><i class="flaticon-checkmark"></i>Water Slides & Pool</li>
                            <li><i class="flaticon-checkmark"></i>Picnic Spot</li>
                            <li><i class="flaticon-checkmark"></i>Nature Trails</li>
                            <li><i class="flaticon-checkmark"></i>Live Cultural Events</li>
                            <li><i class="flaticon-checkmark"></i>BBQ & Bonfire Nights</li>
                        </ul>
                    </div>
                </div>


            </div>
        </div>
    </section>



    <!-- about section two -->
    <section class="about-section-two dark_bg">
        <div class="auto-container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="title-text">
                        <div class="sub-title">about us</div>
                        <h2 class="sec-title text-light">{!! $about_us[2]->title !!}</h2>
                    </div>
                    <div class="text">{!! $about_us[2]->description !!}</div>
                    <div class="image"><img src="{{ asset('storage/' . $about_us[2]->image) }}" alt=""></div>
                </div>
                <div class="col-lg-4">
                    <div class="image-two"><img src="{{ asset('storage/' . $about_us[3]->image) }}" alt=""></div>
                    <div class="text-two" style="color: #f2f2f2 !important;">{!! $about_us[3]->description !!}</div>
                    {{-- <div class="link-btn"><a href="about.php" class="view-all-btn"><span>Read More</span></a></div> --}}
                </div>
            </div>
        </div>
    </section>



    <!-- Our Team -->
    <section class="team-section light-bg mx-60 border-shape-top" style="background-color: #d3d3d3!important;">
        <div class="auto-container">
            <div class="top-content">
                <div class="title-box">
                    <div class="sub-title">Dedicated team</div>
                    <h2 class="sec-title mb-30">Our Resort Staff</h2>
                    <div class="text">
                        "At Wonder Park, our team is the heart of every magical experience. From creating thrilling <br />
                        adventures to ensuring comfort and safety, we work together to make every visit unforgettable.
                        <br />
                        Our passionate and dedicated members are committed to bringing joy, excitement, and exceptional
                        <br />service
                        to all our guests."
                    </div>
                </div>
            </div>
            <div class="row">

                @foreach ($teams as $team)
                    <div class="col-lg-3 col-md-6 team-block-one">
                        <div class="inner-box wow fadeInDown" data-wow-duration="1500ms">
                            <!-- Team Image -->
                            <div class="image">
                                <img src="{{ $team->image ? asset(Storage::url($team->image)) : asset('assets_2/images/resource/default.jpg') }}"
                                    alt="{{ $team->name }}"
                                    style="width:100%; height:300px; object-fit:cover; border: 8px solid #fff;">

                                <!-- Social Overlay -->
                                <div class="overlay-box">
                                    <ul class="social-links">
                                        @if ($team->fb_url)
                                            <li><a href="{{ $team->fb_url }}" target="_blank"><span
                                                        class="fab fa-facebook-f"></span></a></li>
                                        @endif
                                        @if ($team->twitter_url)
                                            <li><a href="{{ $team->twitter_url }}" target="_blank"><span
                                                        class="fab fa-twitter"></span></a></li>
                                        @endif
                                        @if ($team->linkdin_url)
                                            <li><a href="{{ $team->linkdin_url }}" target="_blank"><span
                                                        class="fab fa-linkedin-in"></span></a></li>
                                        @endif
                                        @if ($team->instagram_url)
                                            <li><a href="{{ $team->instagram_url }}" target="_blank"><span
                                                        class="fab fa-instagram"></span></a></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <!-- Team Content -->
                            <div class="content" style="padding: 20px 15px;">
                                <h5>{{ $team->name }}</h5>
                                <div class="designation">{{ $team->designation }}</div>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>




    <div class="light-bg mx-60">
        <div class="auto-container">
            <div class="boder-bottom-two"></div>
        </div>
    </div>


    <!-- funfact section -->
    <section class="funfact-section light-bg  mx-60 border-shape-bottom" style="background-color: #d3d3d3!important;">
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
@endsection
