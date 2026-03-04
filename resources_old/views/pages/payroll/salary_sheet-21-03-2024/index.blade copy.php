<x-default-layout>
    <style>
        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }

        /* .flatpickr-weekdays {
            display: none;
        } */
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
                <h3>Salary Sheet</h3>
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

                    <!--begin::monthly_salaries Card toolbar-->
                    <div class="monthly_salaries">
                        <form action="{{ route('monthly_salaries.store') }}" method="POST" id="payslip_form">
                            @csrf
                            <div class="d-flex ps-7">

                                <div class="card-body p-2">
                                    <label for="selectedmonth" class="form-label">{{ __('Select Month') }}</label>
                                    <input class="form-control form-control-sm" placeholder="Select Month" id="kt_datepicker_3" name="selectedmonth"/>
                                </div>

                                {{-- <div class="card-body p-2">
                                    <label for="selectedmonth" class="form-label">{{ __('Select Month') }}</label>
                                    <input type="month" class="form-control form-control-sm select" name="selectedmonth"
                                        id="selectedmonth" placeholder="YYYY-MM">
                                </div> --}}
                                <div class="card-body p-2 mt-8">
                                    <button type="submit" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                                        title="{{ __('payslip') }}" data-original-title="{{ __('payslip') }}">
                                        {{ __('Generate Salary') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--end::monthly_salaries Card toolbar-->

                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="kt_ecommerce_report_customer_orders_table">
                        <!--begin::Table head-->
                        <thead>
                            <tr style="text-align: center; vertical-align: middle;">
                                <th class="min-w-50px" rowspan="2">ID</th>
                                <th class="min-w-100px" rowspan="2">Employee Id</th>
                                <th class="min-w-100px" rowspan="2">Employee Name</th>
                                <th class="min-w-100px" rowspan="2">Designation</th>
                                <th style="text-align: center; vertical-align: middle;" colspan="6">Income</th>
                                <th style="text-align: center; vertical-align: middle;" colspan="5">Deduction</th>
                            </tr>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0"
                                style="text-align: center; vertical-align: middle;">

                                <th class="min-w-100px">Basic</th>
                                <th class="min-w-100px">House Rent</th>
                                <th class="min-w-100px">Medical</th>
                                <th class="min-w-100px">P/F</th>
                                <th class="min-w-100px">Others</th>
                                <th class="min-w-100px">Total Payable</th>
                                <th class="text-start min-w-100px">Tax</th>
                                <th class="text-start min-w-100px">P/F</th>
                                <th class="text-start min-w-100px">Others</th>
                                <th class="text-start min-w-100px">Net Payable</th>
                                <th class="text-start min-w-100px">Remarks</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-700">

                            {{-- @foreach ($employeeSalary as $employeeSalarys)
                            @endforeach --}}


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

  flatpickr("#kt_datepicker_3", {
    altInput: true,
    altFormat: "F , Y",
    dateFormat: "Y-m",
    onReady: function (selectedDates, dateStr, instance) {
      const monthPicker = instance._input.parentNode.querySelector(".flatpickr-monthDropdown-months");
      const yearPicker = instance._input.parentNode.querySelector(".flatpickr-yearDropdown-years");

      // Remove day picker
      instance.calendarContainer.querySelector(".flatpickr-days").style.display = "none";

      // Populate month dropdown
      monthPicker.innerHTML = Array.from({ length: 12 }, (_, i) => `<option value="${i + 1}"${i + 1 === new Date().getMonth() + 1 ? " selected" : ""}>${instance.l10n.months.longhand[i]}</option>`).join('');

      // Populate year dropdown
      yearPicker.innerHTML = Array.from({ length: new Date().getFullYear() - 1899 }, (_, i) => `<option value="${new Date().getFullYear() - i}">${new Date().getFullYear() - i}</option>`).join('');

      // Set the initial value to the current month and year
      instance.setDate(new Date(), false, instance.config.altFormat);
    },
    onChange: function (selectedDates, dateStr, instance) {
      // Set the selected date to the first day of the month
      const selectedDate = selectedDates[0];
      instance.setDate(new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1), true, instance.config.altFormat);
      instance.redraw();
    }
  });

</script>
