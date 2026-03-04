<x-default-layout>

    <div class="col-xl-12 px-5">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
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
            <div class="page-title d-flex flex-column justify-content-center">
                <h3 class="fw-bold">Create Spot Package</h3>
            </div>

            <div>
                <a href="{{ route('spot-packages.index') }}" class="btn btn-sm btn-light-primary">
                    <i class="ki-duotone ki-arrow-left fs-3"></i> Go Back
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

                    <form action="{{ route('spot-packages.store') }}" method="POST">
                        @csrf
                     <div class="row">
                        <!-- Spot -->
                        <!-- <div class="mb-5">
                            <label class="form-label required">Spot</label>
                            <select name="spot_id" class="form-select form-select-solid">
                                <option value="">Select Spot</option>
                                @foreach($spots as $spot)
                                    <option value="{{ $spot->id }}"
                                        {{ old('spot_id') == $spot->id ? 'selected' : '' }}>
                                        {{ $spot->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div> -->
                        
                        <!-- Persons -->
                        <div class="mb-5 col-md-6">
                            <label class="form-label required">Max Capacity</label>
                            <input type="number"
                                   name="persons"
                                   class="form-control form-control-solid"
                                   value="{{ old('persons') }}"
                                   placeholder="e.g. 100">
                        </div>

                        <!-- Price -->
                        <div class="mb-5 col-md-6">
                            <label class="form-label required">Price (৳)</label>
                            <input type="number"
                                   step="0.01"
                                   name="price"
                                   class="form-control form-control-solid"
                                   value="{{ old('price') }}"
                                   placeholder="e.g. 100000">
                        </div>

                        <!-- Status -->
                        <div class="mb-5 col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select form-select-solid">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('spot-packages.index') }}"
                               class="btn btn-sm btn-light me-5">Cancel</a>

                            <button type="submit" class="btn btn-sm btn-success">
                                <span class="indicator-label">Save</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                      </div>    
                    </form>

                </div>
            </div>

        </div>
    </div>
    <!--end::Content-->

</x-default-layout>
