<x-default-layout>
    <h1>Manage YouTube Gallery</h1>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Upload YouTube Video Link -->
    <form action="{{ route('video.store') }}" method="POST">
        @csrf
        <div class="col-12 col-md-6">
            <label class="form-label">Add YouTube Video Link</label>
            <input type="text" class="form-control mb-3" name="link"
                placeholder="https://www.youtube.com/watch?v=VIDEO_ID" required>
        </div>
        <button type="submit" class="btn btn-success">Add New Video</button>
    </form>

    <hr>

    <!-- Video Gallery Masonry Grid -->
    <div class="row masonry-gallery">
        @foreach ($videos as $video)
            @php
                preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&]+)/', $video->link, $matches);
                $videoId = $matches[1] ?? null;
            @endphp

            @if ($videoId)
                <div class="gallery-block-four gallery-overlay masonry-item all cat-1 col-lg-4 col-md-6 mb-4">
                    <div class="inner-box position-relative">
                        <div class="image ratio ratio-16x9">
                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}" title="YouTube video player"
                                allowfullscreen
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin">
                            </iframe>
                        </div>

                        <div class="p-2 bg-light">
                            <form action="{{ route('video.destroy', $video->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100">Delete</button>
                            </form>

                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</x-default-layout>
