<x-default-layout>
    <style>
        .card .card-header {
            min-height: 40px;
        }

        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }
    </style>

    <div class="container-fluid">
        <!-- Page Heading -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <!-- Product Add to Cart Content Row -->
            <div class="col-12 col-md-8">
                <div class="row">
                    <!-- Select Product -->
                    <div class="col-md-12 card shadow">
                        <div class="card-header px-2 mx-0">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h1 class="h3 mb-0 text-gray-800">{{ __('Purchase Return') }}</h1>
                            </div>
                        </div>

                        <form class="px-0" id="purchaseform" method="POST" action="{{ route('purchase_invoice_return_store', $invoiceNo) }}"  enctype="multipart/form-data">
                            @csrf
                            <div class="col-12">
                                <div id="cart_product">
                                    <table id="product_add" class="table table-bordered mt-4">
                                        <thead class="text-center">
                                            <tr>
                                                <th>SL</th>
                                                <th>Product Name</th>
                                                <th>Unit</th>
                                                <th class="w-100px">Qty</th>
                                                <th class="w-100px">Return Qty</th>
                                                <th class="w-110px">Purchase Price</th>
                                                <th class="w-110px">Discount</th>
                                                <th class="w-110px">Amount</th>
                                                <th class="w-150px">Return Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Dynamic Rows Will Be Injected Here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

            <!-- Purchase Summary Content Row -->
            <div class="col-12 col-md-4">
                <div class="card shadow px-10 pb-8 ms-0 ms-md-5">
                    <div class="card-header my-3 mx-0 px-2">
                        <div class="d-sm-flex align-items-center justify-content-center">
                            <h1 class="h3 text-center mb-0 text-gray-800">{{ __('Purchase Return Summary') }}</h1>
                        </div>
                    </div>

                    <div class="form-group">
                        <h4> Invoice No: {{ $invoiceNo }} </h4>
                        <h4> Invoice Date: {{ $supplierFinance->voucher_date }}</h4>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Net Total Amount') }}</label>
                        <input type="text" class="form-control form-control-sm form-control-solid" id="netTotalAmount" name="netTotalAmount" value="0" readonly />
                    </div>

                    <div class="form-group">
                        <label>{{ __('Return Net Total Amount') }}</label>
                        <input type="text" class="form-control form-control-sm form-control-solid" id="returnNetTotalAmount" name="returnNetTotalAmount" value="0" readonly />
                        <input type="hidden" class="form-control form-control-sm form-control-solid" id="invoice_no" name="invoice_no" value="{{ $supplierFinance->invoice_no }}" />
                        <input type="hidden" class="form-control form-control-sm form-control-solid" id="voucher_no" name="voucher_no" value="{{ $supplierFinance->voucher_no }}" />

                    <div class="form-group">
                        <label>{{ __('Select Supplier') }}</label>
                        <select id="changeSupplier" class="form-control form-control-sm" name="supplier_id" data-control="select2" data-placeholder="Select Supplier" required readonly>
                            <option></option>
                            @foreach ($supplierAccounts as $supplierAccount)
                                <option value="{{ $supplierAccount->id }}" @if ($supplierAccount->id == $stocks[0]->supplier_id) selected @endif>
                                    {{ $supplierAccount->account_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="voucher_date" class="required">{{ __('Return Date') }}</label>
                        <input type="date" class="form-control form-control-sm form-control-solid" id="voucher_date" name="voucher_date" value="{{ now()->toDateString() }}" required />
                    </div>

                    <div class="form-group">
                        <label>{{ __('Remarks') }}</label>
                        <input type="text" class="form-control form-control-sm" id="remarks" name="remarks" value="{{ $stocks[0]->remarks }}" />
                    </div>

                    <button type="submit" class="btn btn-sm btn-success px-5 mt-5">{{ __('Purchase Return') }}</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</x-default-layout>

<script type="text/javascript">
    // Disable Enter Key in Form Submission
    document.getElementById("purchaseform").addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
        }
    });

    // Get Data Array from Backend
    var getData = @json($stocks);
    let id = 1;

    // Function to Add a Product Row Dynamically
    function addProductRow(product) {
        //let cartAmount = (product.purchase_price * product.quantity) - product.discount;
        let cartAmount = product.quantity_amount - product.discount;
        cartAmount = Math.round(cartAmount);

        let returnCartAmount = 0;
        let sumAmount = parseFloat(cartAmount) + parseFloat($('#netTotalAmount').val());
        $('#netTotalAmount').val(sumAmount);

        let row = `
            <tr class="each_row" data-id="${id}">
                <td class="text-center">${id}</td>
                <td>${product.product_name}<input type="hidden" name="table_product_id[]" value="${product.product_id}"></td>
                <td class="text-center">${product.purchase_unit}</td>
                <td class="text-center">${product.quantity}
                    <input type="hidden" class="form-control py-0 my-0 text-end previousQuantity" name="table_product_quantity[]" value="${product.quantity}">
                </td>
                <td>
                    <input type="number" class="form-control table_quantity p-0 m-0 text-end" name="table_product_return_quantity[]" step="1" min="0" oninput="this.value = Math.max(this.value, 0);" value="0">
                </td>
                <td class="text-end">${product.purchase_price}
                    <input type="hidden" name="table_product_price[]" value="${product.purchase_price}">
                </td>
                <td class="text-end">${product.discount}
                    <input type="hidden" name="table_product_discount[]" value="${product.discount}">
                </td>
                <td class="cart_amount text-end">${cartAmount}
                    <input type="hidden" name="table_product_cart_amount[]" value="${cartAmount}">
                </td>
                <td id="cart${id}" class="returnCartAmount text-end">
                    <input type="text" class="form-control py-0 m-0 text-end" name="table_product_return_cart_amount[]" value="${returnCartAmount}" readonly>
                </td>
            </tr>
        `;

        $('#product_add tbody').append(row);
        id++;
    }

    // Function to Loop Through Data and Add Product Rows
    function loadProductsFromData() {
        getData.forEach(product => {
            addProductRow({
                product_id: product.product_id || 0,
                product_name: product.product?.product_name || 'Unknown Product',
                purchase_unit: product.unit?.unit_name || 'N/A',
                quantity: product.stock_in_quantity || 0,
                purchase_price: product.stock_in_unit_price || 0,
                discount: product.stock_in_discount || 0,
                quantity_amount: product.stock_in_total_amount || 0 // If discount is null, use 0
            });
        });
    }
    loadProductsFromData();

    // Handle Return Quantity Input and Calculations
    $('#cart_product').on('input', 'input[name="table_product_return_quantity[]"]', function() {
        let returnQuantity = parseFloat($(this).val()) || 0;
        let previousQuantity = parseFloat($(this).closest('.each_row').find('input[name="table_product_quantity[]"]').val()) || 0;

        if (returnQuantity > previousQuantity) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Return quantity cannot exceed original quantity.',
                confirmButtonText: 'OK'
            });
            $(this).val(0);
            returnQuantity = 0;
        }

        let purchase_price = parseFloat($(this).closest('.each_row').find('input[name="table_product_price[]"]').val()) || 0;
        let discount = parseFloat($(this).closest('.each_row').find('input[name="table_product_discount[]"]').val()) || 0;
        let update_amount = (returnQuantity * purchase_price) - discount;
            update_amount = Math.round(update_amount);
        let currentTotal = parseFloat($(this).closest('.each_row').find('input[name="table_product_return_cart_amount[]"]').val()) || 0;

        let getAmount = parseFloat($('#returnNetTotalAmount').val()) || 0;
        let calculateNetTotal = update_amount - currentTotal;
        let finalAmount = getAmount + calculateNetTotal;

        $('#returnNetTotalAmount').val(finalAmount);
        $(this).closest('.each_row').find('input[name="table_product_return_cart_amount[]"]').val(update_amount);
    });

    // Open PDF After Purchase Return Success
    @if (session('success'))
        let invoiceNo = "{{ $invoiceNo }}";
        let url = `/purchase/return/pdf/${invoiceNo}`;
        window.open(url, '_blank');
    @endif
</script>