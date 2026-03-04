<x-default-layout>

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-stack mb-6">
        <div>
            <h3 class="fw-bold mb-1">All Common Facilities</h3>
            <span class="text-muted fs-7">Manage all common facilities</span>
        </div>
        {{-- Add New Facility Button --}}
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addFacilityModal">
            <i class="bi bi-plus-circle"></i> Add New Facility
        </button>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- FACILITIES TABLE WITH BORDERS --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 text-center">#</th>
                            <th class="text-center">Facility Name</th>
                            <th class=" text-center">Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($facilities as $facility)
                        <tr>
                            <td class="ps-4 text-center">{{ $facility->id }}</td>
                            <td class="fw-semibold">{{ $facility->facility_name }}</td>
                            <td class=" text-center">
                                @if($facility->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                {{-- Edit Button --}}
                                <button type="button" class="btn btn-sm btn-light-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editFacilityModal{{ $facility->id }}">
                                    Edit
                                </button>

                                {{-- Delete Button --}}
                                <form action="{{ route('common-facilities.destroy', $facility->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light-danger" onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- EDIT MODAL --}}
                        <div class="modal fade" id="editFacilityModal{{ $facility->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('common-facilities.update', $facility->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Facility</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label required">Facility Name</label>
                                                <input type="text" name="facility_name" class="form-control form-control-solid" value="{{ $facility->facility_name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label required">Status</label>
                                                <select name="status" class="form-select form-select-solid" required>
                                                    <option value="1" {{ $facility->status == 1 ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ $facility->status == 0 ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">Update Facility</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-6">No facilities found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ADD MODAL --}}
    <div class="modal fade" id="addFacilityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('common-facilities.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Facility (Single or Multiple)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="facility-wrapper">
                            <div class="row mb-3 facility-row g-3 align-items-end">
                                <div class="col-md-6">
                                    <label class="form-label required">Facility Name</label>
                                    <input type="text" name="facility_name[]" class="form-control form-control-solid" placeholder="e.g. Gym, Pool" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Status</label>
                                    <select name="status[]" class="form-select form-select-solid" required>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-sm btn-danger remove-btn mt-8">Remove</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-more" class="btn btn-sm btn-primary mt-2">Add More Facility</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save Facility(s)</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JS FOR ADD/REMOVE ROWS --}}
    <script>
        // Add new row
        document.getElementById('add-more').addEventListener('click', function() {
            const wrapper = document.getElementById('facility-wrapper');
            const row = document.querySelector('.facility-row').cloneNode(true);
            row.querySelectorAll('input').forEach(input => input.value = '');
            wrapper.appendChild(row);
        });

        // Remove row
        document.addEventListener('click', function(e) {
            if(e.target.classList.contains('remove-btn')) {
                const rows = document.querySelectorAll('.facility-row');
                if(rows.length > 1) {
                    e.target.closest('.facility-row').remove();
                }
            }
        });
    </script>

</x-default-layout>
