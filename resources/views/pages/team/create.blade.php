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
                <h3>Create Team Member</h3>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('team.index') }}" class="btn btn-sm btn-light-primary">
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
                    <form action="{{ route('team.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Name -->
                        <div class="mb-5">
                            <label for="name" class="form-label required">Name</label>
                            <input type="text" class="form-control form-control-solid" id="name" name="name"
                                value="{{ old('name') }}" placeholder="Enter team member name" />
                        </div>

                        <!-- Designation -->
                        <div class="mb-5">
                            <label for="designation" class="form-label required">Designation</label>
                            <input type="text" class="form-control form-control-solid" id="designation" name="designation"
                                value="{{ old('designation') }}" placeholder="Enter designation" />
                        </div>

                        <!-- Start Date -->
                        <div class="mb-5">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control form-control-solid" id="start_date" name="start_date"
                                value="{{ old('start_date') }}" />
                        </div>

                        <!-- Social Links -->
                        <div class="row mb-5">
                            <div class="col-md-6 mb-3">
                                <label for="fb_url" class="form-label">Facebook URL</label>
                                <input type="url" class="form-control form-control-solid" id="fb_url" name="fb_url"
                                    value="{{ old('fb_url') }}" placeholder="https://facebook.com/username" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="twitter_url" class="form-label">Twitter URL</label>
                                <input type="url" class="form-control form-control-solid" id="twitter_url" name="twitter_url"
                                    value="{{ old('twitter_url') }}" placeholder="https://twitter.com/username" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="linkdin_url" class="form-label">LinkedIn URL</label>
                                <input type="url" class="form-control form-control-solid" id="linkdin_url" name="linkdin_url"
                                    value="{{ old('linkdin_url') }}" placeholder="https://linkedin.com/in/username" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="instagram_url" class="form-label">Instagram URL</label>
                                <input type="url" class="form-control form-control-solid" id="instagram_url" name="instagram_url"
                                    value="{{ old('instagram_url') }}" placeholder="https://instagram.com/username" />
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-5">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                        </div>

                        <!-- CKEditor -->
                        <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
                        <script>
                            CKEDITOR.replace('description', {
                                allowedContent: true
                            });
                        </script>

                        <!-- Image -->
                        <div class="mb-5">
                            <label for="image" class="form-label">Profile Image (270px * 324px)</label>
                            <input type="file" class="form-control form-control-solid p-2" id="image" name="image" />
                            <small class="text-muted">Recommended size: 400x400 px</small>
                        </div>

                        <!-- Order -->
                        <div class="mb-5">
                            <label for="order" class="form-label">Display Order</label>
                            <input type="number" class="form-control form-control-solid" id="order" name="order"
                                value="{{ old('order') }}" placeholder="Enter display order" />
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
                            <a href="{{ route('team.index') }}" class="btn btn-sm btn-primary me-5">Cancel</a>
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
