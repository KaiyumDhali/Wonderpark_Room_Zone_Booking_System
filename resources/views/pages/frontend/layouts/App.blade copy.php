<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from designarc.biz/demos/hilltown/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 13 Jan 2025 12:07:12 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ asset('assets2/img/fav-icon.png') }}" type="image/x-icon" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>@yield('Resort')</title>

    <!-- Icon css link -->
    <link href="{{ asset('assets2/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets2/vendors/stroke-icon/style.css') }}" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="{{ asset('assets2/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Rev slider css -->
    <link href="{{ asset('assets2/vendors/revolution/css/settings.css') }}" rel="stylesheet">
    <link href="{{ asset('assets2/vendors/revolution/css/layers.css') }}" rel="stylesheet">
    <link href="{{ asset('assets2/vendors/revolution/css/navigation.css') }}" rel="stylesheet">
    <link href="{{ asset('assets2/vendors/animate-css/animate.css') }}" rel="stylesheet">

    <!-- Extra plugin css -->
    <link href="{{ asset('assets2/vendors/magnify-popup/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('assets2/vendors/owl-carousel/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets2/vendors/bootstrap-datepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets2/vendors/bootstrap-selector/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('assets2/vendors/lightbox/simpleLightbox.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('vendors/lightbox/css/lightbox.css') }}" rel="stylesheet"> -->

    <link href="{{ asset('assets2/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets2/css/responsive.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/pe-icon-7-stroke/1.2.0/pe-icon-7-stroke.min.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

</head>

