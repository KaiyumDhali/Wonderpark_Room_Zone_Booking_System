@extends('pages.frontend.layouts.app')

@section('content')
    <!-- Page Title -->
    {{-- <section class="page-title" style="background-image: url(assets_2_2/images/main-slider/page-title.jpg);"> --}}
    <section class="page-title" style="background-image: url(storage/{{ $banner_image->banner_image }});">
        <div class="auto-container">
            <div class="text-center">
                <h1>Spots</h1>
            </div>
        </div>
    </section>

    <section class="spots-section light-bg mx-60 py-5 border-shape-top border-shape-bottom">
        <div class="auto-container">
            <div class="row">
                @foreach ($spots as $spot)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <a href="{{ route('frontend.spot_details', $spot->id) }}">
                                <img src="{{ asset('storage/' . $spot->image) }}" class="card-img-top"
                                    alt="{{ $spot->title }}">
                            </a>
                            <div class="card-body">

                                <a href="{{ route('frontend.spot_details', $spot->id) }}">
                                    <span>
                                        <h5 class="card-title">{{ $spot->title }}</h5>
                                    </span>
                                </a>

                                <p class="card-text">BDT {{ $spot->regular_price }}</p>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
