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
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container  d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h3>Manage Announcement</h3>
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
        <div id="kt_app_content_container" class="app-container ">
            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
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
                                class="form-control form-control w-250px ps-14 p-2" placeholder="Search" />
                        </div>
                        <!--end::Search-->
                        <!--begin::Export buttons-->
                        <div id="kt_ecommerce_report_customer_orders_export" class="d-none"></div>
                        <!--end::Export buttons-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        @can('create announcement')
                            <a href="{{ route('announcement.create') }}" class="btn btn-sm btn-primary">
                                <span class="svg-icon svg-icon-3 pe-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5"
                                            fill="currentColor">
                                        </rect>
                                        <rect x="10.8891" y="17.8033" width="12" height="2" rx="1"
                                            transform="rotate(-90 10.8891 17.8033)" fill="currentColor"></rect>
                                        <rect x="6.01041" y="10.9247" width="12" height="2" rx="1"
                                            fill="currentColor">
                                        </rect>
                                    </svg>
                                </span>
                                New Announcement</a>
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
                                <th class="min-w-50px">SL</th>
                                <th class="min-w-50px">Title</th>
                                <th class="min-w-100px">Notice visible Start Date</th>
                                {{-- <th class="min-w-100px">Notice visible End Date</th> --}}
                                <th class="min-w-100px">Description</th>
                                @can('write announcement')
                                    <th class="min-w-100px">Action</th>
                                @endcan
                            </tr>

                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-700">
                            @foreach ($announcements as $announcement)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $announcement->title }}
                                    </td>
                                    <td>
                                        {{ $announcement->start_date }}
                                    </td>
                                    {{-- <td>
                                        {{ $announcement->end_date }}
                                                                </td> --}}
                                    <td>
                                        {{ $announcement->description }}
                                    </td>
                                    @can('write announcement')
                                        <td>
                                            <form method="POST"
                                                action="{{ route('announcement.destroy', $announcement->id) }}">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}


                                                <a href="{{ route('announcement.show', Crypt::encrypt($announcement->id)) }}"
                                                    class="btn btn-sm btn-info hover-scale p-2" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="View"><i
                                                        class="bi bi-eye fs-2 ms-1"></i>
                                                </a>
                                                <!-- <button type="button" class="btn btn-sm btn-success p-2"
                                                                                                                                         data-employeeAnnouncement="{{-- json_encode($announcement) --}}"
                                                                                                                                         onclick="openEditModalAnnouncement(this)">
                                                                                                                                         <i class="fa fa-pencil text-white px-2"></i>
                                                                                                                                         </button>-->
                                                <button type="submit" class="btn btn-sm btn-danger p-2">
                                                    <i class="fa fa-trash text-white px-2"></i>
                                                </button>
                                            </form>
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



    <!--begin::Modal - Create App-->
    <div class="modal fade" id="kt_modal_new_card" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2>Add New Announcement</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                        method="POST" action="{{ route('announcement.store') }}" enctype="multipart/form-data">
                        @csrf
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Title</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    aria-label="Specify a card holder's name"
                                    data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control" name="title" required>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Select Branch</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    aria-label="Specify a card holder's name"
                                    data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->
                            <select class="form-select form-select-sm" id="changeBranch" name="branch_id" required>
                                <option selected disabled>Select Branch</option>
                                @foreach ($allEmpBranch as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>


                        <!--                                                                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                                                                                                                                                                        begin::Label
                                                                                                                                                                                                        <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                                                                                                                                                                        <span class="required">Employee Department</span>
                                                                                                                                                                                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                                                                                                                                                                                        aria-label="Specify a card holder's name"
                                                                                                                                                                                                        data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                                                                                                                                                                                                        </label>
                                                                                                                                                                                                        end::Label
                                                                                                                                                                                                        <select class="form-select form-select-sm" id="changeDepartment" name="department_id"
                                                                                                                                                                                                        required>
                                                                                                                                                                                                        <option selected disabled>Select Employee Department</option>
                                                                                                        {{-- @foreach ($allDepartment as $key => $value)
                                                                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                                                                        @endforeach --}}
                                                                                                                                                                                                        </select>
                                                                                                                                                                                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                                                                                                                                                                                        </div>-->


                        <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                            <span class="required">Employee Department</span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                aria-label="Specify a card holder's name"
                                data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                        </label>
                        @foreach ($allDepartment as $departmentId => $departmentName)
                            <label>
                                <input type="checkbox" name="departments[]" value="{{ $departmentId }}">
                                {{ $departmentName }}
                            </label><br>
                        @endforeach




                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Employee Designation</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    aria-label="Specify a card holder's name"
                                    data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->
                            <select class="form-select form-select-sm" name="designation_id" required>
                                <option selected disabled>Select Employee Designation</option>
                                @foreach ($allEmpDesignation as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Employee Name</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    aria-label="Specify a card holder's name"
                                    data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->
                            <select class="form-select form-select-sm" name="employee_id" required>
                                <option selected disabled>Select Employee</option>
                                @foreach ($allEmployees as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Start Date</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    aria-label="Specify a card holder's name"
                                    data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->
                            <input type="date" class="form-control" name="start_date">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">End Date</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    aria-label="Specify a card holder's name"
                                    data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->
                            <input type="date" class="form-control" name="end_date">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">description</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    aria-label="Specify a card holder's name"
                                    data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control" name="description">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class=" form-label">Status</label>
                            <select class="form-select form-select-sm" name="status">
                                <option value="">--Select Status--</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                        <div class="text-center pt-15">
                            <!-- <button type="reset" id="kt_modal_new_card_cancel"
                                class="btn btn-light me-3">Discard</button> -->

                            <a href="{{ route('announcement.index') }}" id="kt_modal_new_card_cancel"
                                class="btn btn-light me-5">Cancel</a>

                            <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
    </div>
    <!--end::Modal - Create App-->


    <!--begin::Modal - Create App-->
    <div class="modal fade" id="kt_modal_edit_card" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2>Edit Announcement</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_edit_card_form_announcement"
                        class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                        action="{{ route('announcement.update', ':announcementId') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!--begin::Input group-->
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Title</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    aria-label="Specify a card holder's name"
                                    data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control" name="title">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Select Branch</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    aria-label="Specify a card holder's name"
                                    data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->

                            <select class="form-select form-select-sm" id="changeBranch2" name="branch_id" required>
                                <option selected disabled>Select Branch</option>
                                @foreach ($allEmpBranch as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Employee Department</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    aria-label="Specify a card holder's name"
                                    data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->
                            <select class="form-select form-select-sm" id="changeDepartment2" name="department_id"
                                required>
                                <option selected disabled>Select Employee Department</option>
                                @foreach ($allDepartment as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Employee Designation</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    aria-label="Specify a card holder's name"
                                    data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->
                            <select class="form-select form-select-sm" name="designation_id" required>
                                <option selected disabled>Select Employee Designation</option>
                                @foreach ($allEmpDesignation as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Employee Name</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    aria-label="Specify a card holder's name"
                                    data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->
                            <select class="form-select form-select-sm" name="employee_id" required>
                                <option selected disabled>Select Employee</option>
                                @foreach ($allEmployees as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Start Date</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    aria-label="Specify a card holder's name"
                                    data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->
                            <input type="date" class="form-control" name="start_date">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">End Date</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    aria-label="Specify a card holder's name"
                                    data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->
                            <input type="date" class="form-control" name="end_date">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">description</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    aria-label="Specify a card holder's name"
                                    data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control" name="description">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class=" form-label">Status</label>
                            <select class="form-select form-select-sm" name="status">
                                <option value="">--Select Status--</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                        <div class="text-center pt-15">
                            <!-- <button type="reset" id="kt_modal_new_card_cancel"
                                 class="btn btn-light me-3">Discard</button> -->

                            <a href="{{ route('announcement.index') }}" id="kt_modal_new_card_cancel"
                                class="btn btn-light me-5">Cancel</a>

                            <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>


                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
    </div>
    <!--end::Modal - Create App-->

</x-default-layout>
<script type="text/javascript">
    function openEditModalAnnouncement(button) {

        const announcementData = JSON.parse(button.getAttribute('data-employeeAnnouncement'));

        const formAction = $('#kt_modal_edit_card').find('#kt_modal_edit_card_form_announcement').attr('action');
        const announcementId = announcementData.id;
        const updatedFormAction = formAction.replace(':announcementId', announcementId);
        $('#kt_modal_edit_card').find('#kt_modal_edit_card_form_announcement').attr('action', updatedFormAction);

        $('#kt_modal_edit_card').find('input[name="title"]').val(announcementData.title);
        $('#kt_modal_edit_card').find('input[name="start_date"]').val(announcementData.start_date);
        $('#kt_modal_edit_card').find('input[name="end_date"]').val(announcementData.end_date);
        $('#kt_modal_edit_card').find('select[name="branch_id"]').val(announcementData.branch_id);

        //$('#kt_modal_edit_card').find('input[name="departments[]"][value="' + departmentId + '"]').prop('checked', true);

        $('#kt_modal_edit_card').find('input[name="departments[]"]').prop('checked', false);

        // Check checkboxes based on announcementData.department_id
        if (Array.isArray(announcementData.department_id)) {
            announcementData.department_id.forEach(function(departmentId) {
                $('#kt_modal_edit_card').find('input[name="departments[]"][value="' + departmentId + '"]').prop(
                    'checked', true);
            });
        }

        //$('#kt_modal_edit_card').find('select[name="department_id"]').val(announcementData.department_id);
        $('#kt_modal_edit_card').find('select[name="designation_id"]').val(announcementData.designation_id);
        $('#kt_modal_edit_card').find('select[name="employee_id"]').val(announcementData.employee_id);
        $('#kt_modal_edit_card').find('input[name="description"]').val(announcementData.description);

        $('#kt_modal_edit_card').modal('show');
    }
</script>

<script type="text/javascript">
    $('#changeBranch').on('change', function() {
        var branchID = $(this).val();
        var url = '{{ route('branchDepartment', ':branchID') }}';
        url = url.replace(':branchID', branchID);

        console.log(url);
        if (branchID) {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    $('#changeDepartment').empty();

                    $('#changeDepartment').append(
                        `<option selected disabled>Select Department</option>`
                    );
                    if (data) {
                        $.each(data, function(key, value) {
                            $('#changeDepartment').append(
                                `<option value="` + value.id + `">` + value
                                .department_name +
                                `</option>`
                            );
                        });
                    } else {
                        $('#jsdataerror').text('Not Found');
                    }
                }
            });
        } else {
            $('#jsdataerror').text('Not Found');
        }

    });
</script>

<script type="text/javascript">
    $('#changeBranch2').on('change', function() {
        var branchID = $(this).val();
        var url = '{{ route('branchDepartment', ':branchID') }}';
        url = url.replace(':branchID', branchID);

        console.log(url);
        if (branchID) {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    $('#changeDepartment2').empty();

                    $('#changeDepartment2').append(
                        `<option selected disabled>Select Department</option>`
                    );
                    if (data) {
                        $.each(data, function(key, value) {
                            $('#changeDepartment2').append(
                                `<option value="` + value.id + `">` + value
                                .department_name +
                                `</option>`
                            );
                        });
                    } else {
                        $('#jsdataerror').text('Not Found');
                    }
                }
            });
        } else {
            $('#jsdataerror').text('Not Found');
        }

    });


    $(function() {

        $('a[data-bs-toggle="tab"]').on('show.bs.tab', function(e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('#myTabjustified a[href="' + activeTab + '"]').tab('show');
        }

    });
</script>


<!--<script>
    $(document).ready(function() {
        $('#sendAnnouncement').click(function() {
            const formData = $('#announcementForm').serialize();

            $.ajax({
                type: 'POST',
                url: '{{-- route("announcement") --}}', // Replace with your Laravel route
                data: formData,
                success: function(response) {
                    alert('Announcement sent successfully.');
                    // Clear form or perform other actions as needed
                },
                error: function(xhr, status, error) {
                    alert('Failed to send announcement.');
                    console.error(error);
                }
            });
        });
    });
</script>-->

<script>
    $(document).ready(function() {
        // Initially, hide all dependent checkboxes
        $('#department, #designation, #employee').hide();

        // Show/hide checkboxes based on selected companies
        $('input[name="companies[]"]').change(function() {
            const selectedCompanies = $('input[name="companies[]"]:checked').map(function() {
                return this.value;
            }).get();

            if (selectedCompanies.length > 0) {
                $('#department, #designation, #employee')
                    .hide(); // Hide all dependent checkboxes initially

                if ($.inArray('1', selectedCompanies) !== -1) {
                    // Show department options related to Branch 1
                    $('#department').show();
                }

                if ($.inArray('2', selectedCompanies) !== -1) {
                    // Show designation options related to Branch 2
                    $('#designation').show();
                }

                if ($.inArray('3', selectedCompanies) !== -1) {
                    // Show employee options related to Branch 3
                    $('#employee').show();
                }
            } else {
                $('#department, #designation, #employee').hide();
            }
        });

        // Handle the form submission with AJAX (as shown in previous examples)
        $('#sendAnnouncement').click(function() {
            const formData = $('#announcementForm').serialize();
            $.ajax({
                type: 'POST',
                url: '{{-- route("send-announcement") --}}',
                data: formData,
                success: function(response) {
                    alert('Announcement sent successfully.');
                    Clear form or perform other actions as needed
                },
                error: function(xhr, status, error) {
                    alert('Failed to send announcement.');
                    console.error(error);
                }
            });
        });
    });
</script>

<script>
    $(".parent input").on('click', function() {
        var _parent = $(this);
        var nextli = $(this).parent().next().children().children();

        if (_parent.prop('checked')) {
            console.log('parent checked');
            nextli.each(function() {
                $(this).children().prop('checked', true);
            });

        } else {
            console.log('parent un checked');
            nextli.each(function() {
                $(this).children().prop('checked', false);
            });

        }
    });

    $(".child input").on('click', function() {

        var ths = $(this);
        var parentinput = ths.closest('div').prev().children();
        var sibblingsli = ths.closest('ul').find('li');

        if (ths.prop('checked')) {
            console.log('child checked');
            parentinput.prop('checked', true);
        } else {
            console.log('child unchecked');
            var status = true;
            sibblingsli.each(function() {
                console.log('sibb');
                if ($(this).children().prop('checked')) status = false;
            });
            if (status) parentinput.prop('checked', false);
        }
    });
</script>
