@extends('pages.frontend.layouts.app')

@section('content')
    <style>
        .news-block-two h3 {
            font-size: 25px;
            color: #0e2a4e;
            font-weight: 800;
            font-family: "Playfair Display";
            padding-top: 5px;
            padding-bottom: 5px;
        }
        .sidebar-page-container {
            padding-top: 115px;
            position: relative;
            padding-bottom: 0px;
        }
    </style>

    <!-- Page Title -->
    {{-- <section class="page-title" style="background-image: url(assets_2_2/images/main-slider/page-title.jpg);"> --}}
    <section class="page-title" style="background-image: url('{{ asset('storage/' . $banner_image->banner_image) }}');">
        <div class="auto-container">
            <div class="text-center">
                <h1>Blog Details</h1>
            </div>
        </div>
    </section>


    <!-- Sidebar Page Container -->
    <div class="sidebar-page-container light-bg mx-60 border-shape-top border-shape-bottom" style="background-color: #d3d3d3!important;">
        <div class="auto-container">
            <div class="row">
                <!-- Blog Content -->
                <div class="col-lg-12 pr-lg-5">
                    <div class="news-block-two style-two blog-single-post">


                        <div class="inner-box">
                            <div class="image">
                                <a href="#">
                                    <img src="{{ $blog->image ? asset(Storage::url($blog->image)) : asset('assets/images/resource/default.jpg') }}" style="border: 8px solid #fff;"
                                        alt="{{ $blog->title }}">
                                </a>
                            </div>
                            <div class="lower-content" style="padding-left: 20px; padding-right: 20px;">
                                <div class="post-meta">
                                    <div class="date">{{ $blog->created_at->format('d M, Y') }}</div>
                                    <ul>
                                        <li>By: Admin / {{ $blog->type ?? 'General' }}</li>
                                    </ul>
                                </div>
                                <h3>{{ $blog->title }}</h3>
                                <div class="blog-text">{!! $blog->description !!}</div>
                            </div>
                        </div>


                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
