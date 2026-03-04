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
                <h3>Create Spot Booking</h3>
                <span class="text-muted fs-7">Add new spot package booking</span>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('spot-bookings.index') }}" class="btn btn-sm btn-light">
                    Back
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container">

            <form action="{{ route('spot-bookings.store') }}" method="POST">
                @csrf

                <div class="card card-flush">

                    <div class="card-body">

                        <div class="row g-6">

                            {{-- Spot --}}
                            <div class="col-md-6">
                                <label class="required form-label">Spot</label>
                                <select name="spot_id" class="form-select form-select-solid" required>
                                    <option value="">Select Spot</option>
                                    @foreach ($spots as $spot)
                                        <option value="{{ $spot->id }}"
                                            {{ old('spot_id') == $spot->id ? 'selected' : '' }}>
                                            {{ $spot->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Package --}}
                            <div class="col-md-6">
                                <label class="required form-label">Package</label>
                                <select name="package_id" class="form-select form-select-solid" required>
                                    <option value="">Select Package</option>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}"
                                            {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                            {{ $package->title ?? 'Package #' . $package->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Booking Date --}}
                            <div class="col-md-4">
                                <label class="required form-label">Booking Date</label>
                                <input type="date" name="booking_date" class="form-control form-control-solid"
                                    value="{{ old('booking_date') }}" required>
                            </div>

                            {{-- Total Persons --}}
                            <div class="col-md-4">
                                <label class="required form-label">Total Persons</label>
                                <input type="number" name="total_persons" min="1"
                                    class="form-control form-control-solid" value="{{ old('total_persons') }}"
                                    required>
                            </div>

                            {{-- Total Price --}}
                            <div class="col-md-4">
                                <label class="required form-label">Total Price (৳)</label>
                                <input type="number" name="total_price" step="0.01"
                                    class="form-control form-control-solid" value="{{ old('total_price') }}" required>
                            </div>

                            {{-- Customer Name --}}
                            <div class="col-md-6">
                                <label class="required form-label">Customer Name</label>
                                <input type="text" name="customer_name" class="form-control form-control-solid"
                                    value="{{ old('customer_name') }}" required>
                            </div>

                            {{-- Customer Mobile --}}
                            <div class="col-md-6">
                                <label class="required form-label">Customer Mobile</label>
                                <input type="text" name="customer_mobile" class="form-control form-control-solid"
                                    value="{{ old('customer_mobile') }}" required>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select form-select-solid">
                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Confirmed
                                    </option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Pending
                                    </option>
                                </select>
                            </div>

                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="card-footer d-flex justify-content-end gap-3">
                        <a href="{{ route('spot-bookings.index') }}" class="btn btn-light">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Save Booking
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>





</x-default-layout>
