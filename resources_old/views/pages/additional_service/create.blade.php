<x-default-layout>

    {{-- Alerts --}}
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
                <h3>Create Additional Service</h3>
                <span class="text-muted fs-7">Add extra service for spot booking</span>
            </div>

            <a href="{{ route('additional-services.index') }}" class="btn btn-sm btn-light-primary">
                Back
            </a>
        </div>
    </div>

    <!-- Content -->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container">

            <div class="card card-flush">
                <div class="card-body py-5">

                    <form action="{{ route('additional-services.store') }}" method="POST">
                        @csrf

                        {{-- Title --}}
                        <div class="mb-5">
                            <label class="form-label required">Service Title</label>
                            <input type="text" name="title" class="form-control form-control-solid"
                                value="{{ old('title') }}" placeholder="e.g. BBQ, Photography">
                        </div>

                        {{-- Description --}}
                        <div class="mb-5">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-control form-control-solid"
                                placeholder="Optional service details">{{ old('description') }}</textarea>
                        </div>

                        {{-- Price --}}
                        <div class="mb-5">
                            <label class="form-label required">Price (৳)</label>
                            <input type="number" step="0.01" name="price" class="form-control form-control-solid"
                                value="{{ old('price', '0.00') }}">
                        </div>

                        {{-- Status --}}
                        <div class="mb-5">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select form-select-solid">
                                <option value="1" selected>Active</option>
                                <option value="0">Disabled</option>
                            </select>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-end gap-3 mt-6">
                            <a href="{{ route('additional-services.index') }}" class="btn btn-sm btn-light-primary">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-sm btn-success">
                                <span class="indicator-label">Save Service</span>
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</x-default-layout>
