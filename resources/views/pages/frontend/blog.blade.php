@extends('pages.frontend.layouts.app')

@section('content')
    <!-- Page Title -->
    {{-- <section class="page-title" style="background-image: url(assets_2_2/images/main-slider/page-title.jpg);"> --}}
    <section class="page-title" style="background-image: url(storage/{{ $banner_image->banner_image }});">
        <div class="auto-container">
            <div class="text-center">
                <h1>Blog</h1>
            </div>
        </div>
    </section>


    <!-- Blog section -->
    <section class="blog-section light-bg mx-60 border-shape-top border-shape-bottom">
        <div class="auto-container">
            <div class="row">

                @forelse($blogs as $blog)
                    <div class="col-lg-6">
                        <div class="inner-box">
                            <div class="image">
                                <a href="{{ route('frontend.blog_details', $blog->id) }}">
                                    <img src="{{ $blog->image ? asset(Storage::url($blog->image)) : asset('assets_2/images/resource/blog.jpg') }}"
                                        alt="{{ $blog->title }}">
                                </a>
                            </div>
                            <div class="lower-content">
                                <div class="post-meta">
                                    <div class="date">{{ $blog->created_at->format('d M, Y') }}</div>
                                    <ul>
                                        <li>By: Admin / Comments ({{ $blog->comments_count ?? 0 }})</li>
                                    </ul>
                                </div>
                                <h3>
                                    <a href="{{ route('frontend.blog_details', $blog->id) }}">
                                        {{ \Illuminate\Support\Str::limit($blog->title, 50) }}
                                    </a>
                                </h3>
                                <div class="blog-text">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($blog->description), 120) }}
                                </div>
                                <div class="link-btn">
                                    <a href="{{ route('frontend.blog_details', $blog->id) }}" class="view-all-btn">
                                        <span>Read More</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">No blogs available.</p>
                @endforelse


            </div>


            {{-- <ul class="styled-pagination ms-0 text-center">
                <li><a href="#" class="active">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#"><span class="flaticon-right-arrow"></span></a></li>
            </ul> --}}


        </div>
    </section>
@endsection
