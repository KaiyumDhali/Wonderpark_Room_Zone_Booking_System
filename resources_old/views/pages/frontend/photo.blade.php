@extends('pages.frontend.layouts.app')

@section('content')
    <style>
        .fas {
            line-height: inherit;
        }

        @media only screen and (max-width: 767px) {
            .nav-outer .mobile-nav-toggler {
                height: 20px;
            }
        }
    </style>

    <!-- Page Title -->
    <section class="page-title" style="background-image: url({{ asset('storage/' . $banner_image->banner_image) }});">
        <div class="auto-container">
            <div class="text-center">
                <h1>Gallery</h1>
            </div>
        </div>
    </section>


    <!-- gallery section four -->
    <section class="gallery-section-four light-bg mx-60 border-shape-top border-shape-bottom"
        style="background-color: #d3d3d3!important;">
        <div class="auto-container">

            <div class="title-box text-center pb-5">
                <div class="sub-title mb-4">Captured Moments</div>
                <div class="text">
                    Step into our gallery and explore the natural charm, peaceful landscapes, and joyful
                    moments that make Wonder Park & Eco Resort truly special. Each picture tells the story of relaxation,
                    togetherness, and harmony with nature.
                </div>
            </div>


            <!--Sortable Galery-->
            <div class="sortable-masonry">
                <div class="items-container row" id="gallery-container">
                    @foreach ($gallery as $key => $item)
                        <div
                            class="gallery-block-four gallery-overlay masonry-item all cat-1 col-lg-4 col-md-6 gallery-item {{ $key >= 12 ? 'd-none' : '' }}">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ asset('storage/' . $item->image) }}" style="border: 8px solid #fff;"
                                        alt="Gallery Image">
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

                <!-- Load More Button -->
                @if (count($gallery) > 12)
                    <div class="text-center mt-4">
                        <button id="loadMore" class="btn btn-primary">Load More</button>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let loadMoreBtn = document.getElementById("loadMore");
        let itemsToShow = 6;
        let $container = $('.items-container'); // jQuery for isotope/masonry

        loadMoreBtn?.addEventListener("click", function() {
            let hiddenItems = document.querySelectorAll(".gallery-item.d-none");

            for (let i = 0; i < itemsToShow; i++) {
                if (hiddenItems[i]) {
                    hiddenItems[i].classList.remove("d-none");
                }
            }

            // Refresh Isotope/Masonry layout
            $container.isotope('layout');

            // If no more hidden items, hide the button
            if (document.querySelectorAll(".gallery-item.d-none").length === 0) {
                loadMoreBtn.style.display = "none";
            }
        });
    });
</script>
