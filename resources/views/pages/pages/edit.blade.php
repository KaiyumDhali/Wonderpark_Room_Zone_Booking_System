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
                <h3>Edit Page</h3>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container">
            <div class="card card-flush">
                <div class="card-body py-5">
                    <form action="{{ route('pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Page Name (Readonly) -->
                        <div class="mb-5">
                            <label for="name" class="form-label required">Page Name</label>
                            <input type="text" class="form-control form-control-solid" id="name" name="name" readonly
                                value="{{ old('name', $page->name) }}" />
                        </div>

                        <!-- Banner Image -->
                        <div class="mb-5">
                            <label for="banner_image" class="form-label">Banner Image (1920px * 660px)</label>
                            <input type="file" class="form-control form-control-solid p-2" id="banner_image"
                                name="banner_image" />
                            @if ($page->banner_image)
                                <div class="mt-2">
                                    <img src="{{ asset(Storage::url($page->banner_image)) }}" height="75" width="75" alt="Banner Image" />
                                    <a class="btn btn-sm btn-danger mt-2"
                                        onclick="return confirm('Are you sure you want to delete?')"
                                        href="{{ route('pages.banner_destroy', $page->id) }}">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Status -->
                        <div class="mb-5">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select form-select-solid" id="status" name="status">
                                <option disabled>Select Status</option>
                                <option value="1" {{ $page->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $page->status == 0 ? 'selected' : '' }}>Disable</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end my-5">
                            <a href="{{ route('pages.index') }}" class="btn btn-sm btn-primary me-5">Cancel</a>

                            <button type="submit" class="btn btn-sm btn-success">
                                <span class="indicator-label">Save</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end::Content-->
</x-default-layout>
