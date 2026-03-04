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
                <h3>Edit Blog</h3>
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
                    <form action="{{ route('blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-5">
                            <label for="title" class="form-label required">Title</label>
                            <input type="text" class="form-control form-control-solid" id="title" name="title"
                                value="{{ old('title', $blog->title) }}" />
                        </div>

                        <!-- Type -->
                        <div class="mb-5">
                            <label for="type" class="form-label">Type</label>
                            <input type="text" class="form-control form-control-solid" id="type" name="type"
                                value="{{ old('type', $blog->type) }}" />
                        </div>

                        <!-- Description -->
                        <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
                        <div class="mb-5">
                            <label for="description" class="form-label required">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $blog->description) }}</textarea>
                        </div>
                        <script>
                            CKEDITOR.replace('description', {
                                allowedContent: true
                            });
                        </script>

                        <!-- Image Upload -->
                        <div class="mb-5">
                            <label for="image" class="form-label">Image (1920px * 1280px)</label>
                            <input type="file" class="form-control form-control-solid p-2" id="image" name="image" />
                            @if ($blog->image)
                                <div class="mt-3">
                                    <img src="{{ asset(Storage::url($blog->image)) }}" height="80" width="120" class="rounded border shadow-sm" alt="Blog Image" />
                                    <a href="{{ route('blog.image_destroy', $blog->id) }}"
                                        onclick="return confirm('Are you sure you want to delete this image?')"
                                        class="btn btn-sm btn-danger ms-3">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Status -->
                        <div class="mb-5">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select form-select-solid" id="status" name="status">
                                <option disabled>Select Status</option>
                                <option value="1" {{ $blog->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $blog->status == 0 ? 'selected' : '' }}>Disable</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end mt-5">
                            <a href="{{ route('blog.index') }}" class="btn btn-sm btn-primary me-3">Cancel</a>
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
