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

            <div class="title-box text-center pb-5">
                <div class="sub-title mb-4">Entry Ticket</div>
                <div class="text mb-4">
                    Your adventure at Wonder Park begins with a simple step—getting your entry ticket.
                    With this pass, you unlock a world of excitement, from thrilling rides to relaxing
                    moments by the lake. Experience joy, laughter, and unforgettable memories as
                    you explore everything our resort has to offer.
                </div>

                <div class="ticket-pricing d-inline-block text-start bg-light p-4 rounded shadow-sm">
                    <ul class="list-unstyled mb-0">
                        <li><strong>৳140</strong> &nbsp; Age 13+</li>
                        <li><strong>৳90</strong> &nbsp; Age 5–13</li>
                        <li><strong>Free</strong> &nbsp; Age 0–5</li>
                    </ul>
                </div>
            </div>

            <div class="row">
                @foreach ($rides as $ride)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="{{ asset('storage/' . $ride->image) }}" class="card-img-top"
                                alt="{{ $ride->title }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $ride->title }}</h5>
                                <p class="card-text">{{ $ride->price }}</p>
                                <p class="card-text">{!! $ride->description !!}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>



        </div>
    </section>
@endsection
