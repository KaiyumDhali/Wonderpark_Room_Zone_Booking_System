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
                <h3>Manage Promotion</h3>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="promotion_list" class="app-content flex-column-fluid ">
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
                                                   class="form-control form-control-sm w-250px ps-14 p-2" placeholder="Search" />
                                                </div>
                                                <!--end::Search-->
                                                <!--begin::Export buttons-->
                                                <div id="kt_ecommerce_report_customer_orders_export" class="d-none"></div>
                                                <!--end::Export buttons-->
                                                </div>
                                                <!--end::Card title-->
                                                <!--begin::Card toolbar-->
                                                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        @can('create promotion')
                                                    <a href="#" onclick="myFunction()" class="btn btn-sm btn-flex btn-light-primary"
                                                       data-bs-toggle="modal" data-bs-target="#">
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
                                                        Add New
                                                    </a>
                        @endcan
                                                </div>
                                                <!--end::Card toolbar-->
                                                </div>
                                                <!--end::Card header-->
                                                <!--begin::Card body-->
                                                <div class="card-body pt-0" id="card-body">
                                                    <!--begin::Table-->
                                                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                                                           id="kt_ecommerce_report_customer_orders_table">
                                                        <!--begin::Table head-->
                                                        <thead>
                                                            <!--begin::Table row-->
                                                            <tr class="text-start fs-7 text-uppercase gs-0">
                                                                <th class="min-w-50px"> SL</th>
                                                                <th class="min-w-50px"> ID</th>
                                                                <th class="min-w-50px"> Employee Code</th>
                                                                <th class="min-w-50px"> Employee Name</th>
                                                                <th class="min-w-50px"> Branch</th>
                                                                <th class="min-w-50px"> Department</th>
                                                                <th class="min-w-100px"> Designation</th>
                                                                <th class="min-w-100px">From</th>
                                                                <th class="min-w-100px">To</th>
                                                                <th class="min-w-100px">Description</th>
                                @can('write promotion')
                                                                <th class="min-w-100px">Action</th>
                                @endcan
                                                            </tr>
                                                            <!--end::Table row-->
                                                        </thead>
                                                        <!--end::Table head-->
                                                        <!--begin::Table body-->
                                                        <tbody class="fw-semibold text-gray-700">

                            @foreach ($promotions as $promotion)
                                                            <tr>
                                                                <td>
                                        {{ $loop->iteration }}
                                                                </td>
                                                                <td>
                                        {{ $promotion->id }}
                                                                </td>
                                                                <td>
                                        {{ $promotion->employee->employee_code }}
                                                                </td>
                                                                <td>
                                        {{ $promotion->employee->employee_name }}
                                                                </td>
                                                                <td>
                                        {{ $promotion->empbranch->branch_name }}
                                                                </td>
                                                                <td>
                                        {{ $promotion->empdepartment->department_name }}
                                                                </td>
                                                                <td>
                                        {{ $promotion->empdesignation->designation_name }}
                                                                </td>
                                                                <td>
                                        {{ $promotion->start_date }}
                                                                </td>
                                                                <td>
                                        @if ($promotion->end_date == null)
                                                                    Continue
                                        @else
                                            {{ $promotion->end_date }}
                                        @endif
                                                                </td>
                                                                <td>
                                        {{ $promotion->description }}
                                                                </td>
                                    @can('write promotion')
                                                                <td>

                                                                    <button type="button" class="btn btn-sm btn-success p-2"
                                                {{ $promotion->end_date != null ? 'disabled' : '' }}
                                                                        data-employeePromotion="{{ json_encode($promotion) }}"
                                                                        onclick="openEditModalPromotion(this)" data-bs-toggle="tooltip"
                                                                        data-bs-placement="top" title="Edit">
                                                                        <i class="bi bi-check2-square fs-2 ms-1"></i>
                                                                    </button>

                                                                    <!--                                             <form method="POST"
                                                                                                                                                                                                        action="{{-- route('promotion.destroy', $promotion->id) --}}">
                                                                                                        {{-- csrf_field() --}}
                                                                                                        {{-- method_field('DELETE') --}}
                                                                                                                                                    
                                                                                                                                                                                                        <button type="button" class="btn btn-sm btn-success p-2"
                                                                                                                                                                                                        data-employeePromotion="{{-- json_encode($promotion) --}}"
                                                                                                                                                                                                        onclick="openEditModalPromotion(this)" data-bs-toggle="tooltip"
                                                                                                                                                                                                        data-bs-placement="top" title="View">
                                                                                                                                                                                                        <i class="bi bi-check2-square fs-2 ms-1"></i>
                                                                                                                                                                                                        </button>
                                                                                                                                                                                                        <button type="submit" class="btn btn-sm btn-danger p-2" data-bs-toggle="tooltip"
                                                                                                                                                                                                        data-bs-placement="top" title="Delete">
                                                                                                                                                                                                        <i class="bi bi-trash fs-2 ms-1"></i>
                                                                                                                                                                                                        </button>
                                                                                                                                                                                                        </form>-->

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

                                                <!--begin::Content-->
                                                <div id="addPromotion" class="app-content flex-column-fluid d-none">
                                                    <!--begin::Content container-->
                                                    <div id="" class="app-container ">
                                                        <!--begin::Products-->
                                                        <div class="card card-flush">
                                                            <!--begin::Card header-->
                                                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                                                <!--begin::Card title-->
                                                                <div class="card-title">
                                                                    <!--begin::Search-->

                                                                    <!--end::Search-->
                                                                    <!--begin::Export buttons-->
                                                                    <div id="kt_ecommerce_report_customer_orders_export" class="d-none"></div>
                                                                    <!--end::Export buttons-->
                                                                </div>
                                                                <!--end::Card title-->

                                                            </div>
                                                            <!--end::Card header-->
                                                            <!--begin::Card body-->
                                                            <div class="card-body pt-0" id="card-body">

                                                                <div class="" id="kt_modal_new_card" tabindex="-1" aria-hidden="true">
                                                                    <div class="">
                                                                        <!--begin::Modal content-->
                                                                        <div class="">
                                {{-- <!--begin::Modal header-->
                                <div class="modal-header">
                                    <!--begin::Modal title-->
                                    <h2>Add New Promotion</h2>
                                    <!--end::Modal title-->
                                </div>
                                <!--end::Modal header--> --}}

                                                                            <!--begin::Form-->
                                                                            <form id="kt_modal_new_card_form"
                                                                                  class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                                                                  action="{{ route('promotion.store') }}" enctype="multipart/form-data">
                                    @csrf

                                                                                <div class="row">
                                                                                    <div class="col-md-6 mx-auto">
                                                                                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                                                            <!--begin::Label-->
                                                                                            <label
                                                                                                class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                                                                <span class="required">Employee Name</span>
                                                                                            </label>
                                                                                            <!--end::Label-->
                                                                                            <select id="employee_id" required="required" class="form-select form-select-sm"
                                                                                                    name="employee_id" data-control="select2"
                                                                                                    data-hide-search="false">
                                                                                                <option value="">Select Employee</option>
                                                    @foreach ($allEmployees as $key => $allEmployee)
                                                                                                <option value="{{ $allEmployee->id }}">
                                                            {{ $allEmployee->id . '-' . $allEmployee->employee_name . '(' . $allEmployee->employee_code . ')' }}
                                                                                                </option>
                                                    @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>

                                                                                <div class="row">
                                                                                    <div class="col-5">
                                                                                        <h2>Current Designation</h2>
                                                                                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                                                            <!--begin::Label-->
                                                                                            <label
                                                                                                class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                                                                <span class="">Select Branch</span>
                                                                                            </label>
                                                                                            <!--end::Label-->
                                                                                            <select class="form-select form-select-sm branch" disabled>
                                                                                                <option selected disabled>Select Branch</option>
                                                                                            </select>
                                                                                        </div>

                                                                                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                                                            <!--begin::Label-->
                                                                                            <label
                                                                                                class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                                                                <span class="">Employee Department</span>
                                                                                            </label>
                                                                                            <!--end::Label-->
                                                                                            <select class="form-select form-select-sm department" disabled>
                                                                                                <option selected disabled>Select Employee Department</option>

                                                                                            </select>
                                                                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                                                                        </div>

                                                                                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                                                            <label class="form-label">Employee Designation</label>
                                                                                            <select class="form-select form-select-sm designation" disabled>
                                                                                                <option selected disabled>Select Employee Designation</option>
                                                                                            </select>
                                                                                        </div>

                                                                                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                                                            <!--begin::Label-->
                                                                                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                                                                <span class="required">Last Promotion Date</span>
                                                                                            </label>
                                                                                            <!--end::Label-->
                                                                                            <input type="date" disabled="disabled" id="lastPromotionDate" class="form-control form-control-sm" name="end_date">
                                                                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                                                                        </div>


                                                                                        <!--end::Actions-->
                                                                                    </div>

                                                                                    <div class="col-2">

                                                                                    </div>

                                                                                    <div class="col-5">
                                                                                        <h2>Promoted Designation</h2>
                                                                                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                                                            <!--begin::Label-->
                                                                                            <label
                                                                                                class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                                                                <span class="required">Select Branch</span>
                                                                                            </label>
                                                                                            <!--end::Label-->
                                                                                            <select class="form-select form-select-sm" required="required" id="changeBranch"
                                                                                                    name="branch_id" required>
                                                                                                <option selected disabled>Select Branch</option>
                                                    @foreach ($allEmpBranch as $key => $value)
                                                                                                <option value="{{ $key }}">{{ $value }}
                                                                                                </option>
                                                    @endforeach
                                                                                            </select>
                                                                                        </div>

                                                                                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                                                            <!--begin::Label-->
                                                                                            <label
                                                                                                class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                                                                <span class="required">Employee Department</span>
                                                                                            </label>
                                                                                            <!--end::Label-->
                                                                                            <select class="form-select form-select-sm" required="required" id="changeDepartment"
                                                                                                    name="department_id" required>
                                                                                                <option selected disabled>Select Employee Branch First</option>
                                                    @foreach ($allDepartment as $key => $value)
                                                                                                <option value="{{ $key }}">{{ $value }}
                                                                                                </option>
                                                    @endforeach
                                                                                            </select>
                                                                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                                                                        </div>

                                                                                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                                                            <label class="required form-label">Employee Designation</label>
                                                                                            <select class="form-select form-select-sm" name="designation_id"
                                                                                                    id="changeDesignation" required="required">
                                                                                                <option selected disabled>Select Employee Designation</option>
                                                    @foreach ($allEmpDesignation as $key => $value)
                                                                                                <option value="{{ $key }}">{{ $value }}
                                                                                                </option>
                                                    @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                                                            <!--begin::Label-->
                                                                                            <label
                                                                                                class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                                                                <span class="required">Promotion Date</span>
                                                                                            </label>
                                                                                            <!--end::Label-->
                                                                                            <input type="date" class="form-control form-control-sm" required="required"
                                                                                                   name="start_date">
                                                                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                                                                        </div>
                                                                                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                                                            <!--begin::Label-->
                                                                                            <label
                                                                                                class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                                                                <span class="">Description</span>
                                                                                            </label>
                                                                                            <!--end::Label-->
                                                                                            <input type="text" class="form-control form-control-sm"
                                                                                                   name="description">
                                                                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                                                                        </div>
                                                                                        <div class="pt-15" style="text-align: right;">
                                                                                            <a href="{{ route('promotion.index') }}"
                                                                                               id="kt_modal_new_card_cancel"
                                                                                               class="btn btn-light me-5">Cancel</a>

                                                                                            <button type="submit" id="kt_modal_new_card_submit"
                                                                                                    class="btn btn-primary">
                                                                                                <span class="indicator-label">Submit</span>
                                                                                                <span class="indicator-progress">Please wait...
                                                                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                                                            </button>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                                <!--end::Actions-->
                                                                            </form>
                                                                            <!--end::Form-->
                                                                        </div>
                                                                        <!--end::Modal content-->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--end::Card body-->
                                                        </div>
                                                        <!--end::Products-->
                                                    </div>
                                                    <!--end::Content container-->
                                                </div>
                                                <!--end::Content-->

                                                <!--Edit promotion begin=========================================================Edit promotion begin-->

                                                <!--begin::Modal - Create App-->
                                                <div class="modal fade" id="kt_modal_edit_card" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered mw-650px">
                                                        <!--begin::Modal content-->
                                                        <div class="modal-content">
                                                            <!--begin::Modal header-->
                                                            <div class="modal-header">
                                                                <!--begin::Modal title-->
                                                                <h2>Edit Promotion</h2>
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


                                                                <form id="kt_modal_edit_card_form_promotion"
                                                                      class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                                                      action="{{ route('promotion.update', ':promotionId') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                                                    <!--begin::Input group-->
                                                                    <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                                        <!--begin::Label-->
                                                                        <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                                            <span class="required">Employee Name</span>
                                                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                                                               aria-label="Specify a card holder's name"
                                                                               data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                                                                        </label>
                                                                        <!--end::Label-->
                                                                        <select class="form-select form-select-sm" id="" name="employee_id" required>
                                                                            <option selected disabled>Select Employee</option>
                                @foreach ($allEmployees as $key => $value)
                                                                            <option value="{{ $value->id }}">{{ $value->employee_name }}
                                                                            </option>
                                @endforeach
                                                                        </select>
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
                                                                        <select class="form-select form-select-sm" id="changeBranchEdit" name="branch_id" required>
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
                                                                        <select class="form-select form-select-sm" id="changeDepartmentEdit" name="department_id"
                                                                                required>
                                                                            <option selected disabled>Select Employee Department</option>
                                @foreach ($allDepartment as $key => $value)
                                                                            <option value="{{ $key }}">{{ $value }}
                                                                            </option>
                                @endforeach
                                                                        </select>
                                                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                                                    </div>




                                                                    <div class="card-body pt-0 pb-5">
                                                                        <label class="required form-label">Designation</label>
                                                                        <select class="form-select form-select-sm" name="designation_id" required>
                                                                            <option selected disabled>Select Employee Designation</option>
                                @foreach ($allEmpDesignation as $key => $value)
                                                                            <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                                        <!--begin::Label-->
                                                                        <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                                            <span class="required">Promotion Date</span>
                                                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                                                               aria-label="Specify a card holder's name"
                                                                               data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                                                                        </label>
                                                                        <!--end::Label-->
                                                                        <input type="date" class="form-control form-control-sm" name="start_date">
                                                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                                                    </div>
                                                                    <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                                        <!--begin::Label-->
                                                                        <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                                            <span class="required">Description</span>
                                                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                                                               aria-label="Specify a card holder's name"
                                                                               data-bs-original-title="Specify a card holder's name" data-kt-initialized="1"></i>
                                                                        </label>
                                                                        <!--end::Label-->
                                                                        <input type="text" class="form-control form-control-sm" name="description">
                                                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                                                    </div>


                                                                    <div class="text-center pt-15">
                                                                        <!-- <button type="reset" id="kt_modal_new_card_cancel"
                                                                                                                                                                                                        class="btn btn-light me-3">Discard</button> -->

                                                                        <a href="{{ route('promotion.index') }}" id="kt_modal_new_card_cancel"
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
                                                    // Promotion 
                                                    function openEditModalPromotion(button) {

                                                        const promotionData = JSON.parse(button.getAttribute('data-employeePromotion'));

                                                        const formAction = $('#kt_modal_edit_card').find('#kt_modal_edit_card_form_promotion').attr('action');
                                                        const promotionId = promotionData.id;
                                                        const updatedFormAction = formAction.replace(':promotionId', promotionId);
                                                        $('#kt_modal_edit_card').find('#kt_modal_edit_card_form_promotion').attr('action', updatedFormAction);

                                                        $('#kt_modal_edit_card').find('select[name="employee_id"]').val(promotionData.employee_id);
                                                        $('#kt_modal_edit_card').find('select[name="branch_id"]').val(promotionData.branch_id);
                                                        $('#kt_modal_edit_card').find('select[name="department_id"]').val(promotionData.department_id);
                                                        $('#kt_modal_edit_card').find('select[name="designation_id"]').val(promotionData.designation_id);
                                                        $('#kt_modal_edit_card').find('input[name="start_date"]').val(promotionData.start_date);
                                                        $('#kt_modal_edit_card').find('input[name="description"]').val(promotionData.description);

                                                        $('#kt_modal_edit_card').modal('show');
                                                    }
                                                </script>
                                                <script type="text/javascript">
                                                    $('#employee_id').on('change', function() {
                                                        var employeeId = $(this).val();
                                                        var url = '{{ route('single_employee_promotion', ':employeeId ') }}';
                                                        url = url.replace(':employeeId', employeeId);
                                                        if (employeeId) {
                                                            $.ajax({
                                                                url: url,
                                                                type: "GET",
                                                                data: {
                                                                    "_token": "{{ csrf_token() }}"
                                                                },
                                                                dataType: "json",
                                                                success: function(data) {
                                                                    console.log(data);

                                                                    $('.branch').empty();
                                                                    $('.department').empty();
                                                                    $('.designation').empty();
                                                                    $('#lastPromotionDate').empty();

                                                                    if (data.length > 0) {
                                                                        $.each(data, function(key, value) {
                                                                            let d = data[key];
                                                                            let promotionDate = 'Not Available';
                                                                            if(value.last_promotion){
                                                                                promotionDate = value.last_promotion.start_date;
                                                                            }
                                                                            console.log(d);
                                                                            $('#changeBranch').val(value.emp_branch.id);
                                                                            $('#changeDesignation').val(value.emp_designation.id);
                                                                            $('#changeDepartment').val(value.emp_department.id);

                                                                            $('.branch').append(
                                                                                `<option value="` + d.id + `">` + d.emp_branch
                                                                                .branch_name + `</option>`
                                                                            );
                                                                            $('.department').append(
                                                                                `<option value="` + d.id + `">` + d.emp_department
                                                                                .department_name + `</option>`
                                                                            );
                                                                            $('.designation').append(
                                                                                `<option value="` + d.id + `">` + d.emp_designation
                                                                                .designation_name + `</option>`
                                                                            );
                                                                            $('#lastPromotionDate').val(promotionDate);
                                                                        });
                                                                    } else {
                                                                        $('#jsdataerror').text('No records found');
                                                                        console.log('No records found');
                                                                    }

                                                                }
                                                            });
                                                        } else {
                                                            $('#jsdataerror').text('Not Found');
                                                        }

                                                    });
                                                    $('#changeBranch').on('change', function() {
                                                        var branchID = $(this).val();
                                                        console.log('branchID', branchID);
                                                        var url = '{{ route('branchDepartment', ':branchID ') }}';
                                                        url = url.replace(':branchID', branchID);

                                                        console.log('branchID', url);
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
                                                                        `<option selected disabled>Select Employee Department</option>`
                                                                    );
                                                                    if (data) {
                                                                        $.each(data, function(key, value) {
                                                                            $('#changeDepartment').append(
                                                                                `<option value="` + value.id + `">` + value
                                                                                .department_name + `</option>`
                                                                            );
                                                                        });
                                                                    } else {
                                                                        $('#jsdataerror').text('Not Found');
                                                                    }
                                                                }
                                                            });
                                                        } else {
                                                            $('#changeDepartment').empty().append('<option selected disabled>Select Department</option>');
                                                            $('#jsdataerror').text('Not Found');
                                                        }
                                                    });
                                                        
                                                    $('#changeBranchEdit').on('change', function() {
                                                        var branchID = $(this).val();
                                                        console.log('branchID', branchID);
                                                        var url = '{{ route('branchDepartment', ':branchID ') }}';
                                                        url = url.replace(':branchID', branchID);

                                                        console.log('branchID', url);
                                                        if (branchID) {
                                                            $.ajax({
                                                                url: url,
                                                                type: "GET",
                                                                data: {
                                                                    "_token": "{{ csrf_token() }}"
                                                                },
                                                                dataType: "json",
                                                                success: function(data) {
                                                                    $('#changeDepartmentEdit').empty();

                                                                    $('#changeDepartmentEdit').append(
                                                                        `<option selected disabled>Select Employee Department</option>`
                                                                    );
                                                                    if (data) {
                                                                        $.each(data, function(key, value) {
                                                                            $('#changeDepartmentEdit').append(
                                                                                `<option value="` + value.id + `">` + value
                                                                                .department_name + `</option>`
                                                                            );
                                                                        });
                                                                    } else {
                                                                        $('#jsdataerror').text('Not Found');
                                                                    }
                                                                }
                                                            });
                                                        } else {
                                                            $('#changeDepartmentEdit').empty().append('<option selected disabled>Select Department</option>');
                                                            $('#jsdataerror').text('Not Found');
                                                        }
                                                    });

                                                //for div hide show
                                                    function myFunction() {
                                                        $('#addPromotion').removeClass('d-none');
                                                        $('#promotion_list').addClass('d-none');
                                                    }
                                                //for div hide show    
                                                </script>
