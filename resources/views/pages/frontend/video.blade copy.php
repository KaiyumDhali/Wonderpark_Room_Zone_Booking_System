@extends('pages.frontend.layouts.app')

@section('content')

    <!-- Page Title -->
    {{-- <section class="page-title" style="background-image: url(assets_2/images/main-slider/page-title.jpg);"> --}}
    <section class="page-title" style="background-image: url(storage/{{$banner_image->banner_image}});">
        <div class="auto-container">
            <div class="text-center">
                <h1>Video</h1>
            </div>
        </div>
    </section>

    <section class="gallery-section-four light-bg mx-60 border-shape-top border-shape-bottom">
        <div class="auto-container">

            <!-- Gallery -->
            <div class="sortable-masonry">
                <div class="items-container row">

                    {{-- Show YouTube Videos --}}
                    @foreach ($video as $v)
                        @php
                            preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&]+)/', $v->link, $matches);
                            $videoId = $matches[1] ?? null;
                        @endphp

                        @if ($videoId)
                            <div class="gallery-block-four gallery-overlay masonry-item all cat-1 col-lg-4 col-md-6">
                                <div class="inner-box">
                                    <div class="image ratio ratio-16x9">
                                        <iframe src="https://www.youtube.com/embed/{{ $videoId }}"
                                            title="YouTube video player" allowfullscreen
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            referrerpolicy="strict-origin-when-cross-origin">
                                        </iframe>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>

@endsection
