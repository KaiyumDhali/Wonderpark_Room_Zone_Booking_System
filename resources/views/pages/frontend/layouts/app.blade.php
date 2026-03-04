<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name') }}</title>
    <!-- Stylesheets -->
    <link href="{{ asset('assets_2/css/bootstrap.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets_2/css/style.css') }}" rel="stylesheet" />
    <!-- Responsive File -->
    <link href="{{ asset('assets_2/css/responsive.css') }}" rel="stylesheet" />
    <!-- Color File -->
    <link href="{{ asset('assets_2/css/color.css') }}" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;800&amp;family=Roboto:wght@400;700&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&amp;display=swap" rel="stylesheet" />

    <link rel="shortcut icon" href="{{ asset('assets_2/images/favicon.png') }}" type="image/x-icon" />
    <link rel="icon" href="{{ asset('assets_2/images/favicon.png') }}" type="image/x-icon" />

    <!-- Responsive -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <!--[if lt IE 9
      ]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script><![endif]-->
    <!--[if lt IE 9]>
      <script src="js/respond.js"></script><![endif]-->
</head>

<body class="dark_bg">
    <div class="page-wrapper">
        <!-- Main Header -->
        <header class="main-header header-style-one">
            <!-- Header Upper -->
            <div class="header-upper">
                <div class="auto-container">
                    <div class="inner-container">
                        <!--Logo-->
                        <div class="logo-box">
                            <div class="logo">
                                <a href="{{ route('frontend.home') }}">
                                    <img src="{{ asset(Storage::url($company->company_logo_one)) }}" alt="" />
                                </a>
                            </div>
                        </div>
                        <!--Nav Box-->
                        <div class="nav-outer">
                            <!--Mobile Navigation Toggler-->
                            <div class="mobile-nav-toggler">
                                <img src="assets_2/images/icons/icon-bar.png" alt="" />
                            </div>

                            <!-- Main Menu -->
                            <nav class="main-menu navbar-expand-md navbar-light">
                                <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
                                    <ul class="navigation">
                                        <li>
                                            <a href="{{ route('frontend.home') }}">Home</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('frontend.about') }}">About Us</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('frontend.rides') }}">Our Rides</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('frontend.spots') }}">Picnic Spots</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('frontend.rooms') }}">Rooms</a>
                                            {{-- <ul>
                                                <li><a href="room-grid.php">Room Grid Style</a></li>
                                                <li><a href="room-list.php">Room List Style</a></li>
                                                <li class="dropdown">
                                                    <a href="#">Single Room</a>
                                                    <ul>
                                                        <li>
                                                            <a href="booking-reservation.php">Booking with reservation
                                                                form</a>
                                                        </li>
                                                        <li>
                                                            <a href="booking-enquiry.php">Booking with enquiry form</a>
                                                        </li>
                                                        <li>
                                                            <a href="booking-banner.php">Booking with banner link</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul> --}}
                                        </li>

                                        <li>
                                            <a href="{{ route('frontend.photo') }}">Gallery</a>
                                        </li>

                                        <li>
                                            <a href="{{ route('frontend.video') }}">Video</a>
                                        </li>

                                        <li>
                                            <a href="{{ route('frontend.blogs') }}">Blog</a>
                                        </li>

                                        <li><a href="{{ route('frontend.contacts') }}">Contact</a></li>



                                    </ul>
                                </div>
                            </nav>
                            {{-- <div class="search-toggler"><i class="far fa-search"></i></div> --}}
                        </div>
                        <div class="right-column">
                            <div class="navbar-right">
                                <div class="link-btn">
                                    <a href="{{ route('frontend.rooms') }}"
                                        class="theme-btn btn-style-one">Reservation</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Header Upper-->

            <!-- Sticky Header  -->
            <div class="sticky-header">
                <div class="header-upper">
                    <div class="auto-container">
                        <div class="inner-container">
                            <!--Logo-->
                            <div class="logo-box">
                                <div class="logo">
                                    <a href="{{ route('frontend.home') }}"><img
                                            src="{{ asset(Storage::url($company->company_logo_one)) }}"
                                            alt="" /></a>
                                </div>
                            </div>
                            <!--Nav Box-->
                            <div class="nav-outer">
                                <!--Mobile Navigation Toggler-->
                                <div class="mobile-nav-toggler">
                                    <img src="assets_2/images/icons/icon-bar.png" alt="" />
                                </div>

                                <!-- Main Menu -->
                                <nav class="main-menu navbar-expand-md navbar-light"></nav>
                                <div class="search-toggler">
                                    <i class="far fa-search"></i>
                                </div>
                            </div>
                            <div class="right-column">
                                <div class="navbar-right">
                                    <div class="link-btn">
                                        <a href="booking-reservation.php"
                                            class="theme-btn btn-style-one">Reservation</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Sticky Menu -->


            <!-- Mobile Menu  -->
            <div class="mobile-menu">
                <div class="menu-backdrop"></div>
                <div class="close-btn"><i class="icon far fa-times"></i></div>

                <nav class="menu-box">
                    <div class="nav-logo">

                        <a href="{{ route('frontend.home') }}">
                            <img src="{{ asset(Storage::url($company->company_logo_one)) }}" alt="logo" />
                        </a>

                    </div>
                    <div class="menu-outer">
                        <!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header-->
                    </div>


                    <!--Social Links-->
                    {{-- <div class="social-links">
                        <ul class="clearfix">
                            <li>
                                <a href="#"><span class="fab fa-twitter"></span></a>
                            </li>
                            <li>
                                <a href="#"><span class="fab fa-facebook-square"></span></a>
                            </li>
                            <li>
                                <a href="#"><span class="fab fa-pinterest-p"></span></a>
                            </li>
                            <li>
                                <a href="#"><span class="fab fa-instagram"></span></a>
                            </li>
                            <li>
                                <a href="#"><span class="fab fa-youtube"></span></a>
                            </li>
                        </ul>
                    </div> --}}


                </nav>
            </div>
            <!-- End Mobile Menu -->



            <div class="nav-overlay">
                <div class="cursor"></div>
                <div class="cursor-follower"></div>
            </div>
        </header>
        <!-- End Main Header -->

        <!--Search Popup-->
        <div id="search-popup" class="search-popup">
            <div class="close-search theme-btn"><i class="far fa-times"></i></div>
            <div class="popup-inner">
                <div class="overlay-layer"></div>
                <div class="search-form">
                    <form method="post" action="https://html.tonatheme.com/2023/bluebell/index.php">
                        <div class="form-group">
                            <fieldset>
                                <input type="search" class="form-control" name="search-input" value=""
                                    placeholder="Search Here" required />
                                <input type="submit" value="Search Now!" class="theme-btn" />
                            </fieldset>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        @yield('content')


        <!--footer section  -->
        <footer class="main-footer">
            <div class="auto-container">
                <!-- footer gallery -->
                <div class="footer-gallery">
                    <div class="row">

                        @foreach ($gallery_footer as $gallery)
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <div class="image gallery-overlay">
                                    <div class="inner-box">
                                        <img src="{{ asset('storage/' . $gallery->image) }}" alt="" />
                                        <div class="overlay-box">
                                            <div class="overlay-inner">
                                                <div class="content">
                                                    <a href="{{ asset('storage/' . $gallery->image) }}"
                                                        class="lightbox-image link" data-fancybox="gallery">
                                                        <span class="icon fas fa-eye"></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
                <!--Widgets Section-->
                <div class="widgets-section">
                    <div class="row clearfix">
                        <!--Column-->
                        <div class="column col-lg-5">
                            <div class="widget contact-widget">

                                {{-- <div class="image">
                                    <a href="index.php"><img src="assets/images/footer-1.png" alt="" /></a>
                                </div> --}}

                                {{-- <div class="logo-box">
                                    <div class="logo">
                                        <a href="{{ route('frontend.home') }}">
                                            <img src="{{ asset(Storage::url($company->company_logo_two)) }}"
                                                alt="" />
                                        </a>
                                    </div>
                                </div> --}}

                                <div>
                                    <h3 class="widget-title">Wonder Park & Eco Resort</h3>
                                </div>

                                <table id="companyInfo">
                                    <tr>
                                        <td class="lebel">Mobile :</td>
                                        <td id="companyMobile"><a href="tel:">{{ $company->company_mobile }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lebel">Email :</td>
                                        <td id="companyEmail"><a href="mailto:">{{ $company->company_email }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lebel" style="width: 110px;">Location :</td>
                                        <td id="companyAddress">{{ $company->company_address }}</td>
                                    </tr>
                                </table>

                                {{-- <table id="companyInfo">
                                    <tr>
                                        <td class="lebel">Mobile :</td>
                                        <td id="companyMobile"><a href="tel:">Loading...</a></td>
                                    </tr>
                                    <tr>
                                        <td class="lebel">Email :</td>
                                        <td id="companyEmail"><a href="mailto:">Loading...</a></td>
                                    </tr>
                                    <tr>
                                        <td class="lebel">Location :</td>
                                        <td id="companyAddress">Loading...</td>
                                    </tr>
                                </table> --}}

                            </div>
                        </div>

                        <!--Column-->
                        <div class="column col-lg-3 mb-4">
                            <div class="widget links-widget">
                                <h3 class="widget-title">Quick Links</h3>
                                <div class="widget-content">
                                    <ul>
                                        <li><a href="{{ route('frontend.about') }}">About Us</a></li>
                                        <li><a href="{{ route('frontend.rides') }}">Our Rides</a></li>
                                        <li><a href="{{ route('frontend.rooms') }}">Rooms</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                        <!--Column-->
                        <div class="column col-lg-4">
                            <div class="widget links-widget">
                                <h3 class="widget-title">Quick Links</h3>
                                <div class="widget-content">
                                    <ul>
                                        <li><a href="{{ route('frontend.photo') }}">Gallery</a></li>
                                        <li><a href="{{ route('frontend.video') }}">Video</a></li>
                                        <li><a href="{{ route('frontend.blogs') }}">Blog</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <div class="auto-container">
                <div class="boder-bottom-four"></div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="auto-container">
                    <div class="wrapper-box">
                        <div class="copyright text-center">
                            <div class="text">
                                © Copyright {{ date('Y') }} Wonder Park. All rights reserved.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </div>
    <!--End pagewrapper-->

    <!--Scroll to top-->
    <div class="scroll-to-top scroll-to-target" data-target="html">
        <span class="fas fa-arrow-up"></span>
    </div>

    {{-- const APP_URL = "{{ env('APP_URL') }}";
    fetch(APP_URL + "/api/companies") --}}
    <script>
        fetch("http://192.168.10.3:8075/api/companies")
            .then(response => response.json())
            .then(data => {
                const company = data.data[0]; // assuming only one company

                // Mobile
                const mobile = company.company_mobile || 'N/A';
                const mobileLink = document.querySelector("#companyMobile a");
                mobileLink.href = "tel:" + mobile.replace(/[^0-9+]/g, '');
                mobileLink.textContent = mobile;

                // Email
                const email = company.company_email || 'N/A';
                const emailLink = document.querySelector("#companyEmail a");
                emailLink.href = "mailto:" + email;
                emailLink.textContent = email;

                // Address
                const address = company.company_address || 'N/A';
                document.getElementById("companyAddress").innerHTML = address.replace(/, /g, "<br>");
            })
            .catch(error => {
                console.error("Error fetching company info:", error);
            });
    </script>


    <script src="{{ asset('assets_2/js/jquery.js') }}"></script>
    <script src="{{ asset('assets_2/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets_2/js/jquery.fancybox.js') }}"></script>
    <script src="{{ asset('assets_2/js/isotope.js') }}"></script>
    <script src="{{ asset('assets_2/js/owl.js') }}"></script>
    <script src="{{ asset('assets_2/js/appear.js') }}"></script>
    <script src="{{ asset('assets_2/js/wow.js') }}"></script>
    <script src="{{ asset('assets_2/js/scrollbar.js') }}"></script>
    <script src="{{ asset('assets_2/js/TweenMax.min.js') }}"></script>
    <script src="{{ asset('assets_2/js/swiper.min.js') }}"></script>
    <script src="{{ asset('assets_2/js/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('assets_2/js/parallax-scroll.js') }}"></script>
    <script src="{{ asset('assets_2/js/jquery-ui-1.9.2.custom.min.js') }}"></script>
    <script src="{{ asset('assets_2/js/jquery.nice-select.min.js') }}"></script>

    <script src="{{ asset('assets_2/js/script.js') }}"></script>

</body>

</html>
