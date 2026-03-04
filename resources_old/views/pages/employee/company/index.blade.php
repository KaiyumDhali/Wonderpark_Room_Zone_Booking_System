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
    <div id="kt_app_toolbar" class="app-toolbar py-3 ">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h3>Company Setting</h3>
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
                <div class="card-header align-items-center py-3 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
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
                                class="form-control form-control-solid w-250px ps-14 p-2" placeholder="Search" />
                        </div>
                        <!--end::Search-->
                        <!--begin::Export buttons-->
                        <div id="kt_ecommerce_report_customer_orders_export" class="d-none"></div>
                        <!--end::Export buttons-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        @can('create company setting')
                            <a href="{{ route('emp_company.create') }}" class="btn btn-primary btn-sm">Add Company Setting
                            </a>
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
                                <th class="min-w-50px"> SL</th>
                                <th class="min-w-50px"> Company Name</th>
                                <th class="min-w-50px"> E-mail</th>
                                <th class="min-w-50px"> Cell Number</th>
                                <th class="min-w-50px"> Phone Number</th>
                                <th class="min-w-50px"> Status</th>
                                @can('write company setting')
                                    <th class="text-end min-w-100px">Actions</th>
                                @endcan
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-700">
                            @foreach ($companySettings as $companySetting)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $companySetting->company_name }}
                                    </td>
                                    <td>
                                        {{ $companySetting->company_email }}
                                    </td>
                                    <td>
                                        {{ $companySetting->company_mobile }}
                                    </td>
                                    <td>
                                        {{ $companySetting->company_phone }}
                                    </td>
                                    <td>
                                        <div
                                            class="badge badge-light-{{ $companySetting->status == 1 ? 'success' : 'info' }}">
                                            {{ $companySetting->status == 1 ? 'Active' : 'Disabled' }}</div>
                                    </td>

                                    @can('write company setting')
                                        <td class="text-end">
                                            <!-- edit Button -->
                                            <a href="{{ route('emp_company.edit', $companySetting->id) }}"
                                                class="btn btn-sm btn-success hover-scale p-2" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Edit"><i
                                                    class="bi bi-check2-square fs-2 ms-1"></i></a>
                                            <!-- show Button -->
                                            <a href="{{ route('emp_company.show', $companySetting->id) }}"
                                                class="btn btn-sm btn-info hover-scale p-2" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Show"><i
                                                    class="bi bi-eye fs-2 ms-1"></i></a>

                                            <!-- Delete Button and Form -->
                                            @can('write company setting')
                                                <form action="{{ route('emp_company.destroy', $companySetting->id) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger hover-scale p-2"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this record?');">
                                                        <i class="bi bi-trash fs-2 ms-1"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    @endcan
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
