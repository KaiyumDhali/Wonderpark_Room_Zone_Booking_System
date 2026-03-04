<x-default-layout>
    <style>
    .table> :not(caption)>*>* {
        padding: 0.3rem !important;
    }
    </style>
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h3>Create Set Salaries</h3>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->

    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <!--begin::Col Salary-->
                        <div class="col-md-12 mb-5 mb-xl-7">
                            <div class="card card-flush">
                                <!--begin::Card header-->
                                <div class="card-header align-items-center p-3 pt-0">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <h4>Employee Salary</h4>
                                        </div>
                                    </div>
                                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                        @if ($employeeSalary)
                                        <a href="#" class="btn btn-sm btn-flex btn-warning text-dark py-2 px-3 "
                                            data-bs-toggle="modal" data-bs-target="#kt_modal_new_card_sallary">
                                            Increment
                                        </a>
                                        @else
                                        <a href="#" class="btn btn-sm btn-flex btn-warning text-dark py-2 px-3 "
                                            data-bs-toggle="modal" data-bs-target="#kt_modal_new_card_sallary">
                                            Add Salary
                                        </a>
                                        @endif
                                    </div>
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-0 p-3">
                                    <!--begin::Table-->
                                    <div class="table-responsive">
                                        <table
                                            class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                                            id="kt_ecommerce_report_customer_orders_table">
                                            <!--begin::Table head-->
                                            <thead>
                                                <!--begin::Table row-->
                                                <tr class="text-start fs-7 text-uppercase gs-0">
                                                    <th class="min-w-100px"> Employee </th>
                                                    <th class="min-w-100px"> Payslip Type </th>
                                                    <th class="min-w-100px"> Amount</th>
                                                    <th class="min-w-100px"> Start Date </th>
                                                    <th class="min-w-100px"> Created By</th>
                                                    <th class="min-w-100px text-end"> Action</th>
                                                </tr>
                                                <!--end::Table row-->
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody class="fw-semibold text-gray-700">
                                                @if ($employeeSalary)
                                                <tr>
                                                    <td>
                                                        <a>{{$employeeSalary->employee->employee_name}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$employeeSalary->paysliptype->name}}</a>
                                                    </td>
                                                    <td>
                                                        <a id="employeeSalaryAmount">{{$employeeSalary->amount}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$employeeSalary->start_date}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$employeeSalary->user->first_name}}
                                                            {{$employeeSalary->user->last_name}}</a>
                                                    </td>
                                                    <td class="text-end">
                                                        <button type="button" class="btn btn-sm btn-success p-2"
                                                            data-employeeSalary="{{ json_encode($employeeSalary) }}"
                                                            onclick="openEditModalSalary(this)">
                                                            <i class="fa fa-pencil text-white px-2"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endif
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                    </div>
                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--begin::Modal - Create App-->
                            <div class="modal fade" id="kt_modal_new_card_sallary" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <!--begin::Modal content-->
                                    <div class="modal-content">
                                        <!--begin::Modal header-->
                                        <div class="modal-header">
                                            <!--begin::Modal title-->
                                            <h2>Set Basic Sallary</h2>
                                            <!--end::Modal title-->
                                            <!--begin::Close-->
                                            <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                data-bs-dismiss="modal">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                <span class="svg-icon svg-icon-1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                                            rx="1" transform="rotate(-45 6 17.3137)"
                                                            fill="currentColor"></rect>
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
                                            <form id="kt_modal_new_card_form"
                                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                                action="{{ route('employee_salaries.store') }}"
                                                enctype="multipart/form-data">
                                                @csrf

                                                <input type="hidden" id="" name="employee_id"
                                                    value="{{ $employee_id }}">

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Start Date </span>
                                                    </label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        name="start_date">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="required form-label">Payslip Type</label>
                                                    <select class="form-select form-select-sm" name="payslip_type">
                                                        <option value="">--Select Status--</option>
                                                        @foreach($payslipTypes as $key => $value)
                                                        <option value="{{ $key }}">{{ $value}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Salary </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm"
                                                        name="amount">
                                                </div>


                                                <div class="text-center pt-10">
                                                    <button type="reset" class="btn btn-sm btn-light me-3"
                                                        data-bs-dismiss="modal">Discard</button>

                                                    <button type="submit" id="kt_modal_new_card_submit"
                                                        class="btn btn-sm btn-primary">
                                                        <span class="indicator-label">Submit</span>
                                                        <span class="indicator-progress">Please wait...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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
                            <!--begin::Modal edit - Create App-->
                            <div class="modal fade" id="kt_modal_edit_card_salary" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <!--begin::Modal content-->
                                    <div class="modal-content">
                                        <!--begin::Modal header-->
                                        <div class="modal-header">
                                            <!--begin::Modal title-->
                                            <h2>Edit Basic Sallary</h2>
                                            <!--end::Modal title-->
                                            <!--begin::Close-->
                                            <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                data-bs-dismiss="modal">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                <span class="svg-icon svg-icon-1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                                            rx="1" transform="rotate(-45 6 17.3137)"
                                                            fill="currentColor"></rect>
                                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" Loan 2 Loan
                                                            Title 2 Percentage 1500 2023-06-01
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
                                            <form id="kt_modal_edit_card_form_salary"
                                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                                action="{{ route('employee_salaries.update', ':employeeSalaryId') }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" id="" name="employee_id"
                                                    value="{{ $employee_id }}">

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Start Date </span>
                                                    </label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        name="start_date">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="required form-label">Payslip Type</label>
                                                    <select class="form-select form-select-sm" name="payslip_type">
                                                        <option value="">--Select Payslip--</option>
                                                        @foreach($payslipTypes as $key => $value)
                                                        <option value="{{ $key }}">{{ $value}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Salary </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm"
                                                        name="amount">
                                                </div>

                                                <div class="text-center pt-10">
                                                    <button type="reset" class="btn btn-sm btn-light me-3"
                                                        data-bs-dismiss="modal">Discard</button>

                                                    <button type="submit" id="kt_modal_new_card_submit"
                                                        class="btn btn-sm btn-primary">
                                                        <span class="indicator-label">Submit</span>
                                                        <span class="indicator-progress">Please wait...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                    </button>
                                                </div>
                                            </form>
                                            <!--end::Form-->
                                        </div>
                                        <!--end::Modal body-->
                                    </div>
                                    <!--end::Modal content-->
                                </div>
                            </div>
                            <!--end::Modal edit- Create App-->
                        </div>
                        <!--end::Col Salary-->
                        <!--begin::Col Allowance-->
                        <div class="col-md-12 mb-5 mb-xl-7">
                            <div class="card card-flush">
                                <!--begin::Card header-->
                                <div class="card-header align-items-center p-3 pt-0">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <h4>Allowance</h4>
                                        </div>
                                    </div>
                                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                        <a href="#" class="btn btn-sm btn-flex btn-primary py-2 px-3 {{empty($employeeSalary->amount) ? "disabled" : ""}}"
                                            data-bs-toggle="modal" data-bs-target="#kt_modal_new_card_allowance">
                                            Add New
                                        </a>
                                    </div>
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-0 p-3">
                                    <!--begin::Table-->
                                    <div class="table-responsive">
                                        <table
                                            class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                                            id="kt_ecommerce_report_customer_orders_table">
                                            <!--begin::Table head-->
                                            <thead>
                                                <!--begin::Table row-->
                                                <tr class="text-start fs-7 text-uppercase gs-0">
                                                    <th class="min-w-100px"> Employee </th>
                                                    <th class="min-w-100px"> Option </th>
                                                    <th class="min-w-100px"> Title</th>
                                                    <th class="min-w-50px"> Type</th>
                                                    <th class="min-w-100px"> Percentage</th>
                                                    <th class="min-w-50px"> Amount</th>
                                                    <th class="min-w-100px"> Created By</th>
                                                    <th class="text-end min-w-90px"> Action</th>
                                                </tr>
                                                <!--end::Table row-->
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody class="fw-semibold text-gray-700">
                                                @foreach($allowances as $allowance)
                                                <tr>
                                                    <td>
                                                        <a>{{$allowance->employee->employee_name}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$allowance->allowanceoption->name}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$allowance->title}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{ $allowance->type==1?'Fixed':'Percentage' }}</a>
                                                    </td>
                                                    <td>
                                                        @if($allowance->percentage > 0)
                                                        <a>{{$allowance->percentage}} %</a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a>{{$allowance->amount}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$allowance->user->first_name}}
                                                            {{$allowance->user->last_name}}</a>
                                                    </td>
                                                    <td class="text-end">
                                                        <form method="POST"
                                                            action="{{route('allowance.destroy', $allowance->id)}}">
                                                            {{csrf_field()}}
                                                            {{ method_field('DELETE') }}

                                                            <button type="button" class="btn btn-sm btn-success p-2"
                                                                data-allowance="{{ json_encode($allowance) }}"
                                                                onclick="openEditModalAllowance(this)">
                                                                <i class="fa fa-pencil text-white px-2"></i>
                                                            </button>

                                                            <button type="submit" class="btn btn-sm btn-danger p-2">
                                                                <i class="fa fa-trash text-white px-2"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                    </div>
                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--begin::Modal - Create App-->
                            <div class="modal fade" id="kt_modal_new_card_allowance" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <!--begin::Modal content-->
                                    <div class="modal-content">
                                        <!--begin::Modal header-->
                                        <div class="modal-header">
                                            <!--begin::Modal title-->
                                            <h2>Create Allowance</h2>
                                            <!--end::Modal title-->
                                            <!--begin::Close-->
                                            <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                data-bs-dismiss="modal">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                <span class="svg-icon svg-icon-1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                                            rx="1" transform="rotate(-45 6 17.3137)"
                                                            fill="currentColor"></rect>
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
                                            <form id="kt_modal_new_card_form"
                                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                                action="{{ route('allowance.store') }}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" id="" name="employee_id"
                                                    value="{{ $employee_id }}">

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="required form-label">Allowance Option</label>
                                                    <select class="form-select form-select-sm" name="allowance_option">
                                                        <option value="">--Select Option--</option>
                                                        @foreach($allowanceOptions as $key => $value)
                                                        <option value="{{ $key }}">{{ $value}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Title </span>
                                                    </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="title">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="required form-label">Type</label>
                                                    <select class="form-select form-select-sm" name="type" id="percentageType">
                                                        <option value="">--Select Type--</option>
                                                        <option value="1" selected>Fixed</option>
                                                        <option value="2">Percentage</option>
                                                    </select>
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container d-none" id="percentageInputContainer">
                                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Percentage </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm" min="1" max="100" name="percentage" id="percentageInput">
                                                </div>
                                                
                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container" id="amountInputContainer">
                                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Amount </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm" name="amount" id="amountInput">
                                                </div>

                                                <div class="text-center pt-10">
                                                    <button type="reset" class="btn btn-sm btn-light me-3"
                                                        data-bs-dismiss="modal">Discard</button>

                                                    <button type="submit" id="kt_modal_new_card_submit"
                                                        class="btn btn-sm btn-primary">
                                                        <span class="indicator-label">Submit</span>
                                                        <span class="indicator-progress">Please wait...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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

                            <!--begin::Modal - Edit App-->
                            <div class="modal fade" id="kt_modal_edit_card_allowance" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <!--begin::Modal content-->
                                    <div class="modal-content">
                                        <!--begin::Modal header-->
                                        <div class="modal-header">
                                            <!--begin::Modal title-->
                                            <h2>Edit Allowance</h2>
                                            <!--end::Modal title-->
                                            <!--begin::Close-->
                                            <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                data-bs-dismiss="modal">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                <span class="svg-icon svg-icon-1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                                            rx="1" transform="rotate(-45 6 17.3137)"
                                                            fill="currentColor"></rect>
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
                                            <form id="kt_modal_edit_card_form_allowance"
                                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                                action="{{ route('allowance.update', ':allowanceId') }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" id="" name="employee_id"
                                                    value="{{ $employee_id }}">

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="required form-label">Allowance Option</label>
                                                    <select class="form-select form-select-sm" name="allowance_option">
                                                        <option value="">--Select Option--</option>
                                                        @foreach($allowanceOptions as $key => $value)
                                                        <option value="{{ $key }}">{{ $value}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Title </span>
                                                    </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="title">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="required form-label">Type</label>
                                                    <select class="form-select form-select-sm" name="type" id="percentageTypeEdit">
                                                        <option value="">--Select Type--</option>
                                                        <option value="1" selected>Fixed</option>
                                                        <option value="2">Percentage</option>
                                                    </select>
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container d-none" id="percentageInputContainerEdit">
                                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Percentage </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm" min="1" max="100" name="percentage" id="percentageInputEdit">
                                                </div>
                                                
                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Amount </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm" name="amount" id="amountInputEdit">
                                                </div>

                                                <div class="text-center pt-10">
                                                    <button type="reset" class="btn btn-sm btn-light me-3"
                                                        data-bs-dismiss="modal">Discard</button>

                                                    <button type="submit" id="kt_modal_new_card_submit"
                                                        class="btn btn-sm btn-primary">
                                                        <span class="indicator-label">Submit</span>
                                                        <span class="indicator-progress">Please wait...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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
                            <!--end::Modal - Edit App-->
                        </div>
                        <!--end::Col Allowance-->
                        <!--begin::Col Commission-->
                        <div class="col-md-12 mb-5 mb-xl-7">
                            <div class="card card-flush">
                                <!--begin::Card header-->
                                <div class="card-header align-items-center p-3 pt-0">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <h4>Commission</h4>
                                        </div>
                                    </div>
                                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                        <a href="#" class="btn btn-sm btn-flex btn-primary py-2 px-3 {{empty($employeeSalary->amount) ? "disabled" : ""}}"
                                            data-bs-toggle="modal" data-bs-target="#kt_modal_new_card_commission">
                                            Add New
                                        </a>
                                    </div>
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-0 p-3">
                                    <!--begin::Table-->
                                    <div class="table-responsive">
                                        <table
                                            class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                                            id="kt_ecommerce_report_customer_orders_table">
                                            <!--begin::Table head-->
                                            <thead>
                                                <!--begin::Table row-->
                                                <tr class="text-start fs-7 text-uppercase gs-0">
                                                    <th class="min-w-100px">{{__('Employee')}}</th>
                                                    <th class="min-w-100px">{{__('Title')}}</th>
                                                    <th class="min-w-100px">{{__('Type')}}</th>
                                                    <th class="min-w-100px">{{__('Percentage')}}</th>
                                                    <th class="min-w-100px">{{__('Amount')}}</th>
                                                    <th class="min-w-100px">{{__('Created By')}}</th>
                                                    <th class="min-w-100px text-end">{{__('Action')}}</th>
                                                </tr>
                                                <!--end::Table row-->
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody class="fw-semibold text-gray-700">
                                                @foreach($commissions as $commission)
                                                <tr>
                                                    <td>
                                                        <a>{{$commission->employee->employee_name}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$commission->title}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{ $commission->type==1?'Fixed':'Percentage' }}</a>
                                                    </td>
                                                    <td>
                                                        @if($commission->percentage > 0)
                                                        <a>{{$commission->percentage}} %</a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a>{{$commission->amount}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$commission->user->first_name}}
                                                            {{$commission->user->last_name}}</a>
                                                    </td>
                                                    <td class="text-end">
                                                        <form method="POST"
                                                            action="{{route('commission.destroy', $commission->id)}}">
                                                            {{csrf_field()}}
                                                            {{ method_field('DELETE') }}
                                                            <button type="button" class="btn btn-sm btn-success p-2"
                                                                data-commission="{{ json_encode($commission) }}"
                                                                onclick="openEditModalCommission(this)">
                                                                <i class="fa fa-pencil text-white px-2"></i>
                                                            </button>
                                                            <button type="submit" class="btn btn-sm btn-danger p-2">
                                                                <i class="fa fa-trash text-white px-2"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                    </div>
                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--begin::Modal - Create App-->
                            <div class="modal fade" id="kt_modal_new_card_commission" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <!--begin::Modal content-->
                                    <div class="modal-content">
                                        <!--begin::Modal header-->
                                        <div class="modal-header">
                                            <!--begin::Modal title-->
                                            <h2>Create Commission</h2>
                                            <!--end::Modal title-->
                                            <!--begin::Close-->
                                            <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                data-bs-dismiss="modal">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                <span class="svg-icon svg-icon-1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                                            rx="1" transform="rotate(-45 6 17.3137)"
                                                            fill="currentColor"></rect>
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
                                            <form id="kt_modal_new_card_form"
                                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                                action="{{ route('commission.store') }}" enctype="multipart/form-data">
                                                @csrf

                                                <input type="hidden" id="" name="employee_id"
                                                    value="{{ $employee_id }}">

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Title </span>
                                                    </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="title">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="required form-label">Type</label>
                                                    <select class="form-select form-select-sm" name="type" id="commissionPercentageType">
                                                        <option value="">--Select Type--</option>
                                                        <option value="1" selected>Fixed</option>
                                                        <option value="2">Percentage</option>
                                                    </select>
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container d-none" id="commissionPercentageInputContainer">
                                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Percentage </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm" min="1" max="100" name="percentage" id="commissionPercentageInput">
                                                </div>
                                                
                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container" id="amountInputContainer">
                                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Amount </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm" name="amount" id="commissionAmountInput">
                                                </div>

                                                <div class="text-center pt-10">
                                                    <button type="reset" class="btn btn-sm btn-light me-3"
                                                        data-bs-dismiss="modal">Discard</button>

                                                    <button type="submit" id="kt_modal_new_card_submit"
                                                        class="btn btn-sm btn-primary">
                                                        <span class="indicator-label">Submit</span>
                                                        <span class="indicator-progress">Please wait...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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
                            <!--begin::Modal - Edit App-->
                            <div class="modal fade" id="kt_modal_edit_card_commission" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <!--begin::Modal content-->
                                    <div class="modal-content">
                                        <!--begin::Modal header-->
                                        <div class="modal-header">
                                            <!--begin::Modal title-->
                                            <h2>Edit Commission</h2>
                                            <!--end::Modal title-->
                                            <!--begin::Close-->
                                            <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                data-bs-dismiss="modal">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                <span class="svg-icon svg-icon-1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                                            rx="1" transform="rotate(-45 6 17.3137)"
                                                            fill="currentColor"></rect>
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
                                            <form id="kt_modal_edit_card_form_commission"
                                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                                action="{{ route('commission.update', ':commissionId') }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" id="" name="employee_id"
                                                    value="{{ $employee_id }}">

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Title </span>
                                                    </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="title">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="required form-label">Type</label>
                                                    <select class="form-select form-select-sm" name="type" id="commissionPercentageTypeEdit">
                                                        <option value="">--Select Type--</option>
                                                        <option value="1" selected>Fixed</option>
                                                        <option value="2">Percentage</option>
                                                    </select>
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container d-none" id="commissionPercentageInputContainerEdit">
                                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Percentage </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm" min="1" max="100" name="percentage" id="commissionPercentageInputEdit">
                                                </div>
                                                
                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Amount </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm" name="amount" id="commissionAmountInputEdit">
                                                </div>

                                                <div class="text-center pt-10">
                                                    <button type="reset" class="btn btn-sm btn-light me-3"
                                                        data-bs-dismiss="modal">Discard</button>

                                                    <button type="submit" id="kt_modal_new_card_submit"
                                                        class="btn btn-sm btn-primary">
                                                        <span class="indicator-label">Submit</span>
                                                        <span class="indicator-progress">Please wait...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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
                            <!--end::Modal - Edit App-->
                        </div>
                        <!--end::Col Commission-->
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <!--begin::Col Loan-->
                        <div class="col-md-12 mb-5 mb-xl-7">
                            <div class="card card-flush">
                                <!--begin::Card header-->
                                <div class="card-header align-items-center p-3 pt-0">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <h4>Loan</h4>
                                        </div>
                                    </div>
                                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                        <a href="#" class="btn btn-sm btn-flex btn-primary py-2 px-3 {{empty($employeeSalary->amount) ? "disabled" : ""}}" data-bs-toggle="modal" data-bs-target="#kt_modal_new_card_Loan">
                                            Add New
                                        </a>
                                    </div>
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-0 p-3">
                                    <!--begin::Table-->
                                    <div class="table-responsive">
                                        <table
                                            class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                                            id="kt_ecommerce_report_customer_orders_table">
                                            <!--begin::Table head-->
                                            <thead>
                                                <!--begin::Table row-->
                                                <tr class="text-start fs-7 text-uppercase gs-0">
                                                    <th class="min-w-50px">{{__('Employee')}}</th>
                                                    <th class="min-w-150px">{{__('Loan Options')}}</th>
                                                    <th class="min-w-150px">{{__('Title')}}</th>
                                                    <th class="min-w-50px">{{__('Type')}}</th>
                                                    <th class="min-w-50px">{{__('Percentage')}}</th>
                                                    <th class="min-w-50px">{{__('Amount')}}</th>
                                                    <th class="min-w-100px">{{__('Start Date')}}</th>
                                                    <th class="min-w-50px">{{__('End Date')}}</th>
                                                    <th class="min-w-150px">{{__('Reason')}}</th>
                                                    <th class="min-w-100px">{{__('Created By')}}</th>
                                                    <th class="min-w-100px text-end">{{__('Action')}}</th>
                                                </tr>
                                                <!--end::Table row-->
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody class="fw-semibold text-gray-700">
                                                @foreach($loans as $loan)
                                                <tr>
                                                    <td>
                                                        <a>{{$loan->employee->employee_name}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$loan->loanoption->name}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$loan->title}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$loan->type==1?'Fixed':'Percentage'}}</a>
                                                    </td>
                                                    <td>
                                                        @if($loan->percentage > 0)
                                                        <a>{{$loan->percentage}} %</a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a>{{$loan->amount}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$loan->start_date}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$loan->end_date}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$loan->reason}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$loan->user->first_name}}
                                                            {{$loan->user->last_name}}</a>
                                                    </td>
                                                    <td class="text-end">
                                                        <form method="POST"
                                                            action="{{route('loan.destroy', $loan->id)}}">
                                                            {{csrf_field()}}
                                                            {{ method_field('DELETE') }}

                                                            <button type="button" class="btn btn-sm btn-success p-2"
                                                                data-loan="{{ json_encode($loan) }}"
                                                                onclick="openEditModalLoan(this)">
                                                                <i class="fa fa-pencil text-white px-2"></i>
                                                            </button>

                                                            <button type="submit" class="btn btn-sm btn-danger p-2">
                                                                <i class="fa fa-trash text-white px-2"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                    </div>
                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--begin::Modal - Create App-->
                            <div class="modal fade" id="kt_modal_new_card_Loan" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <!--begin::Modal content-->
                                    <div class="modal-content">
                                        <!--begin::Modal header-->
                                        <div class="modal-header">
                                            <!--begin::Modal title-->
                                            <h2>Create Loan</h2>
                                            <!--end::Modal title-->
                                            <!--begin::Close-->
                                            <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                data-bs-dismiss="modal">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                <span class="svg-icon svg-icon-1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                                            rx="1" transform="rotate(-45 6 17.3137)"
                                                            fill="currentColor"></rect>
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
                                            <form id="kt_modal_new_card_form"
                                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                                action="{{ route('loan.store') }}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" id="" name="employee_id"
                                                    value="{{ $employee_id }}">

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Title </span>
                                                    </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="title">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="required form-label">Loan Option</label>
                                                    <select class="form-select form-select-sm" name="loan_option">
                                                        <option value="">--Select Type--</option>
                                                        @foreach($loanOptions as $key => $value)
                                                        <option value="{{ $key }}">{{ $value}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="required form-label">Type</label>
                                                    <select class="form-select form-select-sm" name="type" id="loanPercentageType">
                                                        <option value="">--Select Type--</option>
                                                        <option value="1" selected>Fixed</option>
                                                        <option value="2">Percentage</option>
                                                    </select>
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container d-none" id="loanPercentageInputContainer">
                                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Percentage </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm" min="1" max="100" name="percentage" id="loanPercentageInput">
                                                </div>
                                                
                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Amount </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm" name="amount" id="loanAmountInput">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Start Date </span>
                                                    </label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        name="start_date">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">End Date </span>
                                                    </label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        name="end_date">
                                                </div>
                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Loan Reason </span>
                                                    </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="reason">
                                                </div>

                                                <div class="text-center pt-10">
                                                    <button type="reset" class="btn btn-sm btn-light me-3"
                                                        data-bs-dismiss="modal">Discard</button>

                                                    <button type="submit" id="kt_modal_new_card_submit"
                                                        class="btn btn-sm btn-primary">
                                                        <span class="indicator-label">Submit</span>
                                                        <span class="indicator-progress">Please wait...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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
                            <!--begin::Modal - Edit App-->
                            <div class="modal fade" id="kt_modal_edit_card_loan" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <!--begin::Modal content-->
                                    <div class="modal-content">
                                        <!--begin::Modal header-->
                                        <div class="modal-header">
                                            <!--begin::Modal title-->
                                            <h2>Edit Loan</h2>
                                            <!--end::Modal title-->
                                            <!--begin::Close-->
                                            <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                data-bs-dismiss="modal">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                <span class="svg-icon svg-icon-1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                                            rx="1" transform="rotate(-45 6 17.3137)"
                                                            fill="currentColor"></rect>
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
                                            <form id="kt_modal_edit_card_form_loan"
                                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                                action="{{ route('loan.update', ':loanId') }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" id="" name="employee_id"
                                                    value="{{ $employee_id }}">

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Title </span>
                                                    </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="title">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="required form-label">Loan Option</label>
                                                    <select class="form-select form-select-sm" name="loan_option">
                                                        <option value="">--Select Type--</option>
                                                        @foreach($loanOptions as $key => $value)
                                                        <option value="{{ $key }}">{{ $value}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="required form-label">Type</label>
                                                    <select class="form-select form-select-sm" name="type" id="loanPercentageTypeEdit">
                                                        <option value="">--Select Type--</option>
                                                        <option value="1" selected>Fixed</option>
                                                        <option value="2">Percentage</option>
                                                    </select>
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container d-none" id="loanPercentageInputContainerEdit">
                                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Percentage </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm" min="1" max="100" name="percentage" id="loanPercentageInputEdit">
                                                </div>
                                                
                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Amount </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm" name="amount" id="loanAmountInputEdit">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Start Date </span>
                                                    </label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        name="start_date">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">End Date </span>
                                                    </label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        name="end_date">
                                                </div>
                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Loan Reason </span>
                                                    </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="reason">
                                                </div>

                                                <div class="text-center pt-10">
                                                    <button type="reset" class="btn btn-sm btn-light me-3"
                                                        data-bs-dismiss="modal">Discard</button>

                                                    <button type="submit" id="kt_modal_new_card_submit"
                                                        class="btn btn-sm btn-primary">
                                                        <span class="indicator-label">Submit</span>
                                                        <span class="indicator-progress">Please wait...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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
                            <!--end::Modal - Edit App-->
                        </div>
                        <!--end::Col Loan-->
                        <!--begin::Col Other Payment-->
                        <div class="col-md-12 mb-5 mb-xl-7">
                            <div class="card card-flush">
                                <!--begin::Card header-->
                                <div class="card-header align-items-center p-3 pt-0">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <h4>Other Payment</h4>
                                        </div>
                                    </div>
                                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                        <a href="#" class="btn btn-sm btn-flex btn-primary py-2 px-3 {{empty($employeeSalary->amount) ? "disabled" : ""}}"
                                            data-bs-toggle="modal" data-bs-target="#kt_modal_new_card_other_payment">
                                            Add New
                                        </a>
                                    </div>
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-0 p-3">
                                    <!--begin::Table-->
                                    <div class="table-responsive">
                                        <table
                                            class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                                            id="kt_ecommerce_report_customer_orders_table">
                                            <!--begin::Table head-->
                                            <thead>
                                                <!--begin::Table row-->
                                                <tr class="text-start fs-7 text-uppercase gs-0">
                                                    <th class="min-w-100px">{{__('Employee')}}</th>
                                                    <th class="min-w-100px">{{__('Title')}}</th>
                                                    <th class="min-w-100px">{{__('Type')}}</th>
                                                    <th class="min-w-100px">{{__('Percentage')}}</th>
                                                    <th class="min-w-100px">{{__('Amount')}}</th>
                                                    <th class="min-w-100px">{{__('Created By')}}</th>
                                                    <th class="min-w-100px text-end">{{__('Action')}}</th>
                                                </tr>
                                                <!--end::Table row-->
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody class="fw-semibold text-gray-700">
                                                @foreach($otherPayments as $otherPayment)
                                                <tr>
                                                    <td>
                                                        <a>{{$otherPayment->employee->employee_name}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$otherPayment->title}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{ $otherPayment->type==1?'Fixed':'Percentage' }}</a>
                                                    </td>
                                                    <td>
                                                        @if($otherPayment->percentage > 0)
                                                        <a>{{$otherPayment->percentage}} %</a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a>{{$otherPayment->amount}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$otherPayment->user->first_name}}
                                                            {{$otherPayment->user->last_name}}</a>
                                                    </td>
                                                    <td class="text-end">
                                                        <form method="POST"
                                                            action="{{route('other_payment.destroy', $otherPayment->id)}}">
                                                            {{csrf_field()}}
                                                            {{ method_field('DELETE') }}

                                                            <button type="button" class="btn btn-sm btn-success p-2"
                                                                data-otherPayment="{{ json_encode($otherPayment) }}"
                                                                onclick="openEditModalOtherPayment(this)">
                                                                <i class="fa fa-pencil text-white px-2"></i>
                                                            </button>

                                                            <button type="submit" class="btn btn-sm btn-danger p-2">
                                                                <i class="fa fa-trash text-white px-2"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                    </div>
                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--begin::Modal - Create App-->
                            <div class="modal fade" id="kt_modal_new_card_other_payment" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <!--begin::Modal content-->
                                    <div class="modal-content">
                                        <!--begin::Modal header-->
                                        <div class="modal-header">
                                            <!--begin::Modal title-->
                                            <h2>Create Other Payment</h2>
                                            <!--end::Modal title-->
                                            <!--begin::Close-->
                                            <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                data-bs-dismiss="modal">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                <span class="svg-icon svg-icon-1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                                            rx="1" transform="rotate(-45 6 17.3137)"
                                                            fill="currentColor"></rect>
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
                                            <form id="kt_modal_new_card_form"
                                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                                action="{{ route('other_payment.store') }}"
                                                enctype="multipart/form-data">
                                                @csrf

                                                <input type="hidden" id="" name="employee_id"
                                                    value="{{ $employee_id }}">

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Title </span>
                                                    </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="title">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="required form-label">Type</label>
                                                    <select class="form-select form-select-sm" name="type" id="otherPaymentPercentageType">
                                                        <option value="">--Select Type--</option>
                                                        <option value="1" selected>Fixed</option>
                                                        <option value="2">Percentage</option>
                                                    </select>
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container d-none" id="otherPaymentPercentageInputContainer">
                                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Percentage </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm" min="1" max="100" name="percentage" id="otherPaymentPercentageInput">
                                                </div>
                                                
                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Amount </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm" name="amount" id="otherPaymentAmountInput">
                                                </div>

                                                <div class="text-center pt-10">
                                                    <button type="reset" class="btn btn-sm btn-light me-3"
                                                        data-bs-dismiss="modal">Discard</button>

                                                    <button type="submit" id="kt_modal_new_card_submit"
                                                        class="btn btn-sm btn-primary">
                                                        <span class="indicator-label">Submit</span>
                                                        <span class="indicator-progress">Please wait...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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
                            <!--begin::Modal - Edit App-->
                            <div class="modal fade" id="kt_modal_edit_card_other_payment" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <!--begin::Modal content-->
                                    <div class="modal-content">
                                        <!--begin::Modal header-->
                                        <div class="modal-header">
                                            <!--begin::Modal title-->
                                            <h2>Create Other Payment</h2>
                                            <!--end::Modal title-->
                                            <!--begin::Close-->
                                            <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                data-bs-dismiss="modal">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                <span class="svg-icon svg-icon-1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                                            rx="1" transform="rotate(-45 6 17.3137)"
                                                            fill="currentColor"></rect>
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
                                            <form id="kt_modal_edit_card_form_other_payment"
                                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                                action="{{ route('other_payment.update', ':otherPaymentId') }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')

                                                <input type="hidden" id="" name="employee_id"
                                                    value="{{ $employee_id }}">

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Title </span>
                                                    </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="title">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="required form-label">Type</label>
                                                    <select class="form-select form-select-sm" name="type" id="otherPaymentPercentageTypeEdit">
                                                        <option value="">--Select Type--</option>
                                                        <option value="1" selected>Fixed</option>
                                                        <option value="2">Percentage</option>
                                                    </select>
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container d-none" id="otherPaymentPercentageInputContainerEdit">
                                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Percentage </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm" min="1" max="100" name="percentage" id="otherPaymentPercentageInputEdit">
                                                </div>
                                                
                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Amount </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm" name="amount" id="otherPaymentAmountInputEdit">
                                                </div>

                                                <div class="text-center pt-10">
                                                    <button type="reset" class="btn btn-sm btn-light me-3"
                                                        data-bs-dismiss="modal">Discard</button>

                                                    <button type="submit" id="kt_modal_new_card_submit"
                                                        class="btn btn-sm btn-primary">
                                                        <span class="indicator-label">Submit</span>
                                                        <span class="indicator-progress">Please wait...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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
                            <!--end::Modal - Edit App-->
                        </div>
                        <!--end::Col Other Payment-->
                        <!--begin::Col Overtime-->
                        <div class="col-md-12 mb-5 mb-xl-7">
                            <div class="card card-flush">
                                <!--begin::Card header-->
                                <div class="card-header align-items-center p-3 pt-0">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <h4>Overtime</h4>
                                        </div>
                                    </div>
                                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                        <a href="#" class="btn btn-sm btn-flex btn-primary py-2 px-3 {{empty($employeeSalary->amount) ? "disabled" : ""}}"
                                            data-bs-toggle="modal" data-bs-target="#kt_modal_new_card_overtime">
                                            Add New
                                        </a>
                                    </div>
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-0 p-3">
                                    <!--begin::Table-->
                                    <div class="table-responsive">
                                        <table
                                            class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                                            id="kt_ecommerce_report_customer_orders_table">
                                            <!--begin::Table head-->
                                            <thead>
                                                <!--begin::Table row-->
                                                <tr class="text-start fs-7 text-uppercase gs-0">
                                                    <th class="min-w-100px">{{__('Employee Name')}}</th>
                                                    <th class="min-w-100px">{{__('Overtime Title')}}</th>
                                                    <th class="min-w-100px">{{__('Number of days')}}</th>
                                                    <th class="min-w-100px">{{__('Hours')}}</th>
                                                    <th class="min-w-100px">{{__('Rate')}}</th>
                                                    <th class="min-w-100px">{{__('Created By')}}</th>
                                                    <th class="min-w-100px text-end">{{__('Action')}}</th>
                                                </tr>
                                                <!--end::Table row-->
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody class="fw-semibold text-gray-700">
                                                @foreach($overtimes as $overtime)
                                                <tr>
                                                    <td>
                                                        <a>{{$overtime->employee->employee_name}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$overtime->title}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$overtime->number_of_days}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$overtime->hours}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$overtime->rate}}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{$overtime->user->first_name}}
                                                            {{$overtime->user->last_name}}</a>
                                                    </td>
                                                    <td class="text-end">
                                                        <form method="POST"
                                                            action="{{route('overtime.destroy', $overtime->id)}}">
                                                            {{csrf_field()}}
                                                            {{ method_field('DELETE') }}

                                                            <button type="button" class="btn btn-sm btn-success p-2"
                                                                data-overtime="{{ json_encode($overtime) }}"
                                                                onclick="openEditModalOvertime(this)">
                                                                <i class="fa fa-pencil text-white px-2"></i>
                                                            </button>

                                                            <button type="submit" class="btn btn-sm btn-danger p-2">
                                                                <i class="fa fa-trash text-white px-2"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                    </div>
                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->
                            </div>

                            <!--begin::Modal - Create App-->
                            <div class="modal fade" id="kt_modal_new_card_overtime" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <!--begin::Modal content-->
                                    <div class="modal-content">
                                        <!--begin::Modal header-->
                                        <div class="modal-header">
                                            <!--begin::Modal title-->
                                            <h2>Create Overtime</h2>
                                            <!--end::Modal title-->
                                            <!--begin::Close-->
                                            <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                data-bs-dismiss="modal">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                <span class="svg-icon svg-icon-1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                                            rx="1" transform="rotate(-45 6 17.3137)"
                                                            fill="currentColor"></rect>
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
                                            <form id="kt_modal_new_card_form"
                                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                                action="{{ route('overtime.store') }}" enctype="multipart/form-data">
                                                @csrf

                                                <input type="hidden" id="" name="employee_id"
                                                    value="{{ $employee_id }}">

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Title </span>
                                                    </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="title">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required"> Number of days</span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm"
                                                        name="number_of_days">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required"> Hours</span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm"
                                                        name="hours">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Rate </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm"
                                                        name="rate">
                                                </div>

                                                <div class="text-center pt-10">
                                                    <button type="reset" class="btn btn-sm btn-light me-3"
                                                        data-bs-dismiss="modal">Discard</button>

                                                    <button type="submit" id="kt_modal_new_card_submit"
                                                        class="btn btn-sm btn-primary">
                                                        <span class="indicator-label">Submit</span>
                                                        <span class="indicator-progress">Please wait...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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

                            <!--begin::Modal - Edit App-->
                            <div class="modal fade" id="kt_modal_edit_card_overtime" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <!--begin::Modal content-->
                                    <div class="modal-content">
                                        <!--begin::Modal header-->
                                        <div class="modal-header">
                                            <!--begin::Modal title-->
                                            <h2>Create Overtime</h2>
                                            <!--end::Modal title-->
                                            <!--begin::Close-->
                                            <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                data-bs-dismiss="modal">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                <span class="svg-icon svg-icon-1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                                            rx="1" transform="rotate(-45 6 17.3137)"
                                                            fill="currentColor"></rect>
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
                                            <form id="kt_modal_edit_card_form_overtime"
                                                class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                                action="{{ route('overtime.update', ':overtimeId') }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')

                                                <input type="hidden" id="" name="employee_id"
                                                    value="{{ $employee_id }}">

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Title </span>
                                                    </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="title">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required"> Number of days</span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm"
                                                        name="number_of_days">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required"> Hours</span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm"
                                                        name="hours">
                                                </div>

                                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                    <label
                                                        class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                        <span class="required">Rate </span>
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm"
                                                        name="rate">
                                                </div>

                                                <div class="text-center pt-10">
                                                    <button type="reset" class="btn btn-sm btn-light me-3"
                                                        data-bs-dismiss="modal">Discard</button>

                                                    <button type="submit" id="kt_modal_new_card_submit"
                                                        class="btn btn-sm btn-primary">
                                                        <span class="indicator-label">Submit</span>
                                                        <span class="indicator-progress">Please wait...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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
                            <!--end::Modal - Edit App-->
                        </div>
                        <!--end::Col Overtime-->
                    </div>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
    <div class="d-flex justify-content-end">
        <!--begin::Button-->
        <a class="btn btn-sm btn-success" href="{{ route('set_salaries') }}"
            id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">Back Set Salaries</a>
        <!--end::Button-->
    </div>

