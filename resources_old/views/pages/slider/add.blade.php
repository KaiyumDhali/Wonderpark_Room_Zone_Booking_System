<x-default-layout>
    <h1>Manage Sliders</h1>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Upload Sliders -->
    <form action="{{ route('slider.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="col-12 col-md-6">
            <label for="image" class="form-label">Add Slider Images ( 1920px * 928px )</label>
            <input type="file" 
                   class="form-control mb-3" 
                   name="image[]" 
                   multiple="multiple" 
                   accept="image/png, image/jpeg, image/jpg" 
                   required>
        </div>
        <button type="submit" class="btn btn-success">Add New Sliders</button>
    </form>

    <hr>

    <!-- Display Sliders with Update and Delete Buttons in a Professional Layout -->
    <div class="row">
        @php
            $sl = 1;
        @endphp
        @foreach($sliders as $slider)
            <div class="col-md-4 col-sm-6 text-center mb-4">
                <div class="card" style="width:300px;height:auto;">
                    <img src="{{ asset('storage/' . $slider->slider_image) }}" alt="Slider Image" class="img-thumbnail card-img-top" style="width:auto;height:180px;">
                    <div class="card-body">
                        <h5 class="card-title">Slider: {{ $sl }}</h5>

                        <!-- Buttons Row -->
                    
                            <!-- Update Slider Form -->
                            <form action="{{ route('slider.update', $slider->id) }}" method="POST" enctype="multipart/form-data" class="me-2">
                                @csrf
                                @method('PUT')
                                <div class="mb-2">
                                    <input type="file" 
                                           class="form-control mb-2" 
                                           name="slider_image" 
                                           accept="image/png, image/jpeg, image/jpg" 
                                           required>
                                </div>
                                <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary btn-sm w-50 me-2">Update</button>
                                <button type="button" class="btn btn-danger btn-sm w-50" onclick="confirmDelete({{ $slider->id }})">Delete</button>
                                </div>
                            </form>

                            <!-- Delete Slider Form -->
                            <form action="{{ route('slider.destroy', $slider->id) }}" method="POST" class="w-50" id="deleteForm{{ $slider->id }}">
                                @csrf
                                @method('DELETE')
                               
                            </form>
                        
                    </div>
                </div>
            </div>
            @php
                $sl++;
            @endphp
        @endforeach
    </div>

</x-default-layout>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SweetAlert2 Script -->
<script>
    function confirmDelete(sliderId) {
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
                document.getElementById('deleteForm' + sliderId).submit();
            }
        });
    }
</script>
