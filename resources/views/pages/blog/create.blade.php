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
                <h3>Create Blog</h3>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('blog.index') }}" class="btn btn-sm btn-light-primary">
                    <i class="bi bi-arrow-left fs-5"></i> Go Back
                </a>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container">
            <div class="card card-flush">
                <div class="card-body py-5">
                    <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-5">
                            <label for="title" class="form-label required">Title</label>
                            <input type="text" class="form-control form-control-solid" id="title" name="title"
                                value="{{ old('title') }}" />
                        </div>

                        <!-- Type -->
                        <div class="mb-5">
                            <label for="type" class="form-label">Type</label>
                            <input type="text" class="form-control form-control-solid" id="type" name="type"
                                value="{{ old('type') }}" />
                        </div>

                        <!-- Description -->
                        <div class="mb-5">
                            <label for="description" class="form-label required">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="6">{{ old('description') }}</textarea>
                        </div>

                        <!-- CKEditor Script -->
                        <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
                        <script>
                            CKEDITOR.replace('description', {
                                allowedContent: true
                            });
                        </script>

                        <!-- Image -->
                        <div class="mb-5">
                            <label for="image" class="form-label">Image (1920px * 1280px)<small></small></label>
                            <input type="file" class="form-control form-control-solid p-2" id="image" name="image" />
                        </div>

                        <!-- Status -->
                        <div class="mb-5">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select form-select-solid" id="status" name="status">
                                <option disabled selected>Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-end my-5">
                            <a href="{{ route('blog.index') }}" class="btn btn-sm btn-primary me-5">Cancel</a>
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
