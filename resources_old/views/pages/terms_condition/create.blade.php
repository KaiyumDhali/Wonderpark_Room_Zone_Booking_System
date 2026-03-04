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
                <h3>Create Terms & Conditions</h3>
                <span class="text-muted fs-7">
                    Add invoice terms for spot and additional services
                </span>
            </div>

            <a href="{{ route('terms-conditions.index') }}" class="btn btn-sm btn-light-primary">
                Back
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

                        {{-- Term Type --}}
                        <div class="mb-5">
                            <label class="form-label required">Term Type</label>
                            <select name="term_type" class="form-select form-select-solid">
                                <option value="">-- Select Type --</option>
                                <option value="spot" {{ old('term_type') == 'spot' ? 'selected' : '' }}>
                                    Spot
                                </option>
                                <option value="service" {{ old('term_type') == 'service' ? 'selected' : '' }}>
                                    Additional Service
                                </option>
                                {{-- <option value="common" {{ old('term_type') == 'common' ? 'selected' : '' }}>
                                    Common
                                </option> --}}
                            </select>
                        </div>

@php
        // echo '<pre>';
        // print_r($spots);
        // echo '</pre>';
        // die();
@endphp

                        {{-- Spot --}}
                        <div class="mb-5 d-none" id="spotField">
                            <label class="form-label">Select Spot</label>
                            <select name="spot_id" class="form-select form-select-solid">
                                <option value="">-- Select Spot --</option>
                                @foreach ($spots as $spot)
                                    <option value="{{ $spot->id }}"
                                        {{ old('spot_id') == $spot->id ? 'selected' : '' }}>
                                        {{ $spot->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Additional Service --}}
                        <div class="mb-5 d-none" id="serviceField">
                            <label class="form-label">Select Additional Service</label>
                            <select name="additional_service_id" class="form-select form-select-solid">
                                <option value="">-- Select Service --</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}"
                                        {{ old('additional_service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->title }}
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
                                <option value="1" selected>Active</option>
                                <option value="0">Disabled</option>
                            </select>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-end gap-3 mt-6">
                            <a href="{{ route('terms-conditions.index') }}" class="btn btn-sm btn-light-primary">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-sm btn-success">
                                <span class="indicator-label">
                                    Save Terms
                                </span>
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function toggleFields() {
                const type = document.querySelector('[name="term_type"]').value;

                document.getElementById('spotField').classList.add('d-none');
                document.getElementById('serviceField').classList.add('d-none');

                if (type === 'spot') {
                    document.getElementById('spotField').classList.remove('d-none');
                }

                if (type === 'service') {
                    document.getElementById('serviceField').classList.remove('d-none');
                }
            }

            document.querySelector('[name="term_type"]').addEventListener('change', toggleFields);
            window.addEventListener('load', toggleFields);
        </script>
    @endpush


</x-default-layout>
