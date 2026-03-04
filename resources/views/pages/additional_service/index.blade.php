<x-default-layout>

    <style>
        .dataTables_filter {
            float: right;
        }

        .dataTables_buttons {
            float: left;
        }

        .bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
    </style>

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

        @if (session('message'))
            <div class="alert alert-{{ session('alert-type', 'success') }}">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <!-- Toolbar -->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center">
                <h3>Additional Services</h3>
                <span class="text-muted fs-7">List of all additional services</span>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('additional-services.create') }}" class="btn btn-sm btn-primary">
                    Add Service
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container">

            <div class="card card-flush">

                <!-- Card Header -->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                <i class="bi bi-search fs-4"></i>
                            </span>
                            <input type="text" class="form-control form-control-solid w-250px ps-14 p-2"
                                placeholder="Search service">
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="card-body pt-0">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                        <thead>
    <tr class="text-start fs-7 text-uppercase gs-0">
        <th>No</th>
        <th>Title</th>
        <th>Description</th>
        <th>Price (৳)</th>
        <th>Status</th>
        <th>Editable</th> {{-- New Column --}}
        <th class="text-end">Actions</th>
    </tr>
</thead>


                        <tbody class="fw-semibold text-gray-700">
    @forelse($services as $service)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $service->title }}</td>
            <td>{{ Str::limit($service->description, 50) ?? '-' }}</td>
            <td>{{ number_format($service->price, 2) }}</td>

            {{-- Status --}}
            <td>
                <span class="badge badge-light-{{ $service->status ? 'success' : 'secondary' }}">
                    {{ $service->status ? 'Active' : 'Disabled' }}
                </span>
            </td>

            {{-- Editable Dropdown --}}
<td class="align-middle">
    @can('update editable status') {{-- এটা আলাদা permission name --}}
        <select class="form-select editable-status"
                data-service-id="{{ $service->id }}">
            <option value="1" {{ (int)$service->editable_status === 1 ? 'selected' : '' }}>Editable</option>
            <option value="0" {{ (int)$service->editable_status === 0 ? 'selected' : '' }}>Not Editable</option>
        </select>
    @else
        <span>
            {{ (int)$service->editable_status === 1 ? 'Editable' : 'Not Editable' }}
        </span>
    @endcan
</td>


            {{-- Actions --}}
            <td class="text-end">
                <div class="btn-group" role="group">
                    {{-- Edit --}}
                    <a href="{{ route('additional-services.edit', $service->id) }}"
                       class="btn btn-icon btn-sm btn-light-success" data-bs-toggle="tooltip" title="Edit">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </a>

                    {{-- View --}}
                    <a href="{{ route('additional-services.show', $service->id) }}"
                       class="btn btn-icon btn-sm btn-light-primary" data-bs-toggle="tooltip" title="View">
                        <i class="bi bi-eye fs-5"></i>
                    </a>

                    {{-- Delete --}}
                    <form action="{{ route('additional-services.destroy', $service->id) }}"
                          method="POST" class="d-inline"
                          onsubmit="return confirm('Are you sure you want to delete this service?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-icon btn-sm btn-light-danger"
                                data-bs-toggle="tooltip" title="Delete">
                            <i class="bi bi-trash3 fs-5"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center">
                No Services Found
            </td>
        </tr>
    @endforelse
</tbody>

                    </table>

                    {{-- Pagination --}}
                    @if (method_exists($services, 'links'))
                        <div class="mt-4 d-flex justify-content-end">
                            {{ $services->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

</x-default-layout>
<script>
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('.editable-status').forEach(function(dropdown) {

        // Color update function
        function updateColor(el) {
            el.classList.remove('text-success', 'text-secondary');
            if (el.value == "1") el.classList.add('text-success'); // Editable
            if (el.value == "0") el.classList.add('text-secondary'); // Not Editable
        }

        // Initial color
        updateColor(dropdown);

        // Handle change
        dropdown.addEventListener('change', function () {
            let serviceId = this.getAttribute('data-service-id');
            let newStatus = this.value;

            fetch("{{ route('additional-services.toggle-editable-ajax') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify({
                    service_id: serviceId,
                    status: newStatus
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateColor(this);
                    alert("Editable status updated!");
                } else {
                    alert("Update failed!");
                }
            })
            .catch(err => console.error(err));
        });
    });
});
</script>
