<x-default-layout>



    <div class="col-xl-12 px-5">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h3>Create Spot</h3>
            </div>
            {{-- <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('about_us.index') }}" class="btn btn-sm btn-light-primary">
                    <i class="ki-duotone ki-arrow-left fs-2"></i>Go Back
                </a>
            </div> --}}
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container">
            <div class="card card-flush">
                {{-- <div class="card-header">
                    <h3 class="card-title">Add New About Us</h3>
                </div> --}}
                <div class="card-body py-5">
                    <form action="{{ route('spots.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                    <div class="row">
                        <div class="mb-5 col-md-6">
                            <label for="title" class="form-label required">Title</label>
                            <input type="text" class="form-control form-control-solid" id="title" name="title"
                                value="{{ old('title') }}" />
                            {{-- <textarea class="form-control form-control-solid" id="title" name="title" >{{ old('title') }}</textarea> --}}
                        </div>
                       <div class="mb-5 col-md-3">
                            <label for="title" class="form-label required">Area Size</label>
                            <input type="text" class="form-control form-control-solid" id="area_size" name="area_size"
                                value="{{ old('area_size') }}" />
                            {{-- <textarea class="form-control form-control-solid" id="area_size" name="area_size" >{{ old('title') }}</textarea> --}}
                        </div>
                       <div class="mb-5 col-md-3">
                            <label for="title" class="form-label required">Max Capacity</label>
                            <input type="text" class="form-control form-control-solid" id="max_capacity" name="max_capacity"
                                value="{{ old('max_capacity') }}" />
                            {{-- <textarea class="form-control form-control-solid" id="max_capacity" name="max_capacity" >{{ old('title') }}</textarea> --}}
                        </div>
                        <div class="mb-5 col-md-8">
                            <label for="description" class="form-label required">Description</label>
                            <textarea class="form-control form-control-solid" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="mb-5 col-md-4">
                            <label for="title" class="form-label required">Price</label>
                            <input type="text" class="form-control form-control-solid" id="price" name="price"
                                value="{{ old('price', '0.00') }}" />
                        </div>
<div class="mb-5 col-md-4">
    <label class="form-label">Facilities</label>

    <div id="facility-wrapper">
        <div class="d-flex mb-2">
            <input type="text" name="facilities[]" class="form-control me-2" placeholder="Enter facility">
            <button type="button" class="btn btn-danger remove-facility">X</button>
        </div>
    </div>

    <button type="button" class="btn btn-sm btn-primary mt-2" id="add-facility">
        + Add Facility
    </button>
</div>

                        <div class="mb-5  col-md-4">
                            <label for="image" class="form-label">Spot Thumbnail Image (1920px * 1080px)</label>
                            <input type="file" class="form-control  mb-2" id="spot_image"
                                name="image" />
                        </div>

                        <div class="mb-5 col-md-4">
                            <label class=" form-label">Spot Gallery Image (1920px * 1080px)</label>
                            <input type="file" class="form-control  mb-2" id="spot_gallery_image"
                                name="spot_gallery_image[]" multiple="multiple" accept="image/png, image/jpeg, image/jpg"
                                value="{{ old('spot_gallery_image') }}" />
                        </div>

                        {{-- <div class="col-12 col-md-6">
                            <div class="card-body pt-0 pb-2">
                                <div class="product fv-row fv-plugins-icon-container">
                                    <label class=" form-label">Room Thumbnail Image (520px * 360px)</label>
                                    <input type="file" class="form-control form-control-sm mb-2" id="thumbnail_image"
                                        name="thumbnail_image" accept="image/png, image/jpeg, image/jpg"
                                        value="{{ old('thumbnail_image') }}" />
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-md-6">
                            <div class="card-body pt-0 pb-2">
                                <div class="product fv-row fv-plugins-icon-container">
                                    <label class=" form-label">Room Gallery Image</label>
                                    <input type="file" class="form-control form-control-sm mb-2" id="image"
                                        name="image[]" multiple="multiple" accept="image/png, image/jpeg, image/jpg"
                                        value="{{ old('image') }}" />
                                </div>
                            </div>
                        </div> --}}

                        <div class="mb-5 col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select form-select-solid" id="status" name="status">
                                <option selected disabled>Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>
                    </div>
                        <div class="d-flex justify-content-end my-5">
                            <!--begin::Button-->
                            <a href="{{ route('spots.index') }}" id="kt_ecommerce_add_product_cancel"
                                class="btn btn-primary me-5" >Cancel</a>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_ecommerce_add_category_submit" class="btn btn-success" style="">
                                <span class="indicator-label">Save</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                            <!--end::Button-->
                        </div>

                        {{-- <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="ki-duotone ki-check-circle fs-2"></i> Save
                            </button>
                        </div> --}}


                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end::Content-->


</x-default-layout>
<script>
document.getElementById('add-facility').addEventListener('click', function () {
    let wrapper = document.getElementById('facility-wrapper');

    let div = document.createElement('div');
    div.classList.add('d-flex', 'mb-2');

    div.innerHTML = `
        <input type="text" name="facilities[]" class="form-control me-2" placeholder="Enter facility">
        <button type="button" class="btn btn-danger remove-facility">X</button>
    `;

    wrapper.appendChild(div);
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-facility')) {
        e.target.parentElement.remove();
    }
});
</script>
