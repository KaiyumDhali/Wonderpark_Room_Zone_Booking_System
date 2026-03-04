@extends('pages.frontend.layout.App')
@yield('ecommerce')

@section('content')
    <main>
        <!--================Search Room Area =================-->
        <section class="room_details_area">
            <div class="container">
                <div class="row room_details_inner " style="margin-top: 80px;">
                    <div class="col-md-8">
                        <div class="room_details_content">


                            <div class="room_d_main_text">
                                <div class="room_details_img owl-carousel">
                                    @foreach ($rooms->details as $detail)
                                        <div class="item">

                                            <img src="{{ asset(Storage::url($detail->image_path)) }}" alt=""
                                                class="room-detail-image">

                                        </div>
                                    @endforeach

                                </div>
                                <a href="#">
                                    <h4>Room Number : {{ $rooms->room_number }}</h4>
                                </a>
                                <h5>৳{{ $rooms->price_per_night }}<span>/ Night</span></h5>
                                <p>{{ $rooms->description }}</p>

                            </div>
                            <div class="room_service_list">
                                <h3 class="room_d_title">Room services</h3>
                                <div class="row room_service_list_inner">
                                    <div class="col-sm-5 col-md-offset-right-1">
                                        <div class="resot_list">
                                            <ul>
                                                <li><a href="#"><i class="fa fa-caret-right"
                                                            aria-hidden="true"></i>Breakfast Included</a></li>
                                                <li><a href="#"><i class="fa fa-caret-right"
                                                            aria-hidden="true"></i>Free wifi</a></li>
                                                <li><a href="#"><i class="fa fa-caret-right"
                                                            aria-hidden="true"></i>Double Bed</a></li>
                                                <li><a href="#"><i class="fa fa-caret-right"
                                                            aria-hidden="true"></i>120 sq mt.</a></li>
                                                <li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>8
                                                        persons</a></li>
                                                <li><a href="#"><i class="fa fa-caret-right"
                                                            aria-hidden="true"></i>Free internet</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-5 col-md-offset-right-1">
                                        <div class="resot_list">
                                            <ul>
                                                <li><a href="#"><i class="fa fa-caret-right"
                                                            aria-hidden="true"></i>private balcony</a></li>
                                                <li><a href="#"><i class="fa fa-caret-right"
                                                            aria-hidden="true"></i>good room service</a></li>
                                                <li><a href="#"><i class="fa fa-caret-right"
                                                            aria-hidden="true"></i>flat screen tv</a></li>
                                                <li><a href="#"><i class="fa fa-caret-right"
                                                            aria-hidden="true"></i>fully AC</a></li>
                                                <li><a href="#"><i class="fa fa-caret-right"
                                                            aria-hidden="true"></i>mountain view</a></li>
                                                <li><a href="#"><i class="fa fa-caret-right"
                                                            aria-hidden="true"></i>free pick & drop facilies</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="search_right_sidebar">
                            <div class="book_room_area">
                                <div class="book_room_box">
                                    <div class="book_table_item">
                                        <h3>check availability</h3>
                                    </div>

                                    <div class="book_room_box">
                                        <!-- Success Message -->
                                        @if (session('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif

                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <form action="{{ route('book.room') }}" method="POST">
                                            @csrf
                                            {{-- <input type="hidden" name="room_id" value="{{ $rooms->id }}"> --}}
                                            <input type="hidden" name="room_id" value="{{ $rooms->id }}">
                                            <input type="hidden" name="Booking_status" value="0">
                                            <input type="hidden" name="customer_type" value="1">

                                            <div class="form-group">
                                                <label for="arrival_date">Check in Date</label>
                                                <input type="text" id="arrival_date" name="arrival_date"
                                                    placeholder="Select Date" class="form-control" readonly>
                                                @error('arrival_date')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>


                                            <div class="form-group">
                                                <label for="departure_date">Check Out Date</label>
                                                <input type="text" id="departure_date" name="departure_date"
                                                    placeholder="Select Date" class="form-control" readonly>
                                                @error('arrival_date')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="customer_name">Your Name</label>
                                                <input type="text" id="customer_name" name="customer_name"
                                                    value="{{ old('customer_name') }}" class="form-control" required>
                                                @error('customer_name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>


                                            <div class="form-group">
                                                <label for="customer_phone">Mobile</label>
                                                <input type="phone" id="customer_phone" name="customer_phone"
                                                    value="{{ old('customer_phone') }}" class="form-control" required>
                                                @error('customer_email')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="customer_phone">Address</label>
                                                <input type="text" id="customer_address" name="customer_address"
                                                    value="{{ old('customer_address') }}" class="form-control" required>
                                                @error('customer_email')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>


                                            <div class="book_now_button">
                                                <button type="submit" class="book_now_btn_black">Book Now</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>

                            <div class="your_book_box">
                                <h4>Your book</h4>
                                <h5>your cart is empty</h5>
                            </div>

                            
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--================End Search Room Area =================-->



    </main>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

   <script type="text/javascript">
   document.addEventListener('DOMContentLoaded', function () {
    // Convert Laravel bookings into an array of blocked dates
    const blockedDates = {!! json_encode($bookings) !!}.flatMap(booking => {
        const start = new Date(booking.start);
        const end = new Date(booking.end);
        const dates = [];
        while (start <= end) {
            dates.push(start.toISOString().split('T')[0]);
            start.setDate(start.getDate() + 1);
        }
        return dates;
    });

    // Function to find the first blocked date after check-in
    function getNextBlockedDate(startDate) {
        let currentDate = new Date(startDate);
        currentDate.setDate(currentDate.getDate() + 1); // Move to next day

        while (!blockedDates.includes(currentDate.toISOString().split('T')[0])) {
            currentDate.setDate(currentDate.getDate() + 1);
            if (currentDate > new Date("2099-12-31")) break; // Safety check
        }
        return currentDate.toISOString().split('T')[0]; // Return first blocked date
    }

    // Initialize Flatpickr for Check-in
    const arrivalPicker = flatpickr("#arrival_date", {
        dateFormat: "Y-m-d",
        disable: blockedDates,
        minDate: "today",
        onChange: function (selectedDates) {
            if (selectedDates.length > 0) {
                let checkInDate = selectedDates[0];
                let firstBlockedDate = getNextBlockedDate(checkInDate);

                // Set minDate for checkout (should be after check-in)
                let nextDay = new Date(checkInDate);
                nextDay.setDate(nextDay.getDate() + 1);
                let nextDayFormatted = nextDay.toISOString().split('T')[0];

                // Update checkout picker
                departurePicker.set("minDate", nextDayFormatted);
                departurePicker.set("maxDate", firstBlockedDate); // Checkout possible up to first blocked date
                departurePicker.set("disable", [...blockedDates, checkInDate.toISOString().split('T')[0]]); // Block check-in date
                departurePicker.clear(); // Clear preselected checkout date

                // Block the selected check-in date in the check-in picker
                arrivalPicker.set("disable", [...blockedDates, checkInDate.toISOString().split('T')[0]]);
            }
        },
    });

    // Initialize Flatpickr for Check-out
    const departurePicker = flatpickr("#departure_date", {
        dateFormat: "Y-m-d",
        disable: blockedDates,
        minDate: "today",
    });
});

</script>






@endsection