</x-default-layout>

<script type="text/javascript">

// Salary 
function openEditModalSalary(button) {

    const employeeSalaryData = JSON.parse(button.getAttribute('data-employeeSalary'));

    const formAction = $('#kt_modal_edit_card_salary').find('#kt_modal_edit_card_form_salary').attr('action');
    const employeeSalaryId = employeeSalaryData.id;
    const updatedFormAction = formAction.replace(':employeeSalaryId', employeeSalaryId);
    $('#kt_modal_edit_card_salary').find('#kt_modal_edit_card_form_salary').attr('action', updatedFormAction);

    $('#kt_modal_edit_card_salary').find('input[name="start_date"]').val(employeeSalaryData.start_date);
    $('#kt_modal_edit_card_salary').find('input[name="amount"]').val(employeeSalaryData.amount);
    $('#kt_modal_edit_card_salary').find('select[name="payslip_type"]').val(employeeSalaryData.payslip_type);

    $('#kt_modal_edit_card_salary').modal('show');
}

// Allowance
function openEditModalAllowance(button) {
    
    const allowanceData = JSON.parse(button.getAttribute('data-allowance'));

    const percentage = parseFloat(allowanceData.percentage);
    //console.log(percentage);
    if (percentage > 0) {
        $('#percentageInputContainerEdit').removeClass('d-none');
        $('#amountInputEdit').prop('readonly', true);
    } else {
        $('#percentageInputContainerEdit').addClass('d-none');
        $('#amountInputEdit').prop('readonly', false);
    }

    const formAction = $('#kt_modal_edit_card_allowance').find('#kt_modal_edit_card_form_allowance').attr('action');
    const allowanceId = allowanceData.id;
    const updatedFormAction = formAction.replace(':allowanceId', allowanceId);
    $('#kt_modal_edit_card_allowance').find('#kt_modal_edit_card_form_allowance').attr('action', updatedFormAction);

    $('#kt_modal_edit_card_allowance').find('select[name="allowance_option"]').val(allowanceData.allowance_option);
    $('#kt_modal_edit_card_allowance').find('input[name="title"]').val(allowanceData.title);
    $('#kt_modal_edit_card_allowance').find('select[name="type"]').val(allowanceData.type);
    $('#kt_modal_edit_card_allowance').find('input[name="percentage"]').val(allowanceData.percentage);
    $('#kt_modal_edit_card_allowance').find('input[name="amount"]').val(allowanceData.amount);

    $('#kt_modal_edit_card_allowance').modal('show');

    
}
    // Add Modal percentage
    $('#percentageType').on('change', function() {
        if ($(this).val() === '2') {
            $('#percentageInputContainer').removeClass('d-none');
            $('#amountInput').prop('readonly', true);
        } else {
            $('#percentageInputContainer').addClass('d-none');

            $('#amountInput').prop('readonly', false);
        }
    });
    $('#percentageInput').on('change', function() {
        const percentageValue = $(this).val(); 
        var employeeSalaryAmount =  $('#employeeSalaryAmount').text();
        const baseAmount = parseFloat(employeeSalaryAmount);
        const calculatedAmount = (percentageValue / 100) * baseAmount;
        $('#amountInput').val(calculatedAmount.toFixed(2)); 
    });
    // Edit Modal percentage
    $('#percentageTypeEdit').on('change', function() {
        if ($(this).val() === '2') {
            $('#percentageInputContainerEdit').removeClass('d-none');
            $('#amountInputEdit').prop('readonly', true);
        } else {
            $('#percentageInputContainerEdit').addClass('d-none');
            $('#amountInputEdit').prop('readonly', false);
        }
    });
    $('#percentageInputEdit').on('change', function() {
        const percentageValue = $(this).val(); 
        var employeeSalaryAmount =  $('#employeeSalaryAmount').text();
        const baseAmount = parseFloat(employeeSalaryAmount);
        const calculatedAmount = (percentageValue / 100) * baseAmount;
        $('#amountInputEdit').val(calculatedAmount.toFixed(2)); 
    });

