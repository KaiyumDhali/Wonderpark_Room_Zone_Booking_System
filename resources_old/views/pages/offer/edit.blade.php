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
                <h3>Edit Offer</h3>
            </div>
            {{-- <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('offers.index') }}" class="btn btn-sm btn-light-primary">
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
                    <h3 class="card-title">Update About Us</h3>
                </div> --}}
                <div class="card-body py-5">
                    <form action="{{ route('offers.update', $offers->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-5">
                            <label for="title" class="form-label required">Title</label>
                            <input type="text" class="form-control form-control-solid" id="title" name="title"
                                value="{{ old('title', $offers->title) }}" />
                            {{-- <textarea class="form-control form-control-solid" id="title" name="title" rows="2">{{ old('title', $offers->title) }}</textarea> --}}
                        </div>



                        <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>

                        <div class="mb-5">
                            <label for="description" class="form-label required">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $offers->description) }}</textarea>
                        </div>

                        <script>
                            CKEDITOR.replace('description', {
                                allowedContent: true // Allow all HTML
                            });
                        </script>

                        <div class="mb-5">
                            <label for="title" class="form-label required">Price</label>
                            <input type="text" class="form-control form-control-solid" id="price" name="price"
                                value="{{ old('price', $offers->price) }}" />
                        </div>

                        <div class="mb-5">
                            <label for="image" class="form-label">Image (1000px * 667px)</label>
                            <input type="file" class="form-control form-control-solid p-2" id="image"
                                name="image" />
                            @if ($offers->image)
                                <div class="mt-2">
                                    <img src="{{ asset(Storage::url($offers->image)) }}" height="75" width="75"
                                        alt="Offer Image" />

                                    @if ($offers->image != null)
                                        <a class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete ?')"
                                            href="{{ route('offer.image_destroy', $offers->id) }}"><i
                                                class="fa fa-trash"></i></a>
                                    @endif

                                </div>
                            @endif
                        </div>

                        <div class="mb-5">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select form-select-solid" id="status" name="status">
                                <option disabled>Select Status</option>
                                <option value="1" {{ $offers->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $offers->status == 0 ? 'selected' : '' }}>Disable</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end my-5">
                            <!--begin::Button-->
                            <a href="{{ route('offers.index') }}" id="kt_ecommerce_add_product_cancel"
                                class="btn btn-sm btn-primary me-5">Cancel</a>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_ecommerce_add_category_submit" class="btn btn-sm btn-success">
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
