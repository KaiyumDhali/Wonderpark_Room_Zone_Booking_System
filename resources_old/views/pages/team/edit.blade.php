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
                <h3>Edit Team Member</h3>
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
                    <form action="{{ route('team.update', $team->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-5">
                            <label for="name" class="form-label required">Name</label>
                            <input type="text" class="form-control form-control-solid" id="name" name="name"
                                value="{{ old('name', $team->name) }}" />
                        </div>

                        <!-- Designation -->
                        <div class="mb-5">
                            <label for="designation" class="form-label required">Designation</label>
                            <input type="text" class="form-control form-control-solid" id="designation" name="designation"
                                value="{{ old('designation', $team->designation) }}" />
                        </div>

                        <!-- Start Date -->
                        <div class="mb-5">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control form-control-solid" id="start_date" name="start_date"
                                value="{{ old('start_date', $team->start_date) }}" />
                        </div>

                        <!-- Social Links -->
                        <div class="row mb-5">
                            <div class="col-md-6 mb-3">
                                <label for="fb_url" class="form-label">Facebook URL</label>
                                <input type="url" class="form-control form-control-solid" id="fb_url" name="fb_url"
                                    value="{{ old('fb_url', $team->fb_url) }}" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="twitter_url" class="form-label">Twitter URL</label>
                                <input type="url" class="form-control form-control-solid" id="twitter_url" name="twitter_url"
                                    value="{{ old('twitter_url', $team->twitter_url) }}" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="linkdin_url" class="form-label">LinkedIn URL</label>
                                <input type="url" class="form-control form-control-solid" id="linkdin_url" name="linkdin_url"
                                    value="{{ old('linkdin_url', $team->linkdin_url) }}" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="instagram_url" class="form-label">Instagram URL</label>
                                <input type="url" class="form-control form-control-solid" id="instagram_url" name="instagram_url"
                                    value="{{ old('instagram_url', $team->instagram_url) }}" />
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-5">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $team->description) }}</textarea>
                        </div>

                        <!-- CKEditor -->
                        <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
                        <script>
                            CKEDITOR.replace('description', {
                                allowedContent: true
                            });
                        </script>

                        <!-- Image Upload -->
                        <div class="mb-5">
                            <label for="image" class="form-label">Profile Image (270px * 324px)</label>
                            <input type="file" class="form-control form-control-solid p-2" id="image" name="image" />
                            @if ($team->image)
                                <div class="mt-3 d-flex align-items-center">
                                    <img src="{{ asset(Storage::url($team->image)) }}" height="80" width="80"
                                        class="rounded border shadow-sm" alt="Profile Image" />
                                    <a href="{{ route('team.image_destroy', $team->id) }}"
                                        onclick="return confirm('Are you sure you want to delete this image?')"
                                        class="btn btn-sm btn-danger ms-3">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Order -->
                        <div class="mb-5">
                            <label for="order" class="form-label">Display Order</label>
                            <input type="number" class="form-control form-control-solid" id="order" name="order"
                                value="{{ old('order', $team->order) }}" />
                        </div>

                        <!-- Status -->
                        <div class="mb-5">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select form-select-solid" id="status" name="status">
                                <option disabled>Select Status</option>
                                <option value="1" {{ $team->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $team->status == 0 ? 'selected' : '' }}>Disable</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end mt-5">
                            <a href="{{ route('team.index') }}" class="btn btn-sm btn-primary me-3">Cancel</a>
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
