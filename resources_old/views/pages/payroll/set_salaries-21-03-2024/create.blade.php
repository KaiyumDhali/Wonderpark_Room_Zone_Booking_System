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
            <!--begin::Salary Row-->
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <!--begin::Col Salary-->
                        <div class="col-md-12 mb-5 mb-xl-7">
                            <div class="card card-flush">
                                <!--begin::Card header-->
                                <div class="card-header align-items-center p-3 pt-0">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <h4>Employee Basic Salary</h4>
                                        </div>
                                    </div>
                                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                        @if ($employeeSalary)
                                            <a href="#"
                                                class="btn btn-sm btn-flex btn-warning text-dark py-2 px-3 "
                                                data-bs-toggle="modal" data-bs-target="#kt_modal_new_card_sallary">
                                                Increment
                                            </a>
                                        @else
                                            <a href="#"
                                                class="btn btn-sm btn-flex btn-warning text-dark py-2 px-3 "
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
                                            class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5">
                                            <!--begin::Table head-- id="kt_ecommerce_report_customer_orders_table"--->
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
                                                            <a>{{ $employeeSalary->employee->employee_name }}</a>
                                                        </td>
                                                        <td>
                                                            <a>{{ $employeeSalary->paysliptype->name }}</a>
                                                        </td>
                                                        <td>
                                                            <a
                                                                id="employeeSalaryAmount">{{ $employeeSalary->amount }}</a>
                                                        </td>
                                                        <td>
                                                            <a>{{ $employeeSalary->start_date }}</a>
                                                        </td>
                                                        <td>
                                                            <a>{{ $employeeSalary->user->name }} </a>
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
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="6" y="17.3137" width="16"
                                                            height="2" rx="1"
                                                            transform="rotate(-45 6 17.3137)" fill="currentColor">
                                                        </rect>
                                                        <rect x="7.41422" y="6" width="16" height="2"
                                                            rx="1" transform="rotate(45 7.41422 6)"
                                                            fill="currentColor"></rect>
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
                                                        @foreach ($payslipTypes as $key => $value)
                                                            <option value="{{ $key }}">{{ $value }}
                                                            </option>
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
                            <div class="modal fade" id="kt_modal_edit_card_salary" tabindex="-1"
                                aria-hidden="true">
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
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="6" y="17.3137" width="16"
                                                            height="2" rx="1"
                                                            transform="rotate(-45 6 17.3137)" fill="currentColor">
                                                        </rect>
                                                        <rect x="7.41422" y="6" width="16" height="2"
                                                            rx="1" Loan 2 Loan Title 2 Percentage 1500
                                                            2023-06-01 transform="rotate(45 7.41422 6)"
                                                            fill="currentColor"></rect>
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
                                                        @foreach ($payslipTypes as $key => $value)
                                                            <option value="{{ $key }}">{{ $value }}
                                                            </option>
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
                    </div>
                </div>
            </div>
            <!--end::Salary Row-->

            <!--begin::Income & Deduction Row-->
            <div class="row">
                <!--begin::Col Income-->
                <div class="col-md-6">
                    <div class="col-md-12 mb-5 mb-xl-7">
                        <!--begin::All Income List-->
                        <div class="card card-flush">
                            <!--begin::Card Income header-->
                            <div class="card-header align-items-center p-3 pt-0">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <h4>Income</h4>
                                    </div>
                                </div>
                                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                    <a href="#"
                                        class="btn btn-sm btn-flex btn-primary py-2 px-3 {{ empty($employeeSalary->amount) ? 'disabled' : '' }}"
                                        data-bs-toggle="modal" data-bs-target="#kt_modal_new_card_income">
                                        Add New
                                    </a>
                                </div>
                            </div>
                            <!--end::Card Income header-->

                            <!--begin::Card Income body-->
                            <div class="card-body pt-0 p-3">
                                <!--begin::Income List Table-->
                                <div class="table-responsive">
                                    <table
                                        class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                                        id="kt_ecommerce_report_customer_orders_table">
                                        <!--begin::Table head-->
                                        <thead>
                                            <!--begin::Table row-->
                                            <tr class="text-start fs-7 text-uppercase gs-0">
                                                <th class="min-w-100px"> Employee </th>
                                                <th class="min-w-100px">Income Head </th>
                                                {{-- <th class="min-w-100px"> Title</th> --}}
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
                                            @foreach ($incomes as $income)
                                                <tr>
                                                    <td>
                                                        <a>{{ $income->employee->employee_name }}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{ $income->incomehead->name }}</a>
                                                    </td>
                                                    {{-- <td>
                                                        <a>{{ $income->title }}</a>
                                                    </td> --}}
                                                    <td>
                                                        <a>{{ $income->type == 1 ? 'Fixed' : 'Percentage' }}</a>
                                                    </td>
                                                    <td>
                                                        @if ($income->percentage > 0)
                                                            <a>{{ $income->percentage }} %</a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a>{{ $income->amount }}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{ $income->created_by }} </a>
                                                    </td>
                                                    <td class="text-end">
                                                        <form method="POST"
                                                            action="{{ route('income.destroy', $income->id) }}">
                                                            {{ csrf_field() }}
                                                            {{ method_field('DELETE') }}

                                                            <button type="button" class="btn btn-sm btn-success p-2"
                                                                data-income="{{ json_encode($income) }}"
                                                                onclick="openEditModalIncome(this)">
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
                                <!--end::Income List Table-->
                            </div>
                            <!--end::Card Income body-->
                        </div>
                        <!--end::All Income List-->

                        <!--begin::Income Modal - Create App-->
                        <div class="modal fade" id="kt_modal_new_card_income" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                <!--begin::Modal content-->
                                <div class="modal-content">
                                    <!--begin::Modal header-->
                                    <div class="modal-header">
                                        <!--begin::Modal title-->
                                        <h2>Create Income</h2>
                                        <!--end::Modal title-->
                                        <!--begin::Close-->
                                        <div class="btn btn-sm btn-icon btn-active-color-primary"
                                            data-bs-dismiss="modal">
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                            <span class="svg-icon svg-icon-1">
                                                <svg width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.5" x="6" y="17.3137" width="16"
                                                        height="2" rx="1"
                                                        transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                                    <rect x="7.41422" y="6" width="16" height="2"
                                                        rx="1" transform="rotate(45 7.41422 6)"
                                                        fill="currentColor"></rect>
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
                                            action="{{ route('income.store') }}" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" id="" name="employee_id"
                                                value="{{ $employee_id }}">

                                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Income Head</label>
                                                <select class="form-select form-select-sm" name="income_head">
                                                    <option value="">--Select Option--</option>
                                                    @foreach ($incomeHeads as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                <label
                                                    class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                    <span class="required">Title </span>
                                                </label>
                                                <input type="text" class="form-control form-control-sm"
                                                       name="title">
                                            </div> --}}

                                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Type</label>
                                                <select class="form-select form-select-sm" name="type"
                                                    id="percentageType">
                                                    <option value="">--Select Type--</option>
                                                    <option value="1" selected>Fixed</option>
                                                    <option value="2">Percentage</option>
                                                </select>
                                            </div>

                                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container d-none"
                                                id="percentageInputContainer">
                                                <label
                                                    class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                    <span class="required">Percentage </span>
                                                </label>
                                                <input type="number" class="form-control form-control-sm"
                                                    min="1" max="100" name="percentage"
                                                    id="percentageInput">
                                            </div>

                                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container"
                                                id="amountInputContainer">
                                                <label
                                                    class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                    <span class="required">Amount </span>
                                                </label>
                                                <input type="number" class="form-control form-control-sm"
                                                    name="amount" id="amountInput">
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
                        <!--end::Income Modal - Create App-->

                        <!--begin::Income Modal - Edit App-->
                        <div class="modal fade" id="kt_modal_edit_card_income" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                <!--begin::Modal content-->
                                <div class="modal-content">
                                    <!--begin::Modal header-->
                                    <div class="modal-header">
                                        <!--begin::Modal title-->
                                        <h2>Edit Income</h2>
                                        <!--end::Modal title-->
                                        <!--begin::Close-->
                                        <div class="btn btn-sm btn-icon btn-active-color-primary"
                                            data-bs-dismiss="modal">
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                            <span class="svg-icon svg-icon-1">
                                                <svg width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.5" x="6" y="17.3137" width="16"
                                                        height="2" rx="1"
                                                        transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                                    <rect x="7.41422" y="6" width="16" height="2"
                                                        rx="1" transform="rotate(45 7.41422 6)"
                                                        fill="currentColor"></rect>
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
                                        <form id="kt_modal_edit_card_form_income"
                                            class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                            action="{{ route('income.update', ':incomeId') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" id="" name="employee_id"
                                                value="{{ $employee_id }}">

                                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Income Head</label>
                                                <select class="form-select form-select-sm" name="income_head">
                                                    <option value="">--Select Option--</option>
                                                    @foreach ($incomeHeads as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                <label
                                                    class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                    <span class="required">Title </span>
                                                </label>
                                                <input type="text" class="form-control form-control-sm"
                                                       name="title">
                                            </div> --}}

                                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Type</label>
                                                <select class="form-select form-select-sm" name="type"
                                                    id="percentageTypeEdit">
                                                    <option value="">--Select Type--</option>
                                                    <option value="1" selected>Fixed</option>
                                                    <option value="2">Percentage</option>
                                                </select>
                                            </div>

                                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container d-none"
                                                id="percentageInputContainerEdit">
                                                <label
                                                    class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                    <span class="required">Percentage </span>
                                                </label>
                                                <input type="number" class="form-control form-control-sm"
                                                    min="1" max="100" name="percentage"
                                                    id="percentageInputEdit">
                                            </div>

                                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                <label
                                                    class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                    <span class="required">Amount </span>
                                                </label>
                                                <input type="number" class="form-control form-control-sm"
                                                    name="amount" id="amountInputEdit">
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
                        <!--end::Income Modal - Edit App-->
                    </div>
                </div>
                <!--end::Col Income-->

                <!--begin::Col Deduction-->
                <div class="col-md-6">
                    <div class="col-md-12 mb-5 mb-xl-7">
                        <div class="card card-flush">
                            <!--begin::Card header-->
                            <div class="card-header align-items-center p-3 pt-0">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <h4>Deduction</h4>
                                    </div>
                                </div>
                                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                    <a href="#"
                                        class="btn btn-sm btn-flex btn-primary py-2 px-3 {{ empty($employeeSalary->amount) ? 'disabled' : '' }}"
                                        data-bs-toggle="modal" data-bs-target="#kt_modal_new_card_Deduction">
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
                                                <th class="min-w-50px">{{ __('Employee') }}</th>
                                                <th class="min-w-150px">{{ __('Deduction Head') }}</th>
                                                <th class="min-w-50px">{{ __('Type') }}</th>
                                                <th class="min-w-50px">{{ __('Percentage') }}</th>
                                                <th class="min-w-50px">{{ __('Amount') }}</th>
                                                <th class="min-w-100px">{{ __('Created By') }}</th>
                                                <th class="min-w-100px text-end">{{ __('Action') }}</th>
                                            </tr>
                                            <!--end::Table row-->
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody class="fw-semibold text-gray-700">
                                            @foreach ($deductions as $deduction)
                                                <tr>
                                                    <td>
                                                        <a>{{ $deduction->employee->employee_name }}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{ $deduction->deductionhead->name }}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{ $deduction->type == 1 ? 'Fixed' : 'Percentage' }}</a>
                                                    </td>
                                                    <td>
                                                        @if ($deduction->percentage > 0)
                                                            <a>{{ $deduction->percentage }} %</a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a>{{ $deduction->amount }}</a>
                                                    </td>
                                                    <td>
                                                        <a>{{ $deduction->created_by }} </a>
                                                    </td>
                                                    <td class="text-end">
                                                        <form method="POST"
                                                            action="{{ route('deduction.destroy', $deduction->id) }}">
                                                            {{ csrf_field() }}
                                                            {{ method_field('DELETE') }}

                                                            <button type="button" class="btn btn-sm btn-success p-2"
                                                                data-deduction="{{ json_encode($deduction) }}"
                                                                onclick="openEditModalDeduction(this)">
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
                        <div class="modal fade" id="kt_modal_new_card_Deduction" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                <!--begin::Modal content-->
                                <div class="modal-content">
                                    <!--begin::Modal header-->
                                    <div class="modal-header">
                                        <!--begin::Modal title-->
                                        <h2>Create Deduction</h2>
                                        <!--end::Modal title-->
                                        <!--begin::Close-->
                                        <div class="btn btn-sm btn-icon btn-active-color-primary"
                                            data-bs-dismiss="modal">
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                            <span class="svg-icon svg-icon-1">
                                                <svg width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.5" x="6" y="17.3137" width="16"
                                                        height="2" rx="1"
                                                        transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                                    <rect x="7.41422" y="6" width="16" height="2"
                                                        rx="1" transform="rotate(45 7.41422 6)"
                                                        fill="currentColor"></rect>
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
                                            action="{{ route('deduction.store') }}" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" id="" name="employee_id"
                                                value="{{ $employee_id }}">



                                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Deduction Head</label>
                                                <select class="form-select form-select-sm" name="deduction_head">
                                                    <option value="">--Select Type--</option>
                                                    @foreach ($deductionHeads as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Type</label>
                                                <select class="form-select form-select-sm" name="type"
                                                    id="deductionPercentageType">
                                                    <option value="">--Select Type--</option>
                                                    <option value="1" selected>Fixed</option>
                                                    <option value="2">Percentage</option>
                                                </select>
                                            </div>

                                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container d-none"
                                                id="deductionPercentageInputContainer">
                                                <label
                                                    class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                    <span class="required">Percentage </span>
                                                </label>
                                                <input type="number" class="form-control form-control-sm"
                                                    min="1" max="100" name="percentage"
                                                    id="deductionPercentageInput">
                                            </div>

                                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                <label
                                                    class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                    <span class="required">Amount </span>
                                                </label>
                                                <input type="number" class="form-control form-control-sm"
                                                    name="amount" id="deductionAmountInput">
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
                        <div class="modal fade" id="kt_modal_edit_card_deduction" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                <!--begin::Modal content-->
                                <div class="modal-content">
                                    <!--begin::Modal header-->
                                    <div class="modal-header">
                                        <!--begin::Modal title-->
                                        <h2>Edit Deduction</h2>
                                        <!--end::Modal title-->
                                        <!--begin::Close-->
                                        <div class="btn btn-sm btn-icon btn-active-color-primary"
                                            data-bs-dismiss="modal">
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                            <span class="svg-icon svg-icon-1">
                                                <svg width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.5" x="6" y="17.3137" width="16"
                                                        height="2" rx="1"
                                                        transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                                    <rect x="7.41422" y="6" width="16" height="2"
                                                        rx="1" transform="rotate(45 7.41422 6)"
                                                        fill="currentColor"></rect>
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
                                        <form id="kt_modal_edit_card_form_deduction"
                                            class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                            action="{{ route('deduction.update', ':deductionId') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" id="" name="employee_id"
                                                value="{{ $employee_id }}">



                                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Deduction Head</label>
                                                <select class="form-select form-select-sm" name="deduction_head">
                                                    <option value="">--Select Type--</option>
                                                    @foreach ($deductionHeads as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Type</label>
                                                <select class="form-select form-select-sm" name="type"
                                                    id="deductionPercentageTypeEdit">
                                                    <option value="">--Select Type--</option>
                                                    <option value="1" selected>Fixed</option>
                                                    <option value="2">Percentage</option>
                                                </select>
                                            </div>

                                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container d-none"
                                                id="deductionPercentageInputContainerEdit">
                                                <label
                                                    class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                    <span class="required">Percentage </span>
                                                </label>
                                                <input type="number" class="form-control form-control-sm"
                                                    min="1" max="100" name="percentage"
                                                    id="deductionPercentageInputEdit">
                                            </div>

                                            <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                                <label
                                                    class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                    <span class="required">Amount </span>
                                                </label>
                                                <input type="number" class="form-control form-control-sm"
                                                    name="amount" id="deductionAmountInputEdit">
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
                </div>
                <!--end::Col Deduction-->
            </div>
            <!--end::Income & Deduction Row-->
        </div>
        <!--end::Content container-->
    </div>

    <!--end::Content-->
    <div class="d-flex justify-content-end">
        <!--begin::Button-->
        <a class="btn btn-sm btn-success" href="{{ route('set_salaries.index') }}"
            id="kt_ecommerce_add_product_cancel" class="btn btn-sm btn-success me-5">Back Set Salaries</a>
        <!--end::Button-->
    </div>

</x-default-layout>

<script type="text/javascript">
    // Salary Start
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
    // Salary end

    // Income Start 
    function openEditModalIncome(button) {
        const incomeData = JSON.parse(button.getAttribute('data-income'));
        const percentage = parseFloat(incomeData.percentage);
        //console.log(percentage);
        if (percentage > 0) {
            $('#percentageInputContainerEdit').removeClass('d-none');
            $('#amountInputEdit').prop('readonly', true);
        } else {
            $('#percentageInputContainerEdit').addClass('d-none');
            $('#amountInputEdit').prop('readonly', false);
        }
        const formAction = $('#kt_modal_edit_card_income').find('#kt_modal_edit_card_form_income').attr('action');
        const incomeId = incomeData.id;
        const updatedFormAction = formAction.replace(':incomeId', incomeId);
        $('#kt_modal_edit_card_income').find('#kt_modal_edit_card_form_income').attr('action', updatedFormAction);
        $('#kt_modal_edit_card_income').find('select[name="income_head"]').val(incomeData.income_head);
        // $('#kt_modal_edit_card_income').find('input[name="title"]').val(incomeData.title);
        $('#kt_modal_edit_card_income').find('select[name="type"]').val(incomeData.type);
        $('#kt_modal_edit_card_income').find('input[name="percentage"]').val(incomeData.percentage);
        $('#kt_modal_edit_card_income').find('input[name="amount"]').val(incomeData.amount);
        $('#kt_modal_edit_card_income').modal('show');
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
        var employeeSalaryAmount = $('#employeeSalaryAmount').text();
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
        var employeeSalaryAmount = $('#employeeSalaryAmount').text();
        const baseAmount = parseFloat(employeeSalaryAmount);
        const calculatedAmount = (percentageValue / 100) * baseAmount;
        $('#amountInputEdit').val(calculatedAmount.toFixed(2));
    });
    // Income End 





    // Deduction Start
    function openEditModalDeduction(button) {

        const deductionData = JSON.parse(button.getAttribute('data-deduction'));

        const percentage = parseFloat(deductionData.percentage);
        //console.log(percentage);
        if (percentage > 0) {
            $('#deductionPercentageInputContainerEdit').removeClass('d-none');
            $('#deductionAmountInputEdit').prop('readonly', true);
        } else {
            $('#deductionPercentageInputContainerEdit').addClass('d-none');
            $('#deductionAmountInputEdit').prop('readonly', false);
        }

        const formAction = $('#kt_modal_edit_card_deduction').find('#kt_modal_edit_card_form_deduction').attr('action');
        const deductionId = deductionData.id;
        const updatedFormAction = formAction.replace(':deductionId', deductionId);

        $('#kt_modal_edit_card_deduction').find('#kt_modal_edit_card_form_deduction').attr('action', updatedFormAction);

        $('#kt_modal_edit_card_deduction').find('select[name="deduction_head"]').val(deductionData.deduction_head);
        $('#kt_modal_edit_card_deduction').find('select[name="type"]').val(deductionData.type);
        $('#kt_modal_edit_card_deduction').find('input[name="percentage"]').val(deductionData.percentage);
        $('#kt_modal_edit_card_deduction').find('input[name="amount"]').val(deductionData.amount);


        $('#kt_modal_edit_card_deduction').modal('show');
    }
    // Add Modal percentage
    $('#deductionPercentageType').on('change', function() {
        if ($(this).val() === '2') {
            $('#deductionPercentageInputContainer').removeClass('d-none');
            $('#deductionAmountInput').prop('readonly', true);
        } else {
            $('#deductionPercentageInputContainer').addClass('d-none');

            $('#deductionAmountInput').prop('readonly', false);
        }
    });
    $('#deductionPercentageInput').on('change', function() {
        const percentageValue = $(this).val();
        var employeeSalaryAmount = $('#employeeSalaryAmount').text();
        const baseAmount = parseFloat(employeeSalaryAmount);
        const calculatedAmount = (percentageValue / 100) * baseAmount;
        $('#deductionAmountInput').val(calculatedAmount.toFixed(2));
    });
    // Edit Modal percentage
    $('#deductionPercentageTypeEdit').on('change', function() {
        if ($(this).val() === '2') {
            $('#deductionPercentageInputContainerEdit').removeClass('d-none');
            $('#deductionAmountInputEdit').prop('readonly', true);
        } else {
            $('#deductionPercentageInputContainerEdit').addClass('d-none');
            $('#deductionAmountInputEdit').prop('readonly', false);
        }
    });
    $('#deductionPercentageInputEdit').on('change', function() {
        const percentageValue = $(this).val();
        var employeeSalaryAmount = $('#employeeSalaryAmount').text();
        const baseAmount = parseFloat(employeeSalaryAmount);
        const calculatedAmount = (percentageValue / 100) * baseAmount;
        $('#deductionAmountInputEdit').val(calculatedAmount.toFixed(2));
    });
    // Deduction End
</script>
