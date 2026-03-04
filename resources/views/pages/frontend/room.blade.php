@extends('pages.frontend.layouts.app')

@section('content')
    <!-- Page Title -->
    {{-- <section class="page-title" style="background-image: url(assets_2/images/main-slider/page-title-6.jpg);"> --}}
    <section class="page-title" style="background-image: url(storage/{{ $banner_image->banner_image }});">
        <div class="auto-container">
            <div class="text-center">
                <h1>Rooms</h1>
            </div>
        </div>
    </section>

    <!-- gallery section four -->
    <section class="room-grid-section pb-3 light-bg mx-60 border-shape-top border-shape-bottom">
        <div class="auto-container">

            <!-- Filter -->
            <div class="filters style-two">
                <ul class="filter-tabs filter-btns">
                    <li class="filter active" data-role="button" data-filter=".all">All</li>
                    @foreach ($rooms->pluck('room_type')->unique('id') as $room_type)
                        @if ($room_type)
                            <li class="filter" data-role="button" data-filter=".cat-{{ $room_type->id }}">
                                {{ $room_type->type_name }}
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>

            <!-- Sortable Gallery -->
            <div class="sortable-masonry">
                <div class="items-container row">
                    @forelse($rooms as $room)
                        <div class="col-lg-6 room-block-two masonry-item all cat-{{ $room->roomtype_id }}">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ asset('storage/' . $room->thumbnail_image) }}"
                                        alt="{{ $room->room_type->type_name }}">
                                        {{-- ৳ --}}
                                    <div class="text px-1">BDT {{ number_format($room->price_per_night, 2) }} / Night</div>
                                </div>
                                <h3>
                                    <a href="">
                                        {{ $room->room_type->type_name }} - {{ $room->room_number }}
                                    </a>
                                </h3>
                                <div class="icon-list">
                                    <ul>
                                        <li><i class="flaticon-bed"></i>{{ $room->capacity }} Guests</li>
                                        {{-- <li><i class="flaticon-bath-1"></i>{{ $room->floor }} Floor</li> --}}
                                    </ul>
                                </div>
                                <div class="text-two">
                                    {{ Str::limit($room->description, 120, '...') }}
                                </div>
                                <div class="link-btn">
                                    <a href="{{ route('frontend.room_details', $room->id) }}"
                                        class="theme-btn btn-style-one btn-md">
                                        <span>room details</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center">No rooms available at the moment.</p>
                    @endforelse
                </div>

            </div>
        </div>
    </section>

    
@endsection
