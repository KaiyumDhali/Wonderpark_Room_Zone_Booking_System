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
        <div id="kt_app_content_container" class="app-container">
            <!--begin::Products-->
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <h3>Stock Transfer Invoice Details</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0" id="invoice_wise_stock_transfer">
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
                        <tbody class="fw-semibold text-gray-700">
                            {{-- Table rows will be dynamically loaded --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-default-layout>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tableBody = document.querySelector('#invoice_wise_stock_transfer tbody');
        const data = @json($results);

        let serial = 1;

        // Function to create a table row
        const createRow = (item) => {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td class="text-center">${serial++}</td>
                <td class="text-center">${item.stock_id}<input type="hidden" name="table_stock_id[]" value="${item.stock_id}"></td>
                <td>${item.stock_date}<input type="hidden" name="table_stock_date[]" value="${item.stock_date}"></td>
                <td>${item.invoice_no}<input type="hidden" name="table_invoice_no[]" value="${item.invoice_no}"></td>
                <td>${item.from_warehouse_name}<input type="hidden" name="table_from_warehouse_id[]" value="${item.from_warehouse_id}"></td>
                <td>${item.to_warehouse_name}<input type="hidden" name="table_to_warehouse_id[]" value="${item.to_warehouse_id}"></td>
                <td>${item.product_name}<input type="hidden" name="table_product_id[]" value="${item.product_id}"></td>
                <td>${item.quantity}<input type="hidden" name="table_quantity[]" value="${item.quantity}"></td>
                <td>${item.unit_price}<input type="hidden" name="table_unit_price[]" value="${item.unit_price}"></td>
                <td>${item.total_amount}<input type="hidden" name="table_total_amount[]" value="${item.total_amount}"></td>
                <td class="${item.product_status === 1 ? 'text-success' : 'text-danger'}">
                    ${item.product_status === 1 ? 'Approved' : 'Pending'}
                    <input type="hidden" name="table_product_status[]" value="${item.product_status}">
                </td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-success approve-btn">Approve</button>
                </td>
            `;

            tableBody.appendChild(row);
        };

        // Load data into the table
        const loadTableData = () => {
            data.forEach(item => createRow(item));
        };

        // Handle approve button click
        const handleApproveClick = (event) => {
            const button = event.target;
            const row = button.closest('tr');

            if (button.classList.contains('approve-btn')) {
                const rowData = Array.from(row.querySelectorAll('input')).reduce((acc, input) => {
                    acc[input.name] = input.value;
                    return acc;
                }, {});

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to approve this product?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitApproval(rowData);
                    }
                });
            }
        };

        // Submit approval via POST
        const submitApproval = (data) => {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('invoice_wise_stock_transfer_individual_approved_store') }}";

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = "{{ csrf_token() }}";
            form.appendChild(csrfToken);

            for (const [key, value] of Object.entries(data)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();
        };

        // Event delegation for approve buttons
        tableBody.addEventListener('click', handleApproveClick);

        // Initialize table with data
        loadTableData();
    });
</script>