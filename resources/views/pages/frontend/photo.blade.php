@extends('pages.frontend.layouts.app')

@section('content')
    <!-- Page Title -->
    {{-- <section class="page-title" style="background-image: url(assets_2/images/main-slider/page-title.jpg);"> --}}
    <section class="page-title" style="background-image: url(storage/{{$banner_image->banner_image}});">
        <div class="auto-container">
            <div class="text-center">
                <h1>Gallery</h1>
            </div>
        </div>
    </section>


    <!-- gallery section four -->
    <section class="gallery-section-four light-bg mx-60 border-shape-top border-shape-bottom">
        <div class="auto-container">

            <!--Filter-->
            <div class="filters">
                {{-- <ul class="filter-tabs filter-btns">
                    <li class="filter active" data-role="button" data-filter=".all">All</li>
                    <li class="filter" data-role="button" data-filter=".cat-1">Spa</li>
                    <li class="filter" data-role="button" data-filter=".cat-2">Rooms</li>
                    <li class="filter" data-role="button" data-filter=".cat-3">Activities</li>
                    <li class="filter" data-role="button" data-filter=".cat-4">Restaurant</li>
                    <li class="filter" data-role="button" data-filter=".cat-5">Pool</li>
                </ul> --}}
            </div>

            <!--Sortable Galery-->
            <div class="sortable-masonry">

                <div class="items-container row">


                    @foreach ($gallery as $item)
                        <div class="gallery-block-four gallery-overlay masonry-item all cat-1 col-lg-4 col-md-6">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="">
                                </div>
                                <div class="overlay-box">
                                    <div class="overlay-inner">
                                        <div class="content">
                                            <a href="{{ asset('storage/' . $item->image) }}" class="lightbox-image link"
                                                data-fancybox="gallery">
                                                <span class="icon fas fa-eye"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>
            </div>
        </div>
    </section>
@endsection
