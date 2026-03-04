<x-default-layout>
    <style>
        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }
    </style>
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container  d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h3>Payslip</h3>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->

    @if ($errors->any())
        <div class="col-xl-12">
            <div class="card ">
                <div class="card-body pb-0 pt-3 px-3">
                    <div class="alert alert-danger pb-0">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @can('create payslips')
        <div class="col-xl-12 py-5">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('payslips.store') }}" method="POST" id="payslip_form">
                        @csrf
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                <div class="btn-box">
                                    <label for="selectedmonth" class="form-label">{{ __('Select Month') }}</label>
                                    <input type="month" class="form-control form-control-sm select" name="selectedmonth"
                                        id="selectedmonth" placeholder="YYYY-MM">
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2 mt-5">
                                <button type="submit" class="btn btn-sm btn-primary mt-3" data-bs-toggle="tooltip"
                                    title="{{ __('payslip') }}" data-original-title="{{ __('payslip') }}">
                                    {{ __('Generate Payslip') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="">
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
                        <div class="w-150px">
                            <select class="form-select p-2 form-select-solid" data-control="select2"
                                data-hide-search="true" data-placeholder="Status"
                                data-kt-ecommerce-order-filter="status">
                                <option value="all"> Status</option>
                                <option value="Active">Active</option>
                                <option value="Disabled">Disabled</option>
                            </select>
                        </div>
                        <!--end::Filter-->
                        <!--begin::Export dropdown-->
                        <button type="button" class="btn btn-sm btn-light-primary" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr078.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.3" x="12.75" y="4.25" width="12" height="2"
                                        rx="1" transform="rotate(90 12.75 4.25)" fill="currentColor" />
                                    <path
                                        d="M12.0573 6.11875L13.5203 7.87435C13.9121 8.34457 14.6232 8.37683 15.056 7.94401C15.4457 7.5543 15.4641 6.92836 15.0979 6.51643L12.4974 3.59084C12.0996 3.14332 11.4004 3.14332 11.0026 3.59084L8.40206 6.51643C8.0359 6.92836 8.0543 7.5543 8.44401 7.94401C8.87683 8.37683 9.58785 8.34458 9.9797 7.87435L11.4427 6.11875C11.6026 5.92684 11.8974 5.92684 12.0573 6.11875Z"
                                        fill="currentColor" />
                                    <path opacity="0.3"
                                        d="M18.75 8.25H17.75C17.1977 8.25 16.75 8.69772 16.75 9.25C16.75 9.80228 17.1977 10.25 17.75 10.25C18.3023 10.25 18.75 10.6977 18.75 11.25V18.25C18.75 18.8023 18.3023 19.25 17.75 19.25H5.75C5.19772 19.25 4.75 18.8023 4.75 18.25V11.25C4.75 10.6977 5.19771 10.25 5.75 10.25C6.30229 10.25 6.75 9.80228 6.75 9.25C6.75 8.69772 6.30229 8.25 5.75 8.25H4.75C3.64543 8.25 2.75 9.14543 2.75 10.25V19.25C2.75 20.3546 3.64543 21.25 4.75 21.25H18.75C19.8546 21.25 20.75 20.3546 20.75 19.25V10.25C20.75 9.14543 19.8546 8.25 18.75 8.25Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->Export Report
                        </button>
                        <!--begin::Menu-->
                        <div id="kt_ecommerce_report_customer_orders_export_menu"
                            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4"
                            data-kt-menu="true">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-ecommerce-export="copy">Copy to
                                    clipboard</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-ecommerce-export="excel">Export as
                                    Excel</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-ecommerce-export="csv">Export as
                                    CSV</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-ecommerce-export="pdf">Export as
                                    PDF</a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                        <!--end::Menu-->
                        <!--end::Export dropdown-->
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
                                <th class="min-w-50px"> Employee Id</th>
                                <th class="min-w-100px"> Name</th>
                                <th class="min-w-100px"> Payroll Type</th>
                                <th class="min-w-100px">Salary</th>
                                <th class="min-w-100px">Net Salary</th>
                                <th class="min-w-100px">Status</th>
                                <th class="text-start min-w-100px">Action</th>

                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-700">

                            @foreach ($payslips as $payslip)
                                <tr>
                                    <td>
                                        {{-- <a>ID#{{ $payslip->employee_id }} #{{ $payslip->employees->employee_code }}</a> --}}
                                        <a>{{ $payslip->employees->employee_code }}</a>
                                    </td>
                                    <td>
                                        <a>{{ $payslip->employees->employee_name }}</a>
                                    </td>
                                    <td>
                                        {{-- <a>{{ $payslip->employeesallary->paysliptype->name }}</a> --}}
                                    </td>
                                    <td>
                                        <a>{{ $payslip->basic_salary }}</a>
                                    </td>
                                    <td>
                                        <a>{{ $payslip->net_payble }}</a>
                                    </td>
                                    <td>
                                        <div class="badge badge-light-{{ $payslip->status == 1 ? 'success' : 'danger' }}">
                                            {{ $payslip->status == 1 ? 'Paid' : 'UnPaid' }}</div>
                                    </td>

                                    <td class="text-start">
                                        <div class="d-flex bd-highlight">
                                            {{-- <button type="button" class="btn btn-sm btn-warning px-3 py-1 me-5"
                                                data-employee-id="{{ $payslip->employee_id }}"
                                                onclick="openModalPayslip(this)">Payslip All
                                            </button> --}}
                                            <button type="button" class="btn btn-sm btn-warning px-3 py-1 me-5"
                                                data-employee-id="{{ $payslip->employee_id }}"
                                                onclick="openModalPayslipNew(this)">Payslip
                                            </button>
                                            
                                            @can('write payslips')
                                                @if ($payslip->status == 0)
                                                    <form method="POST"
                                                        action="{{ route('payslips.update', $payslip->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-success px-3 py-1 me-5">
                                                            Click to Paid
                                                        </button>
                                                    </form>
                                                @endif

                                                <form method="POST"
                                                    action="{{ route('payslips.destroy', $payslip->id) }}">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button type="submit" class="btn btn-sm btn-danger px-3 py-1">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
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


    <div class="modal fade" id="kt_modal_card_payslip" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bolder">Payslip Details</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- begin::Wrapper-->
                    <div class="mw-lg-950px mx-auto w-100">
                        <!--begin::Body-->
                        <div class="pb-0">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column gap-7 gap-md-10">
                                <!--begin::Billing & shipping-->
                                <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                                    <div class="flex-root d-flex flex-column">
                                        <span class="fs-6">
                                            Name : <span id="employee_id"></span><br>
                                            {{-- Name : <span id="employee_name"></span><br> --}}
                                            Position : Employee<br>
                                            Salary Date : <span id="salary_month"></span>
                                        </span>
                                    </div>

                                    <div class="flex-root d-flex flex-column text-end">
                                        <h4 class="fw-bolder text-gray-800 fs-2qx pe-5 text-uppercase">Payslip
                                        </h4>
                                        <p class="fw-bolder text-gray-800 fs-3 pe-5 text-uppercase" id="status">
                                        </p>
                                    </div>
                                </div>
                                <!--end::Billing & shipping-->

                                <!--begin:Order summary-->
                                <div class="d-flex justify-content-between flex-column">
                                    <!--begin::Table-->
                                    <div class="table-responsive border-bottom mb-9">
                                        <table
                                            class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                                            <thead>
                                                <tr class="text-start fs-7 text-uppercase gs-0">
                                                    <th class="min-w-175px pb-2">Earning</th>
                                                    <th class="min-w-70px text-end pb-2">Title</th>
                                                    <th class="min-w-80px text-end pb-2">Type</th>
                                                    <th class="min-w-100px text-end pb-2">Amount</th>
                                                </tr>
                                            </thead>

                                            <tbody class="fw-semibold text-gray-600 pb-15 mb-15">
                                                <tr>
                                                    <td> Basic Salary </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end" id="basic_salary"></td>
                                                </tr>
                                                <tr>
                                                    <td> Allowance </td>
                                                    <td class="text-end"> Transportation </td>
                                                    <td class="text-end"> Fixed </td>
                                                    <td class="text-end" id="allowance"></td>
                                                </tr>
                                                <tr>
                                                    <td> Commission </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end" id="commission"></td>
                                                </tr>
                                                <tr>
                                                    <td> Other Payment </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end" id="other_payment"></td>
                                                </tr>
                                                <tr>
                                                    <td> Overtime </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end" id="overtime"></td>
                                                </tr>
                                                {{-- <tr>
                                                    <td colspan="3" class="fs-6 text-dark fw-bold text-end">
                                                        Grand Total
                                                    </td>
                                                    <td class="text-dark fs-6 fw-bolder text-end">
                                                        $269.00
                                                    </td>
                                                </tr> --}}
                                            </tbody>
                                        </table>
                                        <table
                                            class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0 mt-10">
                                            <thead>
                                                <tr class="text-start fs-7 text-uppercase gs-0">
                                                    <th class="min-w-175px pb-2">Deduction</th>
                                                    <th class="min-w-70px text-end pb-2">Title</th>
                                                    <th class="min-w-80px text-end pb-2">Type</th>
                                                    <th class="min-w-100px text-end pb-2">Amount</th>
                                                </tr>
                                            </thead>

                                            <tbody class="fw-semibold text-gray-600 mb-15">
                                                <tr>
                                                    <td> Loan </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end" id="loan"></td>
                                                </tr>
                                                {{-- <tr>
                                                    <td colspan="3" class="fs-6 text-dark fw-bold text-end">
                                                        Grand Total
                                                    </td>
                                                    <td class="text-dark fs-6 fw-bolder text-end">
                                                        $269.00
                                                    </td>
                                                </tr> --}}
                                            </tbody>
                                        </table>
                                        <table
                                            class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0 mt-10">
                                            <tbody class="fw-semibold text-gray-600">
                                                <tr>
                                                    <td colspan="3" class="fs-6 text-dark fw-bold text-end">
                                                        Net Payble
                                                    </td>
                                                    <td class="text-dark fs-6 fw-bolder text-end" id="net_payble">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--end::Table-->
                                </div>
                                <!--end:Order summary-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Body-->
                        <!-- begin::Footer-->
                        <div class="d-flex flex-stack flex-wrap mt-lg-0 pt-0">
                            <!-- begin::Actions-->
                            <div class="my-1 me-5">
                                <!-- begin::Pint-->
                                <button type="button" class="btn btn-success my-1 me-12"
                                    onclick="window.print();">Print Invoice</button>
                                <!-- end::Pint-->

                                <!-- begin::Download-->
                                {{-- <button type="button" class="btn btn-light-success my-1">Download</button> --}}
                                <!-- end::Download-->
                            </div>
                            <!-- end::Actions-->
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                        </div>
                        <!-- end::Footer-->
                    </div>
                    <!-- end::Wrapper-->

                </div>

            </div>
        </div>
    </div>


    {{-- All Data --}}
    <div class="modal fade" id="kt_modal_card_all_payslip" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bolder">All Payslip Details</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- begin::Wrapper-->
                    <div class="mw-lg-950px mx-auto w-100">
                        <!--begin::Body-->
                        <div class="pb-0">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column gap-7 gap-md-10">
                                <!--begin::Billing & shipping-->
                                <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                                    <div class="flex-root d-flex flex-column">
                                        <span class="fs-6">
                                            Name : <span id="employee_id"></span><br>
                                            {{-- Name : <span id="employee_name"></span><br> --}}
                                            Position : Employee<br>
                                            Salary Date : <span id="salary_month"></span>
                                        </span>
                                    </div>

                                    <div class="flex-root d-flex flex-column text-end">
                                        <h4 class="fw-bolder text-gray-800 fs-2qx pe-5 text-uppercase">Payslip
                                        </h4>
                                        <p class="fw-bolder text-gray-800 fs-3 pe-5 text-uppercase" id="status">
                                        </p>
                                    </div>
                                </div>
                                <!--end::Billing & shipping-->

                                <!--begin:Order summary-->
                                <div class="d-flex justify-content-between flex-column">
                                    <!--begin::Table-->
                                    <div class="table-responsive border-bottom mb-9">
                                        <table
                                            class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                                            <thead>
                                                <tr class="text-start fs-7 text-uppercase gs-0">
                                                    <th class="min-w-175px pb-2">Earning</th>
                                                    <th class="min-w-70px text-end pb-2">Title</th>
                                                    <th class="min-w-80px text-end pb-2">Type</th>
                                                    <th class="min-w-100px text-end pb-2">Amount</th>
                                                </tr>
                                            </thead>

                                            <tbody class="fw-semibold text-gray-600 pb-15 mb-15">
                                                <tr>
                                                    <td> Basic Salary </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end" id="basic_salary"></td>
                                                </tr>
                                                <tr>
                                                    <td> Allowance </td>
                                                    <td class="text-end"> Transportation </td>
                                                    <td class="text-end"> Fixed </td>
                                                    <td class="text-end" id="allowance"></td>
                                                </tr>
                                                <tr>
                                                    <td> Commission </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end" id="commission"></td>
                                                </tr>
                                                <tr>
                                                    <td> Other Payment </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end" id="other_payment"></td>
                                                </tr>
                                                <tr>
                                                    <td> Overtime </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end" id="overtime"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="fs-6 text-dark fw-bold text-end">
                                                        Grand Total
                                                    </td>
                                                    <td class="text-dark fs-6 fw-bolder text-end">
                                                        $269.00
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table
                                            class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0 mt-10">
                                            <thead>
                                                <tr class="text-start fs-7 text-uppercase gs-0">
                                                    <th class="min-w-175px pb-2">Deduction</th>
                                                    <th class="min-w-70px text-end pb-2">Title</th>
                                                    <th class="min-w-80px text-end pb-2">Type</th>
                                                    <th class="min-w-100px text-end pb-2">Amount</th>
                                                </tr>
                                            </thead>

                                            <tbody class="fw-semibold text-gray-600 mb-15">
                                                <tr>
                                                    <td> Loan </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end"> - </td>
                                                    <td class="text-end" id="loan"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="fs-6 text-dark fw-bold text-end">
                                                        Grand Total
                                                    </td>
                                                    <td class="text-dark fs-6 fw-bolder text-end">
                                                        $269.00
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table
                                            class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0 mt-10">
                                            <tbody class="fw-semibold text-gray-600">
                                                <tr>
                                                    <td colspan="3" class="fs-6 text-dark fw-bold text-end">
                                                        Net Payble
                                                    </td>
                                                    <td class="text-dark fs-6 fw-bolder text-end" id="net_payble">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--end::Table-->
                                </div>
                                <!--end:Order summary-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Body-->
                        <!-- begin::Footer-->
                        <div class="d-flex flex-stack flex-wrap mt-lg-0 pt-0">
                            <!-- begin::Actions-->
                            <div class="my-1 me-5">
                                <!-- begin::Pint-->
                                <button type="button" class="btn btn-success my-1 me-12"
                                    onclick="window.print();">Print Invoice</button>
                                <!-- end::Pint-->

                                <!-- begin::Download-->
                                <button type="button" class="btn btn-light-success my-1">Download</button>
                                <!-- end::Download-->
                            </div>
                            <!-- end::Actions-->
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                        </div>
                        <!-- end::Footer-->
                    </div>
                    <!-- end::Wrapper-->

                </div>

            </div>
        </div>
    </div>


</x-default-layout>

<script type="text/javascript">

    // All Data
    function openModalPayslip(button) {
        const employeeId = button.getAttribute('data-employee-id');

        console.log(employeeId);
        // Make an AJAX request to retrieve payslip data based on the employee_id
        $.ajax({
            url: 'get-all-payslip/' + employeeId, // Replace with the appropriate URL to fetch payslip data
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const data = response.employees;
                const data2 = response.employeeDetails;

                // Do something with the data
                console.log('Data 1:', data);
                console.log('Data 2:', data2);

                console.log(data);
                // Update modal content with payslip details
                document.getElementById('employee_id').textContent = data.employee_id;
                //document.getElementById('employee_name').textContent = data.employees.employee_name;;
                document.getElementById('basic_salary').textContent = data.basic_salary;
                document.getElementById('allowance').textContent = data.allowance;
                document.getElementById('commission').textContent = data.commission;
                document.getElementById('other_payment').textContent = data.other_payment;
                document.getElementById('overtime').textContent = data.overtime;
                document.getElementById('loan').textContent = data.loan;
                document.getElementById('net_payble').textContent = data.net_payble;
                document.getElementById('salary_month').textContent = data.salary_month;
                document.getElementById('status').textContent = data.status == 1 ? 'Paid' : 'UnPaid';

                // Show the modal
                $('#kt_modal_card_all_payslip').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    function openModalPayslipNew(button) {
        const employeeId = button.getAttribute('data-employee-id');

        console.log(employeeId);
        // Make an AJAX request to retrieve payslip data based on the employee_id
        $.ajax({
            url: 'get-payslip/' + employeeId, // Replace with the appropriate URL to fetch payslip data
            method: 'GET',
            dataType: 'json',
            success: function(data) {

                console.log(data);
                // Update modal content with payslip details
                document.getElementById('employee_id').textContent = data.employee_id;
                //document.getElementById('employee_name').textContent = data.employees.employee_name;;
                document.getElementById('basic_salary').textContent = data.basic_salary;
                document.getElementById('allowance').textContent = data.allowance;
                document.getElementById('commission').textContent = data.commission;
                document.getElementById('other_payment').textContent = data.other_payment;
                document.getElementById('overtime').textContent = data.overtime;
                document.getElementById('loan').textContent = data.loan;
                document.getElementById('net_payble').textContent = data.net_payble;
                document.getElementById('salary_month').textContent = data.salary_month;
                document.getElementById('status').textContent = data.status == 1 ? 'Paid' : 'UnPaid';

                // Show the modal
                $('#kt_modal_card_payslip').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
</script>
