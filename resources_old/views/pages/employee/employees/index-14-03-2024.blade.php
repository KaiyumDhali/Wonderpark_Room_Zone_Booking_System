<x-default-layout>
    <style>
        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }
    </style>
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
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Add
                    Employee</h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container">
            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <div class="w-150px">
                                <select class="form-select p-2 form-select-solid" id="changeBranch" name="branch_id"
                                    data-control="select2" data-hide-search="true" data-placeholder="Status"
                                    data-kt-ecommerce-order-filter="status">
                                    {{-- <option value="0">All Branch</option> --}}
                                    @foreach ($allEmpBranch as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                    <option value="0">All Branch</option>
                                </select>
                            </div>
                        </div>

                        {{-- <div class="d-flex align-items-center position-relative my-1">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
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
                            <!--end::Svg Icon-->
                            <input type="text" data-kt-ecommerce-order-filter="search"
                                class="form-control form-control-solid w-250px ps-14 p-2" placeholder="Search Report" />
                        </div> --}}
                        <!--end::Search-->
                        <!--begin::Export buttons-->
                        <div id="kt_ecommerce_report_customer_orders_export" class="d-none"></div>
                        <!--end::Export buttons-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <!--begin::Daterangepicker-->
                        {{-- <input class="form-control form-control-solid w-100 mw-250px" placeholder="Pick date range" id="kt_ecommerce_report_customer_orders_daterangepicker" /> --}}
                        <!--end::Daterangepicker-->
                        <!--begin::Filter-->

                        {{-- <div class="w-150px">
                            <select class="form-select p-2 form-select-solid" data-control="select2"
                                data-hide-search="true" data-placeholder="Status"
                                data-kt-ecommerce-order-filter="status">
                                <option value="all"> Status</option>
                                <option value="Active">Active</option>
                                <option value="Disabled">Disabled</option>
                            </select>
                        </div> --}}
                        <!--end::Filter-->
                        @can('create employee')
                            <a href="{{ route('employees.create') }}" class="btn btn-sm btn-primary">Add Employee</a>
                        @endcan
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="kt_ecommerce_report_customer_orders_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                {{-- <th class="min-w-50px">Order</th> --}}
                                <th class="min-w-50px">Code</th>
                                <th class="min-w-100px">Name</th>
                                <th class="min-w-50px">Status</th>
                                <th class="min-w-50px">Gender</th>
                                <th class="min-w-100px">Mobile</th>
                                {{-- <th class="min-w-50px">Branch</th>
                                <th class="min-w-50px">Department</th>
                                <th class="min-w-50px">Designation</th> --}}
                                <th class="text-end min-w-100px">Actions</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-700" id="employeesList">
                            @foreach ($employees as $employee)
                                <tr>
                                    {{-- <td>{{ $employee->order }}</td> --}}
                                    <td>{{ $employee->employee_code }}</td>
                                    <td>
                                        <a class="text-dark text-hover-primary">{{ $employee->employee_name }}</a>
                                    </td>
                                    <td>
                                        <div
                                            class="badge badge-light-{{ $employee->status == 1 ? 'success' : 'info' }}">
                                            {{ $employee->status == 1 ? 'Active' : 'Disabled' }}</div>
                                    </td>
                                    <td>
                                        <div class="badge badge-light-primary">{{ $employee->gender }}</div>
                                    </td>
                                    <td>{{ $employee->mobile }}</td>
                                    {{-- <td>{{ $employee->branch_name }}</td>
                                    <td>{{ $employee->department_name }}</td>
                                    <td>{{ $employee->designation_name }}</td> --}}
                                    <td class="text-end">
                                        @can('write employee')
                                            <a href="{{ route('employees.edit', $employee->id) }}"
                                                class="btn btn-sm btn-success hover-scale p-2" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Edit"><i
                                                    class="bi bi-check2-square fs-2 ms-1"></i></a>
                                        @endcan
                                        <a href="{{ route('employees.show', $employee->id) }}"
                                            class="btn btn-sm btn-info hover-scale p-2" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="View"><i
                                                class="bi bi-eye fs-2 ms-1"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Products-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</x-default-layout>

<script type="text/javascript">
    $(document).on('change', '#changeBranch', function() {
        var branchID = $(this).val();
        var url = '{{ route('branchEmployees', ':branchID') }}';
        url = url.replace(':branchID', branchID);
        if (branchID) {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    $('#employeesList').empty();
                    if (data && data.length > 0) { // Check if data is not empty

                        var editRoute = @json(route('employees.edit', ['employee' => '_id_']));
                        var showRoute = @json(route('employees.show', ['employee' => '_id_']));

                        $.each(data, function(key, value) {
                            var editUrl = editRoute.replace('_id_', value.id);
                            var showUrl = showRoute.replace('_id_', value.id);

                            // Use template literals for better readability
                            $('#employeesList').append(`
                                <tr>
                                    <td>${value.employee_code}</td>
                                    <td><a class="text-dark text-hover-primary">${value.employee_name}</a></td>
                                    <td><div class="badge badge-light-${value.status == 1 ? 'success' : 'info'}">${value.status == 1 ? 'Active' : 'Disabled'}</div></td>
                                    <td><div class="badge badge-light-primary">${value.gender}</div></td>
                                    {{-- <td>${value.mobile}</td> --}}
                                    <td>${value.branch_name}</td>
                                    <td>${value.department_name}</td>
                                    <td>${value.designation_name}</td>
                                    <td class="text-end">
                                        @can('write employee')
                                            <a href="${editUrl}" class="btn btn-sm btn-success hover-scale p-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                <i class="bi bi-check2-square fs-2 ms-1"></i>
                                            </a>
                                        @endcan
                                        <a href="${showUrl}" class="btn btn-sm btn-info hover-scale p-2" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                            <i class="bi bi-eye fs-2 ms-1"></i>
                                        </a>
                                    </td>
                                </tr>`);
                        });
                    } else {
                        $('#jsdataerror').text('No data found');
                    }
                },
                error: function() {
                    $('#jsdataerror').text('Error fetching data');
                }
            });
        } else {
            $('#jsdataerror').text('Branch ID not selected');
        }
    });
</script>