// Commission
function openEditModalCommission(button) {

    const commissionData = JSON.parse(button.getAttribute('data-commission'));

    const percentage = parseFloat(commissionData.percentage);
    //console.log(percentage);
    if (percentage > 0) {
        $('#commissionPercentageInputContainerEdit').removeClass('d-none');
        $('#commissionAmountInputEdit').prop('readonly', true);
    } else {
        $('#commissionPercentageInputContainerEdit').addClass('d-none');
        $('#commissionAmountInputEdit').prop('readonly', false);
    }

    const formAction = $('#kt_modal_edit_card_commission').find('#kt_modal_edit_card_form_commission').attr('action');
    const commissionId = commissionData.id;
    const updatedFormAction = formAction.replace(':commissionId', commissionId);
    $('#kt_modal_edit_card_commission').find('#kt_modal_edit_card_form_commission').attr('action', updatedFormAction);

    $('#kt_modal_edit_card_commission').find('input[name="title"]').val(commissionData.title);
    $('#kt_modal_edit_card_commission').find('select[name="type"]').val(commissionData.type);
    $('#kt_modal_edit_card_commission').find('input[name="percentage"]').val(commissionData.percentage);
    $('#kt_modal_edit_card_commission').find('input[name="amount"]').val(commissionData.amount);

    $('#kt_modal_edit_card_commission').modal('show');
}
// commission
    // Add Modal percentage
    $('#commissionPercentageType').on('change', function() {
        if ($(this).val() === '2') {
            $('#commissionPercentageInputContainer').removeClass('d-none');
            $('#commissionAmountInput').prop('readonly', true);
        } else {
            $('#commissionPercentageInputContainer').addClass('d-none');

            $('#commissionAmountInput').prop('readonly', false);
        }
    });
    $('#commissionPercentageInput').on('change', function() {
        const percentageValue = $(this).val(); 
        var employeeSalaryAmount =  $('#employeeSalaryAmount').text();
        const baseAmount = parseFloat(employeeSalaryAmount);
        const calculatedAmount = (percentageValue / 100) * baseAmount;
        $('#commissionAmountInput').val(calculatedAmount.toFixed(2)); 
    });
    // Edit Modal percentage
    $('#commissionPercentageTypeEdit').on('change', function() {
        if ($(this).val() === '2') {
            $('#commissionPercentageInputContainerEdit').removeClass('d-none');
            $('#commissionAmountInputEdit').prop('readonly', true);
        } else {
            $('#commissionPercentageInputContainerEdit').addClass('d-none');
            $('#commissionAmountInputEdit').prop('readonly', false);
        }
    });
    $('#commissionPercentageInputEdit').on('change', function() {
        const percentageValue = $(this).val(); 
        var employeeSalaryAmount =  $('#employeeSalaryAmount').text();
        const baseAmount = parseFloat(employeeSalaryAmount);
        const calculatedAmount = (percentageValue / 100) * baseAmount;
        $('#commissionAmountInputEdit').val(calculatedAmount.toFixed(2)); 
    });
