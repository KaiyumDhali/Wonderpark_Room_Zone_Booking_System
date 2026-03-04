@extends('pages.frontend.layouts.app')
@section('content')
    <!-- Page Title -->
    <section class="page-title" style="background-image: url({{ asset('storage/' . $banner_image->banner_image) }});">
        <div class="auto-container">
            <div class="text-center">
                <h1>Rides</h1>
            </div>
        </div>
    </section>
    <!-- Rides Section -->
    <section class="rides-section light-bg mx-60 py-5 border-shape-top border-shape-bottom"
        style="background-color: #d3d3d3!important;">
        <div class="auto-container">
            <!-- Entry Ticket -->
            <div class="title-box text-center pb-5">
                <div class="sub-title mb-4">Entry Ticket</div>
                <div class="text mb-4">
                    Your adventure at Wonder Park begins with a simple step—getting your entry ticket.
                    With this pass, you unlock a world of excitement, from thrilling rides to relaxing
                    moments by the lake. Experience joy, laughter, and unforgettable memories as
                    you explore everything our resort has to offer.
                </div>
                <div class="ticket-pricing d-flex gap-3 flex-wrap mb-5">
                    <div class="bg-light p-4 rounded shadow-sm text-center flex-fill">
                        <strong class="d-block fs-4">৳160</strong>
                        <span>Age 13+</span>
                    </div>
                    <div class="bg-light p-4 rounded shadow-sm text-center flex-fill">
                        <strong class="d-block fs-4">৳90</strong>
                        <span>Age 5–13</span>
                    </div>
                    <div class="bg-light p-4 rounded shadow-sm text-center flex-fill">
                        <strong class="d-block fs-4">Free</strong>
                        <span>Age 0–5</span>
                    </div>
                </div>
            </div>
            <!-- Rides -->
            <div class="row">
                @foreach ($rides as $ride)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="{{ asset('storage/' . $ride->image) }}" class="card-img-top" alt="{{ $ride->title }}"
                                style="border: 8px solid #fff;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $ride->title }}</h5>
                                <p class="card-text">{{ $ride->price }}</p>
                                <p class="card-text">{!! $ride->description !!}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Family Package -->
            {{-- <div class="title-box text-center pb-5">
                <div class="sub-title mb-4 fs-4 fw-bold">🎟️ Ticket (Family Package)</div>
                <div class="d-flex flex-wrap gap-4 justify-content-center mb-4">
                    <!-- Package 1 -->
                    <div class="family-block bg-white p-4 rounded shadow-sm text-center">
                        <h3 class="text-primary mb-3">৳385</h3>
                        <p class="mb-2">2 Adult Tickets</p>
                        <p class="mb-2">2 Child Tickets</p>
                        <p class="mb-2">1 Ride for Everyone</p>
                    </div>
                    <!-- Package 2 -->
                    <div class="family-block bg-white p-4 rounded shadow-sm text-center">
                        <h3 class="text-success mb-3">৳525</h3>
                        <p class="mb-2">3 Adult Tickets</p>
                        <p class="mb-2">2 Child Tickets</p>
                        <p class="mb-2">1 Ride for Everyone</p>
                    </div>
                    <!-- Package 3 -->
                    <div class="family-block bg-white p-4 rounded shadow-sm text-center">
                        <h3 class="text-danger mb-3">৳770</h3>
                        <p class="mb-2">4 Adult Tickets</p>
                        <p class="mb-2">4 Child Tickets</p>
                        <p class="mb-2">1 Ride for Everyone</p>
                    </div>
                </div>
                <!-- Extra Info -->
                <div class="bg-light p-4 rounded shadow-sm text-start">
                    <p class="mb-2">
                        ✅ Family package users can enjoy each ride at only <strong>৳35</strong>.
                    </p>
                    <p class="mb-2">
                        ✅ To avail ride offers, first buy a regular ticket, then pay <strong>৳35</strong> for the deluxe
                        boat
                        and <strong>৳70</strong> for the Sawan boat.
                    </p>
                    <p class="mb-0">
                        ✅ Free Ticket Calculation (Adults only):<br>
                        • Buy 5 tickets → Get 1 free<br>
                        • Buy 10 tickets → Get 2 free
                    </p>
                </div>
            </div> --}}

            <div class="title-box text-center pb-5">
                <div class="sub-title mb-4 fs-4 fw-bold">🎟️ Ticket (Family Package)</div>

                <div class="row g-4 justify-content-center">
                    <!-- Package 1 -->
                    <div class="col-md-4">
                        <div class="family-block bg-white p-4 rounded shadow-sm text-center h-100">
                            <h3 class="text-primary mb-3">৳380</h3>
                            <p class="mb-2">2 Adult Tickets</p>
                            <p class="mb-2">2 Child Tickets</p>
                            <p class="mb-2">1 Ride for Everyone</p>
                        </div>
                    </div>

                    <!-- Package 2 -->
                    <div class="col-md-4">
                        <div class="family-block bg-white p-4 rounded shadow-sm text-center h-100">
                            <h3 class="text-success mb-3">৳520</h3>
                            <p class="mb-2">3 Adult Tickets</p>
                            <p class="mb-2">2 Child Tickets</p>
                            <p class="mb-2">1 Ride for Everyone</p>
                        </div>
                    </div>

                    <!-- Package 3 -->
                    <div class="col-md-4">
                        <div class="family-block bg-white p-4 rounded shadow-sm text-center h-100">
                            <h3 class="text-danger mb-3">৳770</h3>
                            <p class="mb-2">4 Adult Tickets</p>
                            <p class="mb-2">4 Child Tickets</p>
                            <p class="mb-2">1 Ride for Everyone</p>
                        </div>
                    </div>
                </div>

                <!-- Extra Info -->
                <div class="bg-light p-4 rounded shadow-sm text-start mt-4">
                    <p class="mb-2">
                        ✅ Family package users can enjoy each ride at only <strong>৳40</strong> without boat rides.
                    </p>
                    <p class="mb-2">
                        {{-- ✅ To avail ride offers, first buy a regular ticket, then pay
                        <strong>৳35</strong> for the deluxe boat and <strong>৳70</strong> for the Sawan boat. --}}

                        ✅ First purchase an entry ticket and pay <strong>৳100</strong> — To enjoy all rides,  without boat rides.
                    </p>
                    <p class="mb-2">
                        ✅ First purchase an entry ticket and pay <strong>৳200</strong> — To enjoy all rides and boat-3.
                    </p>
                    <p class="mb-0">
                        ✅ Free Ticket Calculation (Adults only):<br>
                        • Buy 5 tickets → Get 1 free<br>
                        • Buy 10 tickets → Get 2 free
                    </p>
                </div>
            </div>




        </div>
    </section>
@endsection
