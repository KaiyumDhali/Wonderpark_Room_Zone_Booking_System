@extends('pages.frontend.layouts.app')

@section('content')
    <!-- Page Title -->
    <section class="page-title" style="background-image: url(storage/{{ $banner_image->banner_image }});">
        <div class="auto-container">
            <div class="text-center">
                <h1>Video</h1>
            </div>
        </div>
    </section>

    <section class="gallery-section-four light-bg mx-60 border-shape-top border-shape-bottom">
        <div class="auto-container">
            <div class="sortable-masonry">
                <div class="items-container row">
                    {{-- Show YouTube Videos --}}
                    @foreach ($video as $index => $v)
                        @php
                            preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&]+)/', $v->link, $matches);
                            $videoId = $matches[1] ?? null;
                        @endphp

                        @if ($videoId)
                            <div class="gallery-block-four gallery-overlay masonry-item all cat-1 col-lg-4 col-md-6">
                                <div class="inner-box">
                                    <div class="image ratio ratio-16x9">
                                        <div id="player-{{ $index }}"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <script>
        let players = [];

        function onYouTubeIframeAPIReady() {
            @foreach ($video as $index => $v)
                @php
                    preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&]+)/', $v->link, $matches);
                    $videoId = $matches[1] ?? null;
                @endphp

                @if ($videoId)
                    players[{{ $index }}] = new YT.Player('player-{{ $index }}', {
                        videoId: '{{ $videoId }}',
                        events: {
                            'onStateChange': function(event) {
                                if (event.data === YT.PlayerState.PLAYING) {
                                    players.forEach((p, i) => {
                                        if (i !== {{ $index }} && p && p.pauseVideo) {
                                            p.pauseVideo();
                                        }
                                    });
                                }
                            }
                        }
                    });
                @endif
            @endforeach
        }

        // Load YouTube IFrame API
        if (typeof YT === 'undefined' || typeof YT.Player === 'undefined') {
            const tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            const firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        } else {
            onYouTubeIframeAPIReady();
        }
    </script>
@endsection
