<x-default-layout>
    <h1>Manage Gallery</h1>

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

    <!-- Upload Gallery Images -->
    <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="col-12 col-md-6">
            <label for="image" class="form-label">Add Gallery Images (370px * 350px)</label>
            <input type="file" class="form-control mb-3" name="image[]" multiple="multiple"
                accept="image/png, image/jpeg, image/jpg" required>
        </div>
        <button type="submit" class="btn btn-success">Add New Images</button>
    </form>

    <hr>

    <!-- Display Gallery Images with Update and Delete Buttons -->
    <div class="row">
        @php
            $index = 1;
        @endphp
        @foreach ($gallerys as $image)
            <div class="col-md-4 col-sm-6 text-center mb-4">
                <div class="card" style="width:300px;height:auto;">
                    <img src=" {{ asset(Storage::url($image->image)) }}" alt="Gallery Image"
                        class="img-thumbnail card-img-top" style="width:auto;height:180px;">
                    <div class="card-body">
                        <h5 class="card-title">Image: {{ $index }}</h5>

                        <!-- Update Image Form -->
                        <form action="{{ route('gallery.update', $image->id) }}" method="POST"
                            enctype="multipart/form-data" class="mb-2">
                            @csrf
                            @method('PUT')
                            <!-- File Input Field -->
                            <div class="mb-2">
                                <input type="file" class="form-control mb-2" name="image"
                                    accept="image/png, image/jpeg, image/jpg" required>
                            </div>
                            <!-- Buttons Row -->
                            <div class="d-flex justify-content-between">
                                <!-- Update Button -->
                                <button type="submit" class="btn btn-primary btn-sm w-50 me-2">Update</button>

                                <!-- Delete Button -->
                                <button type="button" class="btn btn-danger btn-sm w-50"
                                    onclick="confirmDelete({{ $image->id }})">Delete</button>
                            </div>
                        </form>

                        <!-- Delete Form (Hidden, for JavaScript trigger) -->
                        <form action="{{ route('gallery.destroy', $image->id) }}" method="POST" class="d-none"
                            id="deleteForm{{ $image->id }}">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
            @php
                $index++;
            @endphp
        @endforeach
    </div>
</x-default-layout>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SweetAlert2 Script -->
<script>
    function confirmDelete(imageId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this action!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm' + imageId).submit();
            }
        });
    }
</script>