<body>

    <!--================Header Area =================-->
    <header class="main_header_area">
        <div class="header_top">
            <div class="container">
                <div class="header_top_inner">
                    <div class="pull-left">
                        <a href="#"><i class="fa fa-phone"></i>{{ $company->company_mobile }}</a>
                        <a href="#"><i class="fa fa-envelope-o"></i>{{ $company->company_email }}</a>


                    </div>
                    <div class="pull-right">
                        <ul class="header_social">
                            <li><a href="https://www.facebook.com/beachvalleyresort/"><i class="fa fa-facebook"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="https://beachvalleyresort.com/"><i class="fa fa-google-plus"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="header_menu">
            <nav class="navbar navbar-default">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="{{ route('index.view') }}">
                            <img src="{{ asset(Storage::url($company->company_logo_one)) }}" alt=""
                                style="width:110px;height:70px;">
                            <img src="{{ asset(Storage::url($company->company_logo_one)) }}"
                                alt=""style="width:110px;height:60px;">
                        </a>

                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li><a href="{{ route('index.view') }}">Home</a></li>
                            <li><a href="{{ route('room2.index') }}">Rooms</a></li>
                            <li><a href="{{ route('about.index') }}">About Us</a></li>
                            <li><a href="{{ route('contact.index') }}">Contact Us</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="search_dropdown">
                                <a class="popup-with-zoom-anim" href="#test-search" style="
    color: black;"><i
                                        class="icon icon-Search"></i></a>
                            </li>
                            <li class="book_btn">
                                <a class="book_now_btn" href="{{ route('room2.index') }}">Book now</a>
                            </li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
        </div>
    </header>
    <!--================Header Area =================-->

    <main>
        @yield('content')
    </main>

    <!--================Footer Area =================-->
    <footer class="footer_area">
        <div class="footer_widget_area">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-xs-6">
                        <aside class="f_widget about_widget">
                            <img src="{{ asset(Storage::url($company->company_logo_two)) }}" alt="">
                            <div class="ab_wd_list">
                                <div class="media">
                                    <div class="media-left">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <div class="media-body">
                                        <h4 style="color:#D0D0D1">{{ $company->company_address }}</h4>
                                    </div>
                                </div>
                                <div class="media">
                                    <div class="media-left">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <div class="media-body">
                                        <h4 style="color:#D0D0D1">{{ $company->company_mobile }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="book_now_area">
                                <a class="book_now_btn" href="{{ route('room2.index') }}">Book now</a>
                            </div>
                        </aside>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <aside class="f_widget link_widget">
                            <div class="f_title">
                                <h3>Extra Links</h3>
                            </div>
                            <ul>
                                <li><a href="{{ route('about.index') }}">- About Us</a></li>
                                <li><a href="#">- Faq’s</a></li>
                                <li><a href="#">- Blog</a></li>
                                <li><a href="#">- Testimonials</a></li>
                                <li><a href="{{ route('room2.index') }}">- Reservation Now</a></li>
                            </ul>
                        </aside>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <aside class="f_widget link_widget">
                            <div class="f_title">
                                <h3>our services</h3>
                            </div>
                            <ul>
                                <li><a href="#">- Food & Drinks</a></li>
                                <li><a href="#">- Rooms</a></li>
                                <li><a href="#">- Amenities</a></li>
                                <li><a href="#">- Spa & Gym</a></li>
                                <li><a href="#">- Hill Tours</a></li>
                            </ul>
                        </aside>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <aside class="f_widget instagram_widget">
                            <div class="f_title">
                                <h3>Instagram</h3>
                            </div>
                            <ul class="instagram_list" id="instafeed"></ul>
                        </aside>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer_copyright_area">
            <div class="container">
                <div class="pull-left">
                    <h4>Copyright © beachvalley Resort
                        <script>
                            document.write(new Date().getFullYear());
                        </script>. All rights reserved.
                    </h4>
                </div>
                <div class="pull-right">
                    <h4>Created by: <a href="http://nrbtelecom.com/nrbit/index.php">NRB Telecom Ltd.</a></h4>
                </div>
            </div>
        </div>
    </footer>
    <!--================End Footer Area =================-->

    <!--================Search Box Area =================-->
    <div class="search_area zoom-anim-dialog mfp-hide" id="test-search">
        <div class="search_box_inner">
            <h3>Search</h3>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search for...">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button"><i class="icon icon-Search"></i></button>
                </span>
            </div>
        </div>
    </div>
    <!--================End Search Box Area =================-->





    <!--================End Footer Area =================-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- jQuery -->
    <script src="{{ asset('assets2/js/jquery-2.2.4.js') }}"></script>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ asset('assets2/js/bootstrap.min.js') }}"></script>

    <!-- Rev slider js -->
    <script src="{{ asset('assets2/vendors/revolution/js/jquery.themepunch.tools.min.js') }}"></script>
    <script src="{{ asset('assets2/vendors/revolution/js/jquery.themepunch.revolution.min.js') }}"></script>
    <script src="{{ asset('assets2/vendors/revolution/js/extensions/revolution.extension.actions.min.js') }}"></script>
    <script src="{{ asset('assets2/vendors/revolution/js/extensions/revolution.extension.video.min.js') }}"></script>
    <script src="{{ asset('assets2/vendors/revolution/js/extensions/revolution.extension.slideanims.min.js') }}"></script>
    <script src="{{ asset('assets2/vendors/revolution/js/extensions/revolution.extension.layeranimation.min.js') }}">
    </script>
    <script src="{{ asset('assets2/vendors/revolution/js/extensions/revolution.extension.navigation.min.js') }}"></script>

    <!-- Magnific Popup -->
    <script src="{{ asset('assets2/vendors/magnify-popup/jquery.magnific-popup.min.js') }}"></script>

    <!-- Isotope -->
    <script src="{{ asset('assets2/vendors/isotope/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets2/vendors/isotope/isotope.pkgd.min.js') }}"></script>

    <!-- Counterup -->
    <script src="{{ asset('assets2/vendors/counterup/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets2/vendors/counterup/jquery.counterup.min.js') }}"></script>

    <!-- Owl Carousel -->
    <script src="{{ asset('assets2/vendors/owl-carousel/owl.carousel.min.js') }}"></script>

    <!-- Bootstrap Datepicker -->
    <script src="{{ asset('assets2/vendors/bootstrap-datepicker/bootstrap-datetimepicker.min.js') }}"></script>

    <!-- Bootstrap Selector -->
    <script src="{{ asset('assets2/vendors/bootstrap-selector/bootstrap-select.js') }}"></script>

    <!-- Lightbox -->
    <!-- <script src="{{ asset('vendors/lightbox/js/lightbox.min.js') }}"></script> -->
    <script src="{{ asset('assets2/vendors/lightbox/simpleLightbox.min.js') }}"></script>

    <!-- Instafeed -->
    <script src="{{ asset('assets2/vendors/instafeed/instafeed.min.js') }}"></script>
    <script src="{{ asset('assets2/vendors/instafeed/script.js') }}"></script>

    <!-- Theme Script -->
    <script src="{{ asset('assets2/js/theme.js') }}"></script>
    <!-- Include FullCalendar CSS & JS (CDN) -->




</body>

<!-- Mirrored from designarc.biz/demos/hilltown/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 13 Jan 2025 12:07:17 GMT -->

</html>