// Loan
function openEditModalLoan(button) {

    const loanData = JSON.parse(button.getAttribute('data-loan'));

    const percentage = parseFloat(loanData.percentage);
    //console.log(percentage);
    if (percentage > 0) {
        $('#loanPercentageInputContainerEdit').removeClass('d-none');
        $('#loanAmountInputEdit').prop('readonly', true);
    } else {
        $('#loanPercentageInputContainerEdit').addClass('d-none');
        $('#loanAmountInputEdit').prop('readonly', false);
    }

    const formAction = $('#kt_modal_edit_card_loan').find('#kt_modal_edit_card_form_loan').attr('action');
    const loanId = loanData.id;
    const updatedFormAction = formAction.replace(':loanId', loanId);

    $('#kt_modal_edit_card_loan').find('#kt_modal_edit_card_form_loan').attr('action', updatedFormAction);

    $('#kt_modal_edit_card_loan').find('input[name="title"]').val(loanData.title);
    $('#kt_modal_edit_card_loan').find('select[name="loan_option"]').val(loanData.loan_option);
    $('#kt_modal_edit_card_loan').find('select[name="type"]').val(loanData.type);
    $('#kt_modal_edit_card_loan').find('input[name="percentage"]').val(loanData.percentage);
    $('#kt_modal_edit_card_loan').find('input[name="amount"]').val(loanData.amount);
    $('#kt_modal_edit_card_loan').find('input[name="start_date"]').val(loanData.start_date);
    $('#kt_modal_edit_card_loan').find('input[name="end_date"]').val(loanData.end_date);
    $('#kt_modal_edit_card_loan').find('input[name="reason"]').val(loanData.reason);

    $('#kt_modal_edit_card_loan').modal('show');
}
    // loan
    // Add Modal percentage
    $('#loanPercentageType').on('change', function() {
        if ($(this).val() === '2') {
            $('#loanPercentageInputContainer').removeClass('d-none');
            $('#loanAmountInput').prop('readonly', true);
        } else {
            $('#loanPercentageInputContainer').addClass('d-none');

            $('#loanAmountInput').prop('readonly', false);
        }
    });
    $('#loanPercentageInput').on('change', function() {
        const percentageValue = $(this).val(); 
        var employeeSalaryAmount =  $('#employeeSalaryAmount').text();
        const baseAmount = parseFloat(employeeSalaryAmount);
        const calculatedAmount = (percentageValue / 100) * baseAmount;
        $('#loanAmountInput').val(calculatedAmount.toFixed(2)); 
    });
    // Edit Modal percentage
    $('#loanPercentageTypeEdit').on('change', function() {
        if ($(this).val() === '2') {
            $('#loanPercentageInputContainerEdit').removeClass('d-none');
            $('#loanAmountInputEdit').prop('readonly', true);
        } else {
            $('#loanPercentageInputContainerEdit').addClass('d-none');
            $('#loanAmountInputEdit').prop('readonly', false);
        }
    });
    $('#loanPercentageInputEdit').on('change', function() {
        const percentageValue = $(this).val(); 
        var employeeSalaryAmount =  $('#employeeSalaryAmount').text();
        const baseAmount = parseFloat(employeeSalaryAmount);
        const calculatedAmount = (percentageValue / 100) * baseAmount;
        $('#loanAmountInputEdit').val(calculatedAmount.toFixed(2)); 
    });

