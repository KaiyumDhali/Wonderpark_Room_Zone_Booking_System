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
    <div id="kt_app_toolbar_container"
         class="container-fluid d-flex justify-content-between align-items-center px-5">

        <div class="page-title d-flex flex-column justify-content-center">
            <h3 class="fw-bold mb-0">Create Terms & Conditions</h3>
            <span class="text-muted fs-7">
                Add invoice terms for spot and additional services
            </span>
        </div>

        <a href="{{ route('terms-conditions.index') }}" 
           class="btn btn-sm btn-light-primary">
           <i class="bi bi-arrow-left me-1"></i> Back
        </a>

    </div>
</div>



    <!-- Content -->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container">

            <div class="card card-flush">
                <div class="card-body py-5">

                    <form action="{{ route('terms-conditions.store') }}" method="POST">
                        @csrf

                        {{-- Term selectors --}}
                        <div class="d-flex">
                            <div class="mb-5 col-md-6 pe-2">
                                <label class="form-label required">Term For</label>
                                <select name="term_type1" id="termFor" class="form-select form-select-solid">
                                    <option value="">-- Select Type --</option>
                                    <option value="spot" {{ old('term_type1') == 'spot' ? 'selected' : '' }}>
                                        Zone
                                    </option>
                                    <option value="service" {{ old('term_type1') == 'service' ? 'selected' : '' }}>
                                        Additional Service
                                    </option>
                                    <option value="room" {{ old('term_type1') == 'room' ? 'selected' : '' }}>
                                        Room
                                    </option>
                                    <option value="common" {{ old('term_type1') == 'common' ? 'selected' : '' }}>
                                        Common
                                    </option>
                                </select>
                            </div>

                            <div class="mb-5 col-md-6 ps-2">
                                <label class="form-label required">Term Type</label>
                                <select name="term_type" id="termType" class="form-select form-select-solid">
                                    <option value="">-- Select Type --</option>
                                    <option value="included" {{ old('term_type') == 'included' ? 'selected' : '' }}>
                                        Included
                                    </option>
                                    <option value="common" {{ old('term_type') == 'common' ? 'selected' : '' }}>
                                        Common
                                    </option>
                                </select>
                            </div>
                        </div>

                        {{-- Spot --}}
                        <div class="mb-5 d-none" id="spotField">
                            <label class="form-label required">Select Zone</label>
                            <select name="spot_id" id="spotSelect" class="form-select form-select-solid">
                                <option value="">-- Select Zone --</option>
                                @foreach ($spots as $spot)
                                    <option value="{{ $spot->id }}" {{ old('spot_id') == $spot->id ? 'selected' : '' }}>
                                        {{ $spot->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Additional Service --}}
                        <div class="mb-5 d-none" id="serviceField">
                            <label class="form-label required">Select Additional Service</label>
                            <select name="additional_service_id" id="serviceSelect" class="form-select form-select-solid">
                                <option value="">-- Select Service --</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}" {{ old('additional_service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-5 d-none" id="roomField">
                            <label class="form-label required">Select Room</label>
                            <select name="room_id" id="roomSelect" class="form-select form-select-solid">
                                <option value="">-- Select Room --</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->room_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Term Title --}}
                        <div class="mb-5">
                            <label class="form-label">Term Title</label>
                            <input type="text" name="term_title" class="form-control form-control-solid"
                                value="{{ old('term_title') }}" placeholder="e.g. Entry Validity">
                        </div>

                        {{-- Term Description --}}
                        <div class="mb-5">
                            <label class="form-label required">Term Description</label>
                            <textarea name="term_description" rows="4" class="form-control form-control-solid"
                                placeholder="Write detailed terms & conditions here">{{ old('term_description') }}</textarea>
                        </div>

                        {{-- Sort Order --}}
                        <div class="mb-5">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control form-control-solid"
                                value="{{ old('sort_order', 0) }}">
                        </div>

                        {{-- Status --}}
                        <div class="mb-5">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select form-select-solid">
                                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-end gap-3 mt-6">
                            <a href="{{ route('terms-conditions.index') }}" class="btn btn-sm btn-light-primary">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-sm btn-success">
                                <span class="indicator-label">Save Terms</span>
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
    function clearHiddenFields() {
        if (document.getElementById('spotField').classList.contains('d-none')) {
            document.getElementById('spotSelect').value = '';
        }
        if (document.getElementById('serviceField').classList.contains('d-none')) {
            document.getElementById('serviceSelect').value = '';
        }
        if (document.getElementById('roomField').classList.contains('d-none')) {
            document.getElementById('roomSelect').value = '';
        }
    }

    function toggleFields() {

        const termFor = document.getElementById('termFor').value;
        const termType = document.getElementById('termType').value;

        const spotField = document.getElementById('spotField');
        const serviceField = document.getElementById('serviceField');
        const roomField = document.getElementById('roomField');

        // hide all first
        spotField.classList.add('d-none');
        serviceField.classList.add('d-none');
        roomField.classList.add('d-none');

        // If term type is COMMON → hide everything
        if (termType === 'common') {
            clearHiddenFields();
            return;
        }

        // If INCLUDED → show based on termFor
        if (termType === 'included') {

            if (termFor === 'spot') {
                spotField.classList.remove('d-none');
            } 
            else if (termFor === 'service') {
                serviceField.classList.remove('d-none');
            } 
            else if (termFor === 'room') {
                roomField.classList.remove('d-none');
            }
        }

        clearHiddenFields();
    }

    document.getElementById('termFor').addEventListener('change', toggleFields);
    document.getElementById('termType').addEventListener('change', toggleFields);
    window.addEventListener('DOMContentLoaded', toggleFields);
</script>

    @endpush

</x-default-layout>
