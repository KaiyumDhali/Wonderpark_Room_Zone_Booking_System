@extends('pages.frontend.layout.App')
@yield('ecommerce')
<style>
    .room_pagination .simple-pagination {
    list-style: none;
    display: inline-block;
    padding: 0;
    margin: 20px 0;
}

.room_pagination .simple-pagination li {
    display: inline-block;
    margin: 0 3px;
}

.room_pagination .simple-pagination li a,
.room_pagination .simple-pagination li span {
    display: inline-block;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    color: #007bff;
    text-decoration: none;
}

.room_pagination .simple-pagination li a:hover {
    background-color: #f8f9fa;
}

.room_pagination .simple-pagination li.active span {
    background-color: #007bff;
    color: #fff;
    border-color: #007bff;
}

.room_pagination .simple-pagination li.disabled span {
    color: #ccc;
}
</style>
@section('content')
    <main>

        <!--================Explor Room Area =================-->
        <section class="explor_room_area explore_room_list">
            <div class="container">
                <div class="explor_title row m0 " style="margin-top: 80px;">
                    <div class="left_ex_title">
                        <h2>Explor Our <span>rooms</span></h2>
                        <p>choose room according to budget</p>
                    </div>
                </div>
                <div class="row explor_room_item_inner">
                    <section class="book_table_area">
                        <div class="container">
                            <form class="row g-2" method="post" action="{{ route('rooms.search') }}">
                                @csrf
                                <div class="book_table_inner row m0 text-center">
                                    <div class="book_table_item">
                                        <div class="input-append">
                                            <label for="Check_in_date">Check In Date</label>
                                            <input name="check_in_date" type="date" id="check_in_date"
                                                placeholder="Check in"
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
                                            <input name="check_out_date" type="date" id="check_out_date"
                                                placeholder="Check out"
                                                class="form-control @error('check_out_date') is-invalid @enderror"
                                                value="{{ old('check_out_date', $fields['check_out_date'] ?? '') }}"
                                                required />

                                            @error('check_out_date')
                                                <div class="invalid-feedback" style="color:red;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- <div class="book_table_item">
                                    <select class="selectpicker" name="capacity">
                                        <option value="{{ old('capacity', $fields['capacity'] ?? '') }}">Select Capacity</option>
                                        @foreach ($rooms->unique('capacity') as $room)
    <option value="{{ $room->capacity }}">{{ $room->capacity }}</option>
    @endforeach
                                    </select>
                                </div> -->


                                    <!-- <div class="book_table_item">
                                    <select class="selectpicker">
                                        <option>Adults</option>
                                        <option>Adults Two</option>
                                        <option>Adults Three</option>
                                    </select>
                                </div> -->
                                    <!-- <div class="book_table_item">
                                    <select class="selectpicker">
                                        <option>Children</option>
                                        <option>Children Two</option>
                                        <option>Children Three</option>
                                    </select>
                                </div> -->
                                    <div class="book_table_item">
                                        <!-- <a class="book_now_btn" href="#">Check Availability</a> -->
                                        <label for="Check_Out_date"></label>
                                        <label for="Check_Out_date"></label>
                                        <button type="submit" class="btn book_now_btn w-100">Check Availability</button>
                                    </div>
                                </div>
                        </div>
                        </form>
                    </section>


                    @foreach ($rooms as $room)
                        <div class="col-md-4 col-sm-6">
                            <div class="explor_item">
                                <a href="{{ route('roomdetails.index', $room->id) }}" class="room_image">
                                    <img src="{{ asset(Storage::url($room->thumbnail_image)) }}"
                                        alt="{{ $room->thumbnail_image }}" class="room-image">

                                </a>
                                <div class="explor_text">
                                    <a href="{{ route('roomdetails.index', $room->id) }}">
                                        <h4>Room Number : {{ $room->room_number }}</h4>
                                    </a>
                                    <ul>
                                        <li><a href="#">Capacity:{{ $room->capacity }}</a></li>
                                        <li><a href="#">Mountain view</a></li>
                                        <li><a href="#">{{ $room->floor }} Floor</a></li>
                                        <li><a href="#">{{ $room->room_id }} kaium</a></li>
                                    </ul>
                                    <div class="explor_footer">
                                        <div class="pull-left" style="text-size:">
                                            <h3>৳{{ $room->price_per_night }} <span>/ Night</span></h3>
                                        </div>
                                        <div class="pull-right">
                                            <a class="book_now_btn" href="{{ route('roomdetails.index', $room->id) }}">View
                                                details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <nav aria-label="Page navigation" class="room_pagination">
                    <ul class="simple-pagination">
                        {{-- Previous Page Link --}}
                        @if ($rooms->onFirstPage())
                            <li class="disabled"><span>&laquo;</span></li>
                        @else
                            <li><a href="{{ $rooms->previousPageUrl() . '&check_in_date=' . request('check_in_date') . '&check_out_date=' . request('check_out_date') . '&capacity=' . request('capacity') }}" rel="prev">&laquo;</a></li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($rooms->getUrlRange(1, $rooms->lastPage()) as $page => $url)
                            <li class="{{ $page == $rooms->currentPage() ? 'active' : '' }}">
                                <a href="{{ $url . '&check_in_date=' . request('check_in_date') . '&check_out_date=' . request('check_out_date') . '&capacity=' . request('capacity') }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($rooms->hasMorePages())
                            <li><a href="{{ $rooms->nextPageUrl() . '&check_in_date=' . request('check_in_date') . '&check_out_date=' . request('check_out_date') . '&capacity=' . request('capacity') }}" rel="next">&raquo;</a></li>
                        @else
                            <li class="disabled"><span>&raquo;</span></li>
                        @endif
                    </ul>
                </nav>

            </div>
        </section>
        <!--================End Explor Room Area =================-->
    </main>
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