// Other Payment
function openEditModalOtherPayment(button) {

    const otherPaymentData = JSON.parse(button.getAttribute('data-otherPayment'));

    const percentage = parseFloat(otherPaymentData.percentage);
    //console.log(percentage);
    if (percentage > 0) {
        $('#otherPaymentPercentageInputContainerEdit').removeClass('d-none');
        $('#otherPaymentAmountInputEdit').prop('readonly', true);
    } else {
        $('#otherPaymentPercentageInputContainerEdit').addClass('d-none');
        $('#otherPaymentAmountInputEdit').prop('readonly', false);
    }

    const formAction = $('#kt_modal_edit_card_other_payment').find('#kt_modal_edit_card_form_other_payment').attr(
        'action');
    const otherPaymentId = otherPaymentData.id;
    const updatedFormAction = formAction.replace(':otherPaymentId', otherPaymentId);

    $('#kt_modal_edit_card_other_payment').find('#kt_modal_edit_card_form_other_payment').attr('action',
        updatedFormAction);

    $('#kt_modal_edit_card_other_payment').find('input[name="title"]').val(otherPaymentData.title);
    $('#kt_modal_edit_card_other_payment').find('select[name="type"]').val(otherPaymentData.type);
    $('#kt_modal_edit_card_other_payment').find('input[name="percentage"]').val(otherPaymentData.percentage);
    $('#kt_modal_edit_card_other_payment').find('input[name="amount"]').val(otherPaymentData.amount);

    $('#kt_modal_edit_card_other_payment').modal('show');
}

