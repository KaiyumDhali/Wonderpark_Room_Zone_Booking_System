<x-default-layout>

    {{-- Validation Errors --}}
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

    <!-- Toolbar -->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center">
                <h3>Edit Spot Package</h3>
            </div>
        </div>
    </div>
    <!-- End Toolbar -->

    <!-- Content -->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container">

            <div class="card card-flush">
                <div class="card-body py-5">

                    <form action="{{ route('spot-packages.update', $package->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Spot -->
                        <!-- <div class="mb-5">
                            <label class="form-label required">Spot</label>
                            <select name="spot_id" class="form-select form-select-solid">
                                @foreach ($spots as $spot)
                                    <option value="{{ $spot->id }}"
                                        {{ old('spot_id', $package->spot_id) == $spot->id ? 'selected' : '' }}>
                                        {{ $spot->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div> -->
                            <div class="mb-5 col-md-6">
                            <label class="form-label required">Name</label>
                            <input type="text"
                                   name="name"
                                   class="form-control form-control-solid"
                                   value="{{ old('name',$package->name) }}"
                                   placeholder="Up to 100 pax">
                        </div>
                        <!-- Persons -->
                        <div class="mb-5">
                            <label class="form-label required">Persons</label>
                            <input type="number" name="persons" class="form-control form-control-solid"
                                value="{{ old('persons', $package->persons) }}">
                        </div>

                        <!-- Price -->
                        <div class="mb-5">
                            <label class="form-label required">Price (৳)</label>
                            <input type="number" step="0.01" name="price" class="form-control form-control-solid"
                                value="{{ old('price', $package->price) }}">
                        </div>

                        <!-- Status -->
                        <div class="mb-5">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select form-select-solid">
                                <option value="1" {{ $package->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $package->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('spot-packages.index') }}" class="btn btn-sm btn-light me-5">Cancel</a>

                            <button type="submit" class="btn btn-sm btn-success">
                                <span class="indicator-label">Update</span>
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

</x-default-layout>
