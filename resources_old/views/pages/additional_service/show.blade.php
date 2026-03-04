<x-default-layout>
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">

            <!-- Toolbar -->
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                            Additional Service Details
                        </h1>
                    </div>
                </div>
            </div>
            <!--end::Toolbar-->

            <!-- Content -->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container">

                    <div class="card card-flush">
                        <div class="card-body py-5">

                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-hover table-bordered">
                                        <tbody>
                                            <tr>
                                                <th class="w-25"><strong>Title:</strong></th>
                                                <td>{{ $additionalService->title }}</td>
                                            </tr>

                                            <tr>
                                                <th><strong>Description:</strong></th>
                                                <td>{{ $additionalService->description ?? '-' }}</td>
                                            </tr>

                                            <tr>
                                                <th><strong>Price:</strong></th>
                                                <td>৳ {{ number_format($additionalService->price, 2) }}</td>
                                            </tr>

                                            <tr>
                                                <th><strong>Status:</strong></th>
                                                <td>
                                                    <span
                                                        class="badge badge-light-{{ $additionalService->status ? 'success' : 'secondary' }}">
                                                        {{ $additionalService->status ? 'Active' : 'Disable' }}
                                                    </span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th><strong>Created At:</strong></th>
                                                <td>{{ $additionalService->created_at->format('d M Y h:i A') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    {{-- Future use (icon / image / note) --}}
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle"></i>
                                        This service can be added during spot booking.
                                    </div>
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="d-flex justify-content-end mt-5">

                                <a href="{{ route('additional-services.index') }}" class="btn btn-sm btn-primary me-2">
                                    Back
                                </a>

                                <a href="{{ route('additional-services.edit', $additionalService->id) }}"
                                    class="btn btn-sm btn-info me-2">
                                    Edit
                                </a>

                                <form action="{{ route('additional-services.destroy', $additionalService->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger px-4"
                                        onclick="return confirm('Are you sure you want to delete this service?')">
                                        Delete
                                    </button>
                                </form>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <!--end::Content-->

        </div>
    </div>
</x-default-layout>
