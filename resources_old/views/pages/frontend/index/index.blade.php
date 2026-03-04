@extends('pages.frontend.layout.App')
@yield('ecommerce')
@section('content')
    <main>
        <!--================Slider Area =================-->
        <section class="slider-section">
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators slider-indicators">
                    @foreach ($sliders as $key => $slider)
                        <li data-target="#carousel-example-generic" data-slide-to="{{ $key }}"
                            class="{{ $key == 0 ? 'active' : '' }}"></li>
                    @endforeach
                </ol>
                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    @foreach ($sliders as $key => $slider)
                        <div class="item {{ $key == 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $slider->slider_image) }}" style="width:100%; height:568px"
                                alt="Slider Image">
                            <div class="carousel-caption">
                                <div class="slider_text_box">
                                    <div class="tp-caption tp-resizeme first_text" id="slide-1586-layer-1"
                                        data-x="['left','left','left','15','0']" data-hoffset="['0','0','0','0']"
                                        data-y="['top','top','top','top']" data-voffset="['290','290','290','220','130']"
                                        data-fontsize="['55','55','55','40','25']"
                                        data-lineheight="['59','59','59','50','35']"
                                        data-width="['550','550','550','550','300']" data-height="none"
                                        data-whitespace="normal" data-type="text" data-responsive_offset="on"
                                        data-frames="[{&quot;delay&quot;:10,&quot;speed&quot;:1500,&quot;frame&quot;:&quot;0&quot;,&quot;from&quot;:&quot;y:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;&quot;,&quot;mask&quot;:&quot;x:0px;y:0px;s:inherit;e:inherit;&quot;,&quot;to&quot;:&quot;o:1;&quot;,&quot;ease&quot;:&quot;Power2.easeInOut&quot;},{&quot;delay&quot;:&quot;wait&quot;,&quot;speed&quot;:1500,&quot;frame&quot;:&quot;999&quot;,&quot;to&quot;:&quot;y:[175%];&quot;,&quot;mask&quot;:&quot;x:inherit;y:inherit;s:inherit;e:inherit;&quot;,&quot;ease&quot;:&quot;Power2.easeInOut&quot;}]"
                                        data-textAlign="['left','left','left','left']">Spend Quality Holidays With Us </div>
                                    <div class="tp-caption tp-resizeme secand_text" id="slide-1587-layer-2"
                                        data-x="['left','left','left','15','0']" data-hoffset="['0','0','0','0']"
                                        data-y="['top','top','top','top']" data-voffset="['435','435','435','340','225']"
                                        data-fontsize="['18','18','18','18','16']" data-lineheight="['26','26','26','26']"
                                        data-width="['550','550','550','550','300']" data-height="none"
                                        data-whitespace="normal" data-type="text" data-responsive_offset="on"
                                        data-transform_idle="o:1;"
                                        data-frames="[{&quot;delay&quot;:10,&quot;speed&quot;:1500,&quot;frame&quot;:&quot;0&quot;,&quot;from&quot;:&quot;y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;&quot;,&quot;mask&quot;:&quot;x:0px;y:[100%];s:inherit;e:inherit;&quot;,&quot;to&quot;:&quot;o:1;&quot;,&quot;ease&quot;:&quot;Power2.easeInOut&quot;},{&quot;delay&quot;:&quot;wait&quot;,&quot;speed&quot;:1500,&quot;frame&quot;:&quot;999&quot;,&quot;to&quot;:&quot;y:[175%];&quot;,&quot;mask&quot;:&quot;x:inherit;y:inherit;s:inherit;e:inherit;&quot;,&quot;ease&quot;:&quot;Power2.easeInOut&quot;}]">
                                        Welcome to our resort, where comfort meets luxury, and relaxation is a way of life.
                                    </div>
                                    <div class="tp-caption tp-resizeme slider_button" id="slide-1588-layer-3"
                                        data-x="['left','left','left','15','0']" data-hoffset="['0','0','0','0']"
                                        data-y="['top','top','top','top']" data-voffset="['525','525','525','425','330']"
                                        data-fontsize="['14','14','14','14']" data-lineheight="['46','46','46','46']"
                                        data-width="none" data-height="none" data-whitespace="nowrap" data-type="text"
                                        data-responsive_offset="on"
                                        data-frames="[{&quot;delay&quot;:10,&quot;speed&quot;:1500,&quot;frame&quot;:&quot;0&quot;,&quot;from&quot;:&quot;y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;&quot;,&quot;mask&quot;:&quot;x:0px;y:[100%];s:inherit;e:inherit;&quot;,&quot;to&quot;:&quot;o:1;&quot;,&quot;ease&quot;:&quot;Power2.easeInOut&quot;},{&quot;delay&quot;:&quot;wait&quot;,&quot;speed&quot;:1500,&quot;frame&quot;:&quot;999&quot;,&quot;to&quot;:&quot;y:[175%];&quot;,&quot;mask&quot;:&quot;x:inherit;y:inherit;s:inherit;e:inherit;&quot;,&quot;ease&quot;:&quot;Power2.easeInOut&quot;}]">
                                        <a class="slider_btn" href="{{ route('room2.index') }}">Reserve now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Controls -->
                <!-- Previous Button -->
                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon"><i class="fa fa-angle-left"></i></span>
                </a>
                <!-- Next Button -->
                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                    <span class="carousel-control-next-icon"><i class="fa fa-angle-right"></i></span>
                </a>
            </div>
        </section>
        <!--================End Slider Area =================-->

        
        <!--================Book A Table Area =================-->
        <section class="book_table_area">
            <div class="container">
                <form class="row g-2" method="post" action="{{ route('rooms.search') }}">
                    @csrf
                    <div class="book_table_inner row m0 text-center">
                        <div class="book_table_item">
                            <div class="input-append">
                                <label for="Check_in_date">Check In Date</label>
                                <input name="check_in_date" type="date" id="check_in_date" placeholder="Check in"
                                    class="form-control @error('check_in_date') is-invalid @enderror"
                                    value="{{ old('check_in_date', $fields['check_in_date'] ?? '') }}"
                                    min="{{ now()->format('Y-m-d') }}" required />
                                @error('check_in_date')
                                    <div class="invalid-feedback" style="color:red;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="book_table_item">
                            <div class="input-append">
                                <label for="Check_Out_date">Check Out Date</label>
                                <input name="check_out_date" type="date" id="check_out_date" placeholder="Check out"
                                    class="form-control @error('check_out_date') is-invalid @enderror"
                                    value="{{ old('check_out_date', $fields['check_out_date'] ?? '') }}" required />
                                @error('check_out_date')
                                    <div class="invalid-feedback" style="color:red;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                       
                        <div class="book_table_item">
                            <!-- <a class="book_now_btn" href="#">Check Availability</a> -->
                            <label for="Check_Out_date"></label>
                            <button type="submit" class="btn book_now_btn w-100">Check Availability</button>
                        </div>
                    </div>
            </div>
            </form>
        </section>
        <!--================End Book A Table Area =================-->
        <!--================Introduction Area =================-->
        <section class="introduction_area">
            <div class="container">
                <div class="row introduction_inner">
                    <div class="col-md-7">
                        <div class="introduction_left_text">
                            <div class="intro_title">
                                <h2>Introduction <span>of resort</span></h2>
                                <p>Welcome to our resort, where comfort meets luxury, and relaxation is a way of life. </p>
                            </div>
                            <h4>We are delighted to be available for your business needs</h4>
                            <p>providing an exceptional experience tailored to your desires.
                                There may be times when challenges arise, but our team is committed to ensuring your stay is
                                enjoyable. With unparalleled hospitality, we aim to exceed your expectations and offer
                                unforgettable moments. Whether you're here for business or leisure, we are dedicated to
                                making your time with us truly memorable.</p>
                            <a class="about_btn_b" href="{{ route('about.index') }}">More About us</a>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="introduction_img">
                            <img src="{{ asset('assets2/img/328712599_1157630461497820_2600237647450996729_n.jpg') }}"
                                alt="" style="width:450px;height:358px;">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--================End Introduction Area =================-->
        <!--================Explor Room Area =================-->
        <section class="explor_room_area">
            <div class="container">
                <div class="explor_title row m0">
                    <div class="pull-left">
                        <div class="left_ex_title">
                            <h2>Explor Our <span>rooms</span></h2>
                            <p>choose room according to budget</p>
                        </div>
                    </div>
                    <div class="pull-right">
                        <a class="about_btn_b" href="{{ route('room2.index') }}">view all rooms</a>
                    </div>
                </div>
                <div class="row explor_room_item_inner">
                    @foreach ($rooms as $room)
                        <div class="col-md-4 col-sm-6">
                            <div class="explor_item">
                                <a href="{{ route('roomdetails.index', $room->id) }}" class="room_image">
                                    <img src="{{ asset(Storage::url($room->thumbnail_image)) }}" alt=""
                                        class="room-image">
                                </a>
                                <div class="explor_text">
                                    <a href="{{ route('roomdetails.index', $room->id) }}">
                                        <h4>Room Number : {{ $room->room_number }}</h4>
                                    </a>
                                    <ul>
                                        <li><a href="#">Capacity: {{ $room->capacity }}</a></li>
                                        <li><a href="#">Mountain view</a></li>
                                        <li><a href="#">({{ $room->floor }} Floor)</a></li>
                                    </ul>
                                    <div class="explor_footer">
                                        <div class="pull-left" style="text-size:">
                                            <h3>৳{{ $room->price_per_night }} <span>/ Night</span></h3>
                                        </div>
                                        <div class="pull-right">
                                            <a class="book_now_btn"
                                                href="{{ route('roomdetails.index', $room->id) }}">View details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!--================End Explor Room Area =================-->
        <!--================Our Service Area =================-->
        <section class="our_service_area">
            <div class="container">
                <div class="row our_service_inner">
                    <div class="col-md-3 col-sm-6">
                        <div class="our_service_first">
                            <h3>our services</h3>
                            <p>At our resort, we offer a wide range of services to ensure a delightful and memorable stay
                                for every guest. Whether you're here to unwind, explore, or conduct business, we have
                                everything you need.</p>
                            <a class="all_s_btn" href="#">view all services</a>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="our_service_item">
                            <img src="{{ asset('assets2/img/icon/service-1.png') }}" alt="">
                            <h4>Free wifi</h4>
                            <p>Incidunt ut labore et dolore magnam aliquam quaerat volup tatem. Utad minima.</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="our_service_item">
                            <img src="{{ asset('assets2/img/icon/service-2.png') }}" alt="">
                            <h4>Breakfast</h4>
                            <p>Incidunt ut labore et dolore magnam aliquam quaerat volup tatem. Utad minima.</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="our_service_item">
                            <img src="{{ asset('assets2/img/icon/service-3.png') }}" alt="">
                            <h4>Breakfast</h4>
                            <p>Incidunt ut labore et dolore magnam aliquam quaerat volup tatem. Utad minima.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--================End Our Service Area =================-->
        <!--================Our Resort Gallery Area =================-->
        <section class="our_resort_gallery_area">
            <div class="middle_title">
                <h2>our resort <span>gallery</span></h2>
                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum</p>
            </div>
        </section>
        <div class="resort_gallery_inner imageGallery1">
            <div class="resort_gallery owl-carousel">
                @foreach ($galleryImages as $image)
                    <div class="item">
                        <img src="{{ asset('storage/' . $image->image) }}" alt="Resort Gallery Image"
                        
                            style="width:442px;height:355px;">
                        <div class="resort_g_hover">
                            <div class="resort_hover_inner">
                                <a class="light" href="{{ asset('storage/' . $image->image) }}"
                                    style="width:700px;height:500px;">
                                    <i class="fa fa-expand" aria-hidden="true"></i>
                                </a>
                                <h5>{{ $image->title ?? 'Gallery Image' }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!--================End Our Resort Gallery Area =================-->
        <!--================Client Testimonial Area =================-->
        <section class="client_area">
            <div class="container">
                <div class="clients_slider owl-carousel">
                    <div class="item">
                        <div class="media">
                            <div class="media-left">
                                <img src="{{ asset('assets2/img/clients/Screenshot_4.png') }}" alt="Client 1"
                                    class="circular_image">
                            </div>
                            <div class="media-body">
                                <p><i>“</i> The rooms were okay but the outdoor was pretty chilled and gave a Bali vibe. The
                                    food was also okay here. They have family and couple-rooms. We went with some friends
                                    and enjoyed our stay here!
                                    They have -
                                    6 couple rooms
                                    2 family cottages
                                    One dining space
                                    Just beside the beach.
                                </p>
                                <a href="#">
                                    <h4>- Md. Kaiyum Dhali</h4>
                                </a>
                                <h5>Programmer at NRB Telecom Ltd.</h5>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="media">
                            <div class="media-left">
                                <img src="{{ asset('assets2/img/clients/Screenshot_1.png') }}" alt="Client 1"
                                    class="circular_image">
                            </div>
                            <div class="media-body">
                                <p><i>“</i> The rooms were okay but the outdoor was pretty chilled and gave a Bali vibe. The
                                    food was also okay here. They have family and couple-rooms. We went with some friends
                                    and enjoyed our stay here!
                                    They have -
                                    6 couple rooms
                                    2 family cottages
                                    One dining space
                                    Just beside the beach..</p>
                                <a href="#">
                                    <h4>- Din Mohammed Reza</h4>
                                </a>
                                <h5>Programmer at NRB Telecom Ltd.</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--================End Client Testimonial Area =================-->
        <!--================Latest News Area =================-->
        <section class="latest_news_area">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row latest_news_left">
                            <div class="left_ex_title">
                                <h2>Latest <span>News</span></h2>
                            </div>
                            <div class="col-sm-6">
                                <div class="l_news_item">
                                    <a href="blog-details.html" class="news_img">
                                        <img src="{{ asset('img/blog/latest-news/l-news-1.jpg') }}" alt="">
                                    </a>
                                    <div class="news_text">
                                        <a class="l_date" href="#">26 Aug 2017</a>
                                        <a href="#">
                                            <h4>A Night in Resort with Family</h4>
                                        </a>
                                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusa nt ium
                                            dolor emque ...</p>
                                        <a class="news_more" href="blog-details.html">Read more</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="l_news_item">
                                    <a href="blog-details.html" class="news_img">
                                        <img src="{{ asset('img/blog/latest-news/l-news-2.jpg') }}" alt="">
                                    </a>
                                    <div class="news_text">
                                        <a class="l_date" href="#">26 Aug 2017</a>
                                        <a href="#">
                                            <h4>A Night in Resort with Family</h4>
                                        </a>
                                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusa nt ium
                                            dolor emque ...</p>
                                        <a class="news_more" href="blog-details.html">Read more</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="right_event">
                            <div class="left_ex_title">
                                <h2>Upcoming <span>Events</span></h2>
                            </div>
                            <div class="right_event_area">
                                <div class="media">
                                    <div class="media-left">
                                        <h3>17<span>Aug</span></h3>
                                    </div>
                                    <div class="media-body">
                                        <h4>Anneversay of our resort</h4>
                                    </div>
                                </div>
                                <div class="media">
                                    <div class="media-left">
                                        <h3>25<span>Dec</span></h3>
                                    </div>
                                    <div class="media-body">
                                        <h4>Anneversay of our resort</h4>
                                    </div>
                                </div>
                                <a class="all_s_btn" href="#">view all events</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--================End Latest News Area =================-->
        <!--================Video Area =================-->
        <section class="video_area">
            <div class="container">
                <div class="video_inner">
                    <a class="popup-youtube" href="https://www.youtube.com/watch?v=48uPk1SA37Y"><img
                            src="img/icon/video-icon.png" alt=""></a>
                    <h4>virtual Tour</h4>
                    <h5>of beachvalley Resort</h5>
                </div>
            </div>
        </section>
        <!--================End Video Area =================-->
        <!--================Fun Fact Area =================-->
        <section class="fun_fact_area">
            <div class="container">
                <div class="row">
                    <div class="fun_fact_box row m0">
                        <div class="col-md-3 col-sm-6">
                            <div class="media">
                                <div class="media-left">
                                    <h3 class="counter">712</h3>
                                </div>
                                <div class="media-body">
                                    <h4>new <br /> friendships</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="media">
                                <div class="media-left">
                                    <h3 class="counter">254</h3>
                                </div>
                                <div class="media-body">
                                    <h4>International <br /> Guests</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="media">
                                <div class="media-left">
                                    <h3 class="counter">430</h3>
                                </div>
                                <div class="media-body">
                                    <h4>five stars <br /> rating</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="media">
                                <div class="media-left">
                                    <h3 class="counter">782</h3>
                                </div>
                                <div class="media-body">
                                    <h4>Served <br /> Breakfast</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row fun_subscrib_inner">
                    <div class="col-md-5">
                        <div class="left_text_subs">
                            <p>Subscribe to our brief newsletter to get exclusive discounts and new theme launches right in
                                your inbox.</p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search for...">
                            <span class="input-group-btn">
                                <button class="btn btn-default submit_btn" type="button">Subscribtion</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--================End Fun Fact Area =================-->
    </main>
    <style>
        /* Ensure the navigation buttons are positioned correctly */
        .carousel-control {
            width: 5%;
            height: 100%;
            top: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            /* Center vertically */
            justify-content: center;
            opacity: 1;
            transition: opacity 0.3s ease-in-out;
        }
        /* Custom Styling for the Left and Right Controls */
        /* Style the Icons */
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            font-size: 40px;
            color: white;
            background: rgba(0, 0, 0, 0.5);
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        /* Ensure Font Awesome Icons are visible */
        .carousel-control-prev-icon i,
        .carousel-control-next-icon i {
            font-size: 40px;
            color: white;
        }
        /* Hover Effect */
        .carousel-control:hover .carousel-control-prev-icon,
        .carousel-control:hover .carousel-control-next-icon {
            background-color: rgba(0, 0, 0, 0.8);
        }
        .carousel-caption {
            position: absolute;
            top: 50%;
            left: 5%;
            transform: translateY(-50%);
            text-align: left;
            width: 40%;
            /* Adjust width as needed */
        }
        .slider_text_box {
            position: absolute;
            top: 50%;
            left: 5%;
            transform: translateY(-50%);
            text-align: left;
            max-width: 500px;
            /* Adjust width based on design */
        }
        .slider_text_box .tp-caption {
            color: #fff;
            /* Ensure text is visible on the slider */
        }
        .first_text {
            font-size: 55px;
            font-weight: bold;
            line-height: 59px;
        }
        .secand_text {
            font-size: 18px;
            line-height: 26px;
            margin-top: 10px;
        }
        .slider_button {
            margin-top: 20px;
        }
        .slider_button .slider_btn:hover {
            background: #cc5200;
        }
        .carousel-inner .item img {
            width: 100%;
            height: auto;
            object-fit: cover;
            /* Ensures the image scales properly without distortion */
            max-height: 768px;
            /* Adjust this value based on design preference */
        }
        @media (max-width: 768px) {
            .carousel-inner .item img {
                height: 400px;
                /* Adjust the height for tablets */
            }
        }
        @media (max-width: 480px) {
            .carousel-inner .item img {
                height: 250px;
                /* Adjust the height for mobile screens */
            }
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let checkInInput = document.getElementById("check_in_date");
            let checkOutInput = document.getElementById("check_out_date");
            // Set the minimum check-in date to today
            let today = new Date().toISOString().split('T')[0];
            checkInInput.setAttribute("min", today);
            // Function to update check-out date based on check-in date
            checkInInput.addEventListener("change", function() {
                let checkInDate = checkInInput.value;
                checkOutInput.setAttribute("min", checkInDate);
            });
        });
    </script>
@endsection