// Overtime
function openEditModalOvertime(button) {

    const overtimeData = JSON.parse(button.getAttribute('data-overtime'));

    const formAction = $('#kt_modal_edit_card_overtime').find('#kt_modal_edit_card_form_overtime').attr('action');
    const overtimeId = overtimeData.id;
    const updatedFormAction = formAction.replace(':overtimeId', overtimeId);

    $('#kt_modal_edit_card_overtime').find('#kt_modal_edit_card_form_overtime').attr('action', updatedFormAction);

    $('#kt_modal_edit_card_overtime').find('input[name="title"]').val(overtimeData.title);
    $('#kt_modal_edit_card_overtime').find('input[name="number_of_days"]').val(overtimeData.number_of_days);
    $('#kt_modal_edit_card_overtime').find('input[name="hours"]').val(overtimeData.hours);
    $('#kt_modal_edit_card_overtime').find('input[name="rate"]').val(overtimeData.rate);

    $('#kt_modal_edit_card_overtime').modal('show');
}
    
    // Other Payment
    // Add Modal percentage
    $('#otherPaymentPercentageType').on('change', function() {
        if ($(this).val() === '2') {
            $('#otherPaymentPercentageInputContainer').removeClass('d-none');
            $('#otherPaymentAmountInput').prop('readonly', true);
        } else {
            $('#otherPaymentPercentageInputContainer').addClass('d-none');

            $('#otherPaymentAmountInput').prop('readonly', false);
        }
    });
    $('#otherPaymentPercentageInput').on('change', function() {
        const percentageValue = $(this).val(); 
        var employeeSalaryAmount =  $('#employeeSalaryAmount').text();
        const baseAmount = parseFloat(employeeSalaryAmount);
        const calculatedAmount = (percentageValue / 100) * baseAmount;
        $('#otherPaymentAmountInput').val(calculatedAmount.toFixed(2)); 
    });
    // Edit Modal percentage
    $('#otherPaymentPercentageTypeEdit').on('change', function() {
        if ($(this).val() === '2') {
            $('#otherPaymentPercentageInputContainerEdit').removeClass('d-none');
            $('#otherPaymentAmountInputEdit').prop('readonly', true);
        } else {
            $('#otherPaymentPercentageInputContainerEdit').addClass('d-none');
            $('#otherPaymentAmountInputEdit').prop('readonly', false);
        }
    });
    $('#otherPaymentPercentageInputEdit').on('change', function() {
        const percentageValue = $(this).val(); 
        var employeeSalaryAmount =  $('#employeeSalaryAmount').text();
        const baseAmount = parseFloat(employeeSalaryAmount);
        const calculatedAmount = (percentageValue / 100) * baseAmount;
        $('#otherPaymentAmountInputEdit').val(calculatedAmount.toFixed(2)); 
    });

</script>