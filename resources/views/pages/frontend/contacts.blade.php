@extends('pages.frontend.layouts.app')

@section('content')
    <!-- Page Title -->
    {{-- <section class="page-title" style="background-image: url(assets_2/images/main-slider/page-title-11.jpg);"> --}}
    <section class="page-title" style="background-image: url(storage/{{ $banner_image->banner_image }});">
        <div class="auto-container">
            <div class="text-center">
                <h1>Contact Us</h1>
            </div>
        </div>
    </section>

    <!-- Contact Form section -->
    <section class="contact-form-section light-bg mx-60 border-shape-top border-shape-bottom">
        <div class="auto-container">
            <div class="sec-title">
                <div class="sub-title">get in touch</div>
                <h2>Drop a message for any query</h2>
                <div class="text">

                    We’re here to help you make the most of your experience at Wonder Park.<br />

                    Whether you have a question, need assistance with bookings, or want to share your feedback — our team is
                    always ready to listen.<br />

                    Reach out to us using the details below or simply fill out the form. We look forward to hearing from
                    you!

                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <!--Contact Form-->
                    <div class="contact-form style-two">
                        <form method="post" action="" id="contact-form">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" name="form_name" value=""
                                        placeholder="Your Name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="email" class="form-control" name="email" value=""
                                        placeholder="Your Email" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" name="form_phone" value=""
                                        placeholder="Phone Number" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" name="form_subject" value=""
                                        placeholder="Subject" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <textarea name="form_message" class="form-control" placeholder="Write a massage"></textarea>
                                </div>
                                <div class="form-group col-md-12">
                                    <input id="form_botcheck" name="form_botcheck" class="form-control" type="hidden"
                                        value="">
                                    <button class="theme-btn btn-style-one" type="submit"
                                        data-loading-text="Please wait..."><span>Send a Message</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4 icon_box">
                    <div class="text-two d-flex align-items-center">
                        <i class="fas fa-map-marker-alt me-2" style="color: #dc3545;"></i>
                        {{ $company->company_address }}
                    </div>

                    <div class="text-three d-flex align-items-center">
                        <i class="fas fa-envelope me-2" style="color: #0d6efd;"></i>
                        <a href="mailto:{{ $company->company_email }}">{{ $company->company_email }}</a>
                    </div>


                    <div class="icon-box">
                        <h4>Reception Phone No.</h4>
                        <div class="icon">
                            <h5><i class="fas fa-phone"></i><a
                                    href="tel:{{ $company->company_mobile }}">{{ $company->company_mobile }}</a></h5>
                        </div>
                        {{-- <div class="text-four">Check in: 01.00 PM <br>Check out: 10.00 PM</div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Map Section -->
    <section class="map-section-two mx-60">
        <div class="auto-container">
            <div class="contact-map">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d58305.920075031754!2d90.829181!3d24.026833!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x37542551c518a239%3A0x64646ae788555d11!2sWonder%20Park%20%26%20Eco%20Resort!5e0!3m2!1sen!2sus!4v1752412985710!5m2!1sen!2sus"
                    width="600" height="500" frameborder="0" style="border:0; width: 100%" allowfullscreen=""
                    aria-hidden="false" tabindex="0"></iframe>
            </div>
        </div>
    </section>
@endsection
