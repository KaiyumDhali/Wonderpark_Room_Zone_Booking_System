<x-default-layout>
    <style>
        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }
    </style>
    <div class="col-xl-12 py-5">
        <div class="card card-flush h-lg-100" id="kt_contacts_main">
            <div class="card-header py-0" id="kt_chat_contacts_header">
                <div class="card-title">
                    <!--begin::Svg Icon | path: icons/duotune/communication/com005.svg-->
                    <span class="svg-icon svg-icon-1 me-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z"
                                fill="currentColor"></path>
                            <path opacity="0.3"
                                d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z"
                                fill="currentColor"></path>
                        </svg>
                    </span>
                    <h3>Ledger Details</h3>
                </div>
            </div>
            <div class="card-body py-0">
                <div class="row row-cols-1 row-cols-sm-4 rol-cols-md-1 row-cols-lg-4 pb-3">

                    <div class="col">
                        <div class="fv-row mb-0">
                            <label class="fs-6 fw-semibold form-label mt-0">
                                <span>Employee Name</span>
                            </label>
                            <input type="text" class="form-control form-control-solid form-control-sm"
                                name="" readonly value="{{ $employee->employee_name }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="fv-row mb-0">
                            <label class="fs-6 fw-semibold form-label mt-0">
                                <span>Mobile Number</span>
                            </label>
                            <input type="text" class="form-control form-control-solid form-control-sm"
                                name="" readonly value="{{ $employee->mobile }}">
                        </div>
                    </div>
                    {{-- <div class="col">
                        <div class="fv-row mb-0">
                            <label class="fs-6 fw-semibold form-label mt-0">
                                <span>Employee Salary</span>
                            </label>
                            <input type="text" class="form-control form-control-solid form-control-sm"
                                name="" readonly value="{{ $employee->mobile }}">
                        </div>
                    </div> --}}
                    <div class="col">
                        <div class="fv-row mb-0">
                            <label class="fs-6 fw-semibold form-label mt-0">
                                <span>Due Amount</span>
                            </label>
                            <input type="text" class="form-control form-control-solid form-control-sm"
                                    name="is_previous_due" id="is_previous_due" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-flush" id="ledger_list_div_s">
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Table-->
            <div id="kt_ecommerce_category_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="kt_ecommerce_category_table">
                        <thead>
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Ledger ID </th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Employee Name </th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Ledger Date</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Debit</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Credit</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Balance</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700" id="ledger_list_s">
                            <?php $totalDue = 0; ?>
                            @foreach($employeeDetails as $employee)
                            <tr>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary fw-bold mb-1" >{{ $employee->ledger_id }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary  fw-bold mb-1" >{{ $employee->employee_name }} </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary  fw-bold mb-1" >{{ $employee->ledger_date }} </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary  fw-bold mb-1" >{{ $employee->debit }} </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary  fw-bold mb-1" >{{ $employee->credit }} </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary  fw-bold mb-1">{{ $totalDue = $totalDue + ($employee->debit - $employee->credit ) }} </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a class="text-gray-800 text-hover-primary  fw-bold mb-1" >{{ $employee->remarks }} </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-default-layout>

<script type="text/javascript">

    $(document).ready(function() {
        var totalDue = <?php echo $totalDue; ?>;
        $("#is_previous_due").val(totalDue);
    });

</script>
