<x-default-layout>

    <!-- Toolbar -->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center">
                <h3>View Terms & Conditions</h3>
                <span class="text-muted fs-7">
                    Detailed terms information
                </span>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('terms-conditions.index') }}"
                   class="btn btn-sm btn-light-primary">
                    Back
                </a>

                <a href="{{ route('terms-conditions.edit', $term->id) }}"
                   class="btn btn-sm btn-primary">
                    Edit
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container">

            <div class="card card-flush">
                <div class="card-body py-5">

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="fw-bold text-muted">Term Type</label>
                            <div class="mt-1">
                                @if ($term->term_type === 'spot')
                                    <span class="badge badge-light-primary">Spot</span>
                                @elseif ($term->term_type === 'service')
                                    <span class="badge badge-light-info">Service</span>
                                @else
                                    <span class="badge badge-light-secondary">Common</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold text-muted">Status</label>
                            <div class="mt-1">
                                @if ($term->is_active)
                                    <span class="badge badge-light-success">Active</span>
                                @else
                                    <span class="badge badge-light-danger">Disabled</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="fw-bold text-muted">Term Title</label>
                        <div class="fs-6 mt-1">
                            {{ $term->term_title ?? '—' }}
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="fw-bold text-muted">Term Description</label>
                        <div class="fs-6 mt-1">
                            {!! nl2br(e($term->term_description)) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="fw-bold text-muted">Sort Order</label>
                            <div class="fs-6 mt-1">
                                {{ $term->sort_order }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold text-muted">Created At</label>
                            <div class="fs-6 mt-1">
                                {{ \Carbon\Carbon::parse($term->created_at)->format('d M Y, h:i A') }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

</x-default-layout>
