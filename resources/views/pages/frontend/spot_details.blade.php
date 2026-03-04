@extends('pages.frontend.layouts.app')

@section('content')

<style>
    .image-wrapper {
        position: relative;
        overflow: hidden;
    }
    .image-wrapper img {
        transition: transform 0.3s ease;
    }
    .image-wrapper:hover img {
        transform: scale(1.05);
    }
    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .image-wrapper:hover .image-overlay {
        opacity: 1;
    }
    .image-overlay i {
        color: #fff;
        font-size: 2rem;
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">



    <!-- Page Title -->
    {{-- <section class="page-title" style="background-image: url(assets_2_2/images/main-slider/page-title.jpg);"> --}}
    <section class="page-title" style="background-image: url('{{ asset('storage/' . $banner_image->banner_image) }}');">
        <div class="auto-container">
            <div class="text-center">
                <h1>Spot Details</h1>
            </div>
        </div>
    </section>


    <!-- Sidebar Page Container -->
    <div class="sidebar-page-container light-bg mx-60 border-shape-top border-shape-bottom">
        <div class="auto-container">
            <div class="row">
                <!-- Blog Content -->
                <div class="col-lg-12 pr-lg-5">
                    <div class="news-block-two style-two blog-single-post">


                        <div class="inner-box">

                            <h3>{{ $spot->title }}</h3>

                            {!! $spot->description !!}

                            {{-- Related Spot Detail Images --}}
                            @if ($spot->spot_detail->count())
                                <div class="pt-5">
                                    <h4 class="pb-3">More from this Spot</h4>
                                    <div class="row">
                                        @foreach ($spot->spot_detail as $detail)
                                            <div class="col-md-4 col-sm-6 mb-4">
                                                <div class="news-block-two style-two">
                                                    <div class="inner-box">
                                                        <div class="image-wrapper">
                                                            <a href="{{ asset(Storage::url($detail->image_path)) }}"
                                                                data-fancybox="gallery">
                                                                <img src="{{ $detail->image_path ? asset(Storage::url($detail->image_path)) : asset('assets/images/resource/default.jpg') }}"
                                                                    alt="Detail Image"
                                                                    style="height: 220px; width: 100%; object-fit: cover;">
                                                                <div class="image-overlay">
                                                                    <i class="bi bi-eye"></i>
                                                                </div>
                                                            </a>
                                                        </div>
                                                        {{-- @if (!empty($detail->description))
                                                            <div class="lower-content pt-2 px-2">
                                                                <p class="text-muted small mb-0">{{ $detail->description }}</p>
                                                            </div>
                                                        @endif --}}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>


                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
