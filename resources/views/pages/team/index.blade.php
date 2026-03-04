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

    <!-- Error & Success Messages -->
    <div class="col-xl-12 px-5">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('message'))
            <div class="alert alert-{{ session('alert-type') }}">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <!-- Toolbar -->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h3>Team List</h3>
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
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                        rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path
                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <input type="text" class="form-control form-control-solid w-250px ps-14 p-2"
                                placeholder="Search team" />
                        </div>
                    </div>
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <a href="{{ route('team.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Add Team
                        </a>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body pt-0">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                        <thead>
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th>No</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Image</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700">
                            @forelse($teams as $team)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $team->name }}</td>
                                    <td>{{ $team->designation }}</td>
                                    <td>
                                        <img src="{{ asset(Storage::url($team->image)) }}" class="rounded"
                                            width="80" height="80" alt="Team Image" style="object-fit: cover;" />
                                    </td>
                                    <td>{{ $team->order }}</td>
                                    <td>
                                        <span
                                            class="badge badge-light-{{ $team->status == 1 ? 'success' : 'secondary' }}">
                                            {{ $team->status == 1 ? 'Active' : 'Disabled' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <!-- Edit -->
                                            <a href="{{ route('team.edit', $team->id) }}"
                                                class="btn btn-icon btn-sm btn-light-success" data-bs-toggle="tooltip"
                                                title="Edit">
                                                <i class="bi bi-pencil-square fs-5"></i>
                                            </a>
                                            <!-- View -->
                                            <a href="{{ route('team.show', $team->id) }}"
                                                class="btn btn-icon btn-sm btn-light-primary" data-bs-toggle="tooltip"
                                                title="View">
                                                <i class="bi bi-eye fs-5"></i>
                                            </a>
                                            <!-- Delete -->
                                            <form action="{{ route('team.destroy', $team->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Are you sure you want to delete this?')">
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
                                    <td colspan="7" class="text-center">{{ __('No Team Members Found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</x-default-layout>
