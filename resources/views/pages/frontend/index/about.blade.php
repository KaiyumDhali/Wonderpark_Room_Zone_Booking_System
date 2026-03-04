@extends('pages.frontend.layout.App')
@yield('ecommerce')

@section('content')
 
 <main>
 
 <!--================Resort Story Area =================-->
 <section class="introduction_area resort_story_area">
            <div class="container">
                <div class="row introduction_inner" style="margin-top: 80px;">
                    <div class="col-md-5">
                        <a href="#" class="introduction_img">
                            <img src="{{asset('assets2/img/resort-story-img.jpg')}}" alt="">
                        </a>
                    </div>
                    <div class="col-md-7">
                        <div class="introduction_left_text">
                            <div class="resort_title">
                                <h2>our resort <span>story</span></h2>
                                <h5>give best service to our customers</h5>
                            </div>
                            <h6>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum</h6>
                            <h4>We are Available for business</h4>
                            <p>quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam. quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea volup.</p>
                            <p>Ut enim ad minima veniam. quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatut.</p>
                            <a class="about_btn_b" href="{{route('contact.index')}}">contact us</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--================End Resort Story Area =================-->
        
        <!--================Choose Resot Area =================-->
        <section class="choose_resot_area">
            <div class="container">
                <div class="center_title">
                    <h2>why choose our <span>resot</span></h2>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum</p>
                </div>
                <div class="row choose_resot_inner">
                    <div class="col-md-5">
                        <div class="resot_list">
                            <ul>
                                <li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Wellness & poll</a></li>
                                <li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Free wifi</a></li>
                                <li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Bar & garden with terrace</a></li>
                                <li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Delicious breakfast</a></li>
                                <li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>HIgh customer satisfaction</a></li>
                                <li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Good parking & security</a></li>
                                <li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Clean room service</a></li>
                                <li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Discount coupons</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="choose_resot_slider owl-carousel">
                            <div class="item">
                                <img src="{{asset('assets2/img/resot/resot-1.jpg')}}"alt="">
                            </div>
                            <div class="item">
                                <img src="{{asset('assets2/img/resot/resot-1.jpg')}}"alt="">
                            </div>
                            <div class="item">
                                <img src="{{asset('assets2/img/resot/resot-1.jpg')}}"alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--================End Choose Resot Area =================-->
        
        <!--================Client Testimonial Area =================-->
        
        <!--================End Client Testimonial Area =================-->
        
        <!--================Video Area =================-->
        <section class="video_area">
            <div class="container">
                <div class="video_inner">
                    <a class="popup-youtube" href="https://www.youtube.com/watch?v=48uPk1SA37Y"><img src="{{asset('assets2/img/icon/video-icon.png')}}" alt=""></a>
                    <h4>virtual Tour</h4>
                    <h5>of Hill Town Resort</h5>
                </div>
            </div>
        </section>
        <!--================End Video Area =================-->
        
        <!--================Fun Fact Area =================-->
        <section class="fun_fact_area about_fun_fact">
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
            </div>
        </section>
        <!--================End Fun Fact Area =================-->

</main>
@endsection