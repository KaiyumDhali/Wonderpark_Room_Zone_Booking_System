<x-default-layout>
    <style>
        .card .card-header {
            min-height: 40px;
        }

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
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container">
            <!--begin::Products-->
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3>Stock Transfer Invoice Details</h3>
                    </div>
                </div>
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0" id="invoice_wise_stock_transfer">
                        <!--begin::Table head-->
                        <thead>
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-20px">SL</th>
                                <th class="min-w-30px">Stock ID</th>
                                <th class="min-w-150px">Stock Date</th>
                                <th class="min-w-50px">Invoice</th>
                                <th class="min-w-100px">From Warehouse</th>
                                <th class="min-w-100px">To Warehouse</th>
                                <th class="min-w-100px">Product Name</th>
                                <th class="min-w-20px">Quantity</th>
                                <th class="min-w-20px">Unit Price</th>
                                <th class="min-w-20px">Total Amount</th>
                                <th class="min-w-20px">Status</th>
                                <th class="min-w-300px text-end">Action</th>
                            </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-700">
                            {{-- Rows will be populated dynamically --}}
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
    // Get the data array from backend
    var getData = @json($results);
    console.log('getData', getData);

    let id = 1;

    // Function to add a row dynamically
    function addProductRow(result) {
        const row = `
            <tr>
                <td class="text-center">${id}</td>
                <td class="text-center">${result.stock_id}<input type="hidden" name="table_stock_id[]" value="${result.stock_id}"></td>
                <td>${result.stock_date}<input type="hidden" name="table_stock_date[]" value="${result.stock_date}"></td>
                <td>${result.invoice_no}<input type="hidden" name="table_invoice_no[]" value="${result.invoice_no}"></td>
                <td>${result.from_warehouse_name}<input type="hidden" name="table_from_warehouse_id[]" value="${result.from_warehouse_id}"></td>
                <td>${result.to_warehouse_name}<input type="hidden" name="table_to_warehouse_id[]" value="${result.to_warehouse_id}"></td>
                <td>${result.product_name}<input type="hidden" name="table_product_id[]" value="${result.product_id}"></td>
                <td>${result.quantity}<input type="hidden" name="table_quantity[]" value="${result.quantity}"></td>
                <td>${result.unit_price}<input type="hidden" name="table_unit_price[]" value="${result.unit_price}"></td>
                <td>${result.total_amount}<input type="hidden" name="table_total_amount[]" value="${result.total_amount}"></td>
                <td class="${result.product_status === 1 ? 'text-success' : 'text-danger'}">
                    ${result.product_status === 1 ? 'Approved' : 'Pending'}
                    <input type="hidden" name="table_product_status[]" value="${result.product_status}">
                </td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-success approve-btn ${result.product_status === 1 ? 'd-none' : ''}">Approve</button>
                </td>
            </tr>
        `;
        $('#invoice_wise_stock_transfer tbody').append(row);
        id++;
    }

    // Populate table rows from backend data
    function loadProductsFromData() {
        getData.forEach(result => {
            addProductRow({
                stock_id: result.stock_id,
                stock_date: result.stock_date,
                invoice_no: result.invoice_no,
                from_warehouse_id: result.from_warehouse_id,
                to_warehouse_id: result.to_warehouse_id,
                product_id: result.product_id,
                from_warehouse_name: result.from_warehouse_name || 'Unknown Product',
                to_warehouse_name: result.to_warehouse_name || 'Unknown Product',
                product_name: result.product_name || 'Unknown Product',
                quantity: result.quantity || 0,
                unit_price: result.unit_price || 0,
                total_amount: result.total_amount || 0,
                product_status: result.product_status,
            });
        });
    }

    // SweetAlert2 confirmation for approval
    $(document).on('click', '.approve-btn', function (e) {
        e.preventDefault(); // Prevent the default action

        let $button = $(this); // Get the clicked button
        let $row = $button.closest('tr'); // Get the closest row of the clicked button

        // Extract data from the row
        let rowData = {
            stock_id: $row.find('input[name="table_stock_id[]"]').val(),
            stock_date: $row.find('input[name="table_stock_date[]"]').val(),
            invoice_no: $row.find('input[name="table_invoice_no[]"]').val(),
            from_warehouse_id: $row.find('input[name="table_from_warehouse_id[]"]').val(),
            to_warehouse_id: $row.find('input[name="table_to_warehouse_id[]"]').val(),
            product_id: $row.find('input[name="table_product_id[]"]').val(),
            quantity: $row.find('input[name="table_quantity[]"]').val(),
            unit_price: $row.find('input[name="table_unit_price[]"]').val(),
            total_amount: $row.find('input[name="table_total_amount[]"]').val(),
            product_status: $row.find('input[name="table_product_status[]"]').val(),
        };

        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to approve this product?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                // Create a form dynamically
                let form = $('<form>', {
                    method: 'POST',
                    action: "{{ route('invoice_wise_stock_transfer_individual_approved_store') }}",
                });

                // Add CSRF token
                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: "{{ csrf_token() }}",
                }));

                // Add row data to the form
                Object.entries(rowData).forEach(([key, value]) => {
                    form.append($('<input>', {
                        type: 'hidden',
                        name: key,
                        value: value,
                    }));
                });

                // Append the form to the body and submit
                $('body').append(form);
                form.submit();
            }
        });
    });

    // Load data on page load
    loadProductsFromData();
</script>