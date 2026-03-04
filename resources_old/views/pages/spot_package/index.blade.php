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
                <h3>Spot Packages</h3>
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
                                placeholder="Search package" />
                        </div>
                    </div>

                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <a href="{{ route('spot-packages.create') }}" class="btn btn-sm btn-primary">
                            Add Package
                        </a>
                    </div>
                </div>

                <!-- Table -->
                <div class="card-body pt-0">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                        <thead>
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th>No</th>
                                <th>Packege Name</th>
                                <th>Max Capacity</th>
                                <th>Price (৳)</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="fw-semibold text-gray-700">
                            @forelse($packages as $package)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $package->name ?? '-' }}</td>

                                    <td>{{ $package->persons }}</td>

                                    <td>{{ number_format($package->price, 2) }}</td>

                                    <td>
                                        <span
                                            class="badge badge-light-{{ $package->status ? 'success' : 'secondary' }}">
                                            {{ $package->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>

                                    <td class="text-end">
                                        <div class="btn-group" role="group">

                                            {{-- Edit --}}
                                            <a href="{{ route('spot-packages.edit', $package->id) }}"
                                                class="btn btn-icon btn-sm btn-light-success" data-bs-toggle="tooltip"
                                                title="Edit">
                                                <i class="bi bi-pencil-square fs-5"></i>
                                            </a>

                                            {{-- Delete --}}
                                            {{-- <form action="{{ route('spot-packages.destroy', $package->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-sm btn-light-danger"
                                                    data-bs-toggle="tooltip" title="Delete">
                                                    <i class="bi bi-trash3 fs-5"></i>
                                                </button>
                                            </form> --}}

                                            <form action="{{ route('spot-packages.destroy', $package->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-sm btn-light-danger">
                                                    <i class="bi bi-trash3 fs-5"></i>
                                                </button>
                                            </form>


                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        {{ __('No Packages Found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</x-default-layout>
