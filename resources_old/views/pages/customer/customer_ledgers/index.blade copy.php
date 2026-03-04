<x-default-layout>
    <style>
        .table > :not(caption) > * > * {
            padding: 0.3rem !important;
        }
    </style>
    <div class="col-xl-12 py-5">
        <!--begin::Contacts-->
        <div class="card card-flush h-lg-100" id="kt_contacts_main">
            <!--begin::Card header-->
            <div class="card-header py-0" id="kt_chat_contacts_header">
                <!--begin::Card title-->
                <div class="card-title">
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
                    <h3>Customer Ledger</h3>
                </div>
            </div>
            <div class="card-body py-0">
                <form id="kt_ecommerce_settings_general_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                    method="POST" action="{{ route('customer_ledgers.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row row-cols-1 row-cols-sm-4 rol-cols-md-1 row-cols-lg-4">
                        <div class="col">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Customer Name</span>
                                    <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                        aria-label="Enter the contact's email."
                                        data-bs-original-title="Enter the contact's email." data-kt-initialized="1"></i>
                                </label>
                                <select id="changeCustomer" class="form-select form-select-sm" name="customer_id">
                                    <option selected disabled >Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                    @endforeach
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                            <!--end::Input group-->
                        </div>
                        <div class="col">
                            <div class="fv-row mb-0">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span>Mobile</span>
                                </label>
                                <input type="text" class="form-control form-control-sm form-control-solid" name="customer_mobile "
                                    id="customer_mobile" value="" readonly>
                            </div>
                        </div>
                        <div class="col">
                            <!--begin::Input group-->
                            <div class="fv-row mb-7 fv-plugins-icon-container">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="">Due Amount</span>
                                    <!--<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" aria-label="Enter the contact's email." data-bs-original-title="Enter the contact's email." data-kt-initialized="1"></i>-->
                                </label>
                                <input type="text" class="form-control form-control-sm form-control-solid" name=""
                                    id="totalDue" value="" readonly>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col">
                            <!--begin::Input group-->
                            <div class="fv-row mb-7 fv-plugins-icon-container">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="">Customer Address</span>
                                    <!--<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" aria-label="Enter the contact's email." data-bs-original-title="Enter the contact's email." data-kt-initialized="1"></i>-->
                                </label>
                                <input type="text" class="form-control form-control-sm form-control-solid" name="customer_address "
                                    id="customer_address" value="" readonly>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    @can('create customer ledger')

                    <div class="row row-cols-1 row-cols-sm-4 rol-cols-md-1 row-cols-lg-4">
                        <div class="col">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-0 required">
                                    <span>Received Amount</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" name="credit"
                                    value="" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span>Remarks</span>
                                </label>
                                <input class="form-control form-control-sm" name="remarks">
                            </div>
                        </div>
                        <div class="col">
                            <div class="fv-row mb-7 mt-8">
                                <button type="submit" data-kt-contacts-type="submit" class="btn btn-sm btn-primary">
                                    <span class="indicator-label">Save</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endcan
                </form>
            </div>
        </div>
    </div>

    <div class="card card-flush d-none" id="ledger_list_div">
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Table-->
            <div id="kt_ecommerce_category_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="kt_ecommerce_category_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Ledger ID </th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Ledger Date</th>
                                <th class="min-w-50px sorting" tabindex="0"
                                    aria-controls="kt_ecommerce_category_table" rowspan="1" colspan="1"
                                    style="width: 50px;" aria-label="Category: activate to sort column ascending">
                                    Invoice No</th>
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
                        <tbody class="fw-semibold text-gray-700" id="ledger_list">
                            
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>


</x-default-layout>

<script type="text/javascript">
    $('#changeCustomer').on('change', function() {
        var customerID = $(this).val();
        if (customerID) {
            $.ajax({
                url: 'customerDetails/' + customerID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    if (data) {
                        $('#ledger_list_div').removeClass('d-none');
                        $('#ledger_list').empty();
                        var totalDue = 0;
                        $.each(data, function(key, value) {
                            var customer_name = value.customer.customer_name;
                            var customer_mobile = value.customer.customer_mobile;
                            var customer_address = value.customer.customer_address;
                            totalDue = totalDue + (value.credit - value.debit);
                            $('#customer_mobile').val(customer_mobile);
                            // $('#is_previous_due').val(due);
                            $('#customer_address').val(customer_address);

                            $('#ledger_list').append(`<tr>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a href="" class="text-gray-800 text-hover-primary  fw-bold mb-1"
                                               data-kt-ecommerce-category-filter="category_name">` + value.id + `</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a href="" class="text-gray-800 text-hover-primary  fw-bold mb-1"
                                               data-kt-ecommerce-category-filter="category_name">` + value
                                .ledger_date + `</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a href="" class="text-gray-800 text-hover-primary  fw-bold mb-1"
                                               data-kt-ecommerce-category-filter="category_name">` + value.invoice_no + `</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a href="" class="text-gray-800 text-hover-primary  fw-bold mb-1"
                                               data-kt-ecommerce-category-filter="category_name">` + value.debit + `</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a href="" class="text-gray-800 text-hover-primary  fw-bold mb-1"
                                               data-kt-ecommerce-category-filter="category_name">` + value.credit + `</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a href="" class="text-gray-800 text-hover-primary  fw-bold mb-1"
                                               data-kt-ecommerce-category-filter="category_name">` + totalDue + `</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <a href="" class="text-gray-800 text-hover-primary  fw-bold mb-1"
                                               data-kt-ecommerce-category-filter="category_name">` + value.remarks + `</a>
                                        </div>
                                    </div>
                                </td></tr>`);
                        });

                        $('#totalDue').val(totalDue);
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