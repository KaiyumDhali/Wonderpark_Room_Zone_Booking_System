@extends('pages.frontend.layouts.app')




@section('content')
    <style>
        .check-availability form ul li {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            /* Optional spacing between inputs */
        }

        .check-availability form ul li input.form-control {
            width: 100%;
        }
    </style>

    <!-- Page Title -->
    {{-- <section class="page-title" style="background-image: url(assets_2/images/main-slider/page-title-6.jpg);"> --}}
    <section class="page-title" style="background-image: url(storage/{{ $banner_image->banner_image }});">
        <div class="auto-container">
            <div class="text-center">
                <h1>Room details</h1>
            </div>
        </div>
    </section>


    <!-- room details -->
    <section class="room-details-section light-bg mx-60 border-shape-top">
        <div class="auto-container">
            <div class="single-items-carousel">
                <!-- Swiper -->
                <div class="swiper-container single-item-with-pager-carousel">
                    <div class="swiper-wrapper">
                        @foreach ($rooms->details as $detail)
                            <div class="swiper-slide">
                                <div class="image">
                                    <img src="{{ asset(Storage::url($detail->image_path)) }}" alt="Room Image">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="h_10 w_10"></div>
                <div class="swiper-container single-item-with-pager-thumb">
                    <div class="swiper-wrapper">
                        @foreach ($rooms->details as $detail)
                            <div class="swiper-slide">
                                <div class="thumb">
                                    <img src="{{ asset(Storage::url($detail->image_path)) }}" alt="Room Thumbnail">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="room-block">
                        {{-- ৳ --}}
                        <div class="pricing  px-1">BDT {{ $rooms->price_per_night }} / Night</div>
                        <h2 class="sec-title">Room Number: {{ $rooms->room_number }}</h2>
                        <div class="text">{{ $rooms->description }}</div>
                    </div>

                    {{-- <h4>Amenities</h4>
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-one">
                            <li><i class="flaticon-breakfast"></i> Breakfast Included</li>
                            <li><i class="flaticon-wifi-signal"></i> Free WiFi</li>
                            <li><i class="flaticon-bed"></i> Double Bed</li>
                            <li><i class="flaticon-blueprint"></i> 120 sq mt.</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <ul class="list-one">
                            <li><i class="flaticon-user"></i> 8 Persons</li>
                            <li><i class="flaticon-terrace"></i> Private Balcony</li>
                            <li><i class="flaticon-iron"></i> Good Room Service</li>
                            <li><i class="flaticon-air-conditioner"></i> Fully AC</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <ul class="list-one">
                            <li><i class="flaticon-mountain"></i> Mountain View</li>
                            <li><i class="flaticon-pickup-truck"></i> Free Pick & Drop</li>
                            <li><i class="flaticon-television"></i> Flat Screen TV</li>
                            <li><i class="flaticon-cable-tv"></i> Free Internet</li>
                        </ul>
                    </div>
                </div> --}}

                    {{-- <h4>Availability</h4>
                <div>
                    <input type="text" id="arrival_date" placeholder="Check In" class="datepicker">
                    <input type="text" id="departure_date" placeholder="Check Out" class="datepicker">
                </div> --}}
                </div>

                <div class="col-md-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <script>
                            setTimeout(function() {
                                let alert = document.getElementById('success-alert');
                                if (alert) {
                                    // Bootstrap's fade-out and remove
                                    alert.classList.remove('show');
                                    alert.classList.add('fade');
                                    setTimeout(() => alert.remove(), 500); // Remove after fade
                                }
                            }, 3000); // 3 seconds
                        </script>
                    @endif --}}


                    <!-- Check availability -->
                    <div class="check-availability style-seven">
                        <h3>Book Your Room</h3>
                        <form action="{{ route('book.room') }}" method="POST">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $rooms->id }}">
                            <input type="hidden" name="Booking_status" value="0">
                            <input type="hidden" name="customer_type" value="1">

                            <ul>
                                <li>
                                    <p>Check In</p>
                                    <input type="text" id="arrival_date" name="arrival_date" placeholder="Select Date"
                                        class="form-control w-100" readonly>
                                    @error('arrival_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </li>

                                <li>
                                    <p>Check Out</p>
                                    <input type="text" id="departure_date" name="departure_date"
                                        placeholder="Select Date" class="form-control w-100" readonly>
                                    @error('departure_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </li>
                                <li>
                                    <p>Your Name</p>
                                    <input type="text" name="customer_name" value="{{ old('customer_name') }}"
                                        class="form-control w-100" required>
                                    @error('customer_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </li>
                                <li>
                                    <p>Mobile</p>
                                    <input type="text" name="customer_phone" value="{{ old('customer_phone') }}"
                                        class="form-control w-100" required>
                                    @error('customer_phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </li>
                                <li>
                                    <p>Address</p>
                                    <input type="text" name="customer_address" value="{{ old('customer_address') }}"
                                        class="form-control w-100" required>
                                    @error('customer_address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </li>
                            </ul>
                            <div class="right-side">
                                <button type="submit" class="theme-btn btn-style-one btn-md w-100">Book Now</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {


            // const blockedDates = {!! json_encode($bookings) !!}.flatMap(booking => {
            //     const start = new Date(booking.start);
            //     const end = new Date(booking.end);
            //     const dates = [];
            //     while (start <= end) {
            //         dates.push(start.toISOString().split('T')[0]);
            //         start.setDate(start.getDate() + 1);
            //     }
            //     return dates;
            // });

            const blockedDates = {!! json_encode($bookings) !!}.flatMap(booking => {
                const start = new Date(booking.start);
                const end = new Date(booking.end);
                end.setDate(end.getDate() - 1); // ✅ Subtract 1 day from end date

                const dates = [];
                while (start <= end) {
                    dates.push(start.toISOString().split('T')[0]);
                    start.setDate(start.getDate() + 1);
                }

                return dates;
            });


            function getNextBlockedDate(startDate) {
                let currentDate = new Date(startDate);
                currentDate.setDate(currentDate.getDate() + 1);
                while (!blockedDates.includes(currentDate.toISOString().split('T')[0])) {
                    currentDate.setDate(currentDate.getDate() + 1);
                    if (currentDate > new Date("2099-12-31")) break;
                }
                return currentDate.toISOString().split('T')[0];
            }

            const arrivalPicker = flatpickr("#arrival_date", {
                dateFormat: "Y-m-d",
                disable: blockedDates,
                minDate: "today",
                onChange: function(selectedDates) {
                    if (selectedDates.length > 0) {
                        let checkInDate = selectedDates[0];
                        let firstBlockedDate = getNextBlockedDate(checkInDate);

                        let nextDay = new Date(checkInDate);
                        nextDay.setDate(nextDay.getDate() + 1);
                        let nextDayFormatted = nextDay.toISOString().split('T')[0];

                        departurePicker.set("minDate", nextDayFormatted);
                        departurePicker.set("maxDate", firstBlockedDate);
                        departurePicker.set("disable", [...blockedDates, checkInDate.toISOString()
                            .split('T')[0]
                        ]);
                        departurePicker.clear();
                    }
                },
            });

            const departurePicker = flatpickr("#departure_date", {
                dateFormat: "Y-m-d",
                disable: blockedDates,
                minDate: "today",
            });
        });
    </script>
@endsection
