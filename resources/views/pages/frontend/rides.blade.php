@extends('pages.frontend.layouts.app')

@section('content')
    <!-- Page Title -->
    <section class="page-title" style="background-image: url(storage/{{ $banner_image->banner_image }});">
        <div class="auto-container">
            <div class="text-center">
                <h1>Rides</h1>
            </div>
        </div>
    </section>

    <!-- Rides Section -->
    <section class="rides-section light-bg mx-60 py-5 border-shape-top border-shape-bottom">
        <div class="auto-container">
            <div class="row">
                @foreach ($rides as $ride)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="{{ asset('storage/' . $ride->image) }}" class="card-img-top" alt="{{ $ride->title }}">
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
