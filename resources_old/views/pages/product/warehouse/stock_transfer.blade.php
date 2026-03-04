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

        <form class="px-0" id="warehouseTransferForm" method="POST" action="{{ route('warehouse_transfer') }}"
            enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Product add to card Content Row -->
                <div class="col-md-8">

                    <div class="row">
                        {{-- select product --}}
                        <div class="col-md-12 card shadow">
                            <div class="card-header px-2 mx-0">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('Warehouse Transfer') }}</h1>
                                </div>
                            </div>
                            <div class="card-body py-3 px-2">
                                <div class="col-md-12 pb-3">
                                    <div class="row">
                                        <div class="form-group col-md-2 pt-0 pb-2">
                                            <label class="required">{{ __('Transfer Date') }}</label>
                                            <input type="date"
                                                class="form-control form-control-sm form-control-solid"
                                                id="voucher_date" name="voucher_date" value="" required />
                                        </div>

                                        {{-- <div class="form-group col-md-5 pt-0 pb-2">
                                            <label>{{ __('From Warehouse') }}</label>
                                            <select id="from_warehouse_id" class="form-select form-select-sm"
                                                name="from_warehouse_id" data-control="select2"
                                                data-hide-search="false">
                                                <option value="">Select Warehouse</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}">
                                                        {{ $warehouse->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-5 pt-0 pb-2">
                                            <label>{{ __('To Warehouse') }}</label>
                                            <select id="to_warehouse_id" class="form-select form-select-sm"
                                                name="to_warehouse_id" data-control="select2" data-hide-search="false">
                                                <option value="">Select Warehouse</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}">
                                                        {{ $warehouse->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div> --}}

                                        <div class="form-group col-md-5 pt-0 pb-2">
                                            <label>{{ __('From Warehouse') }}</label>
                                            <select id="from_warehouse_id" class="form-select form-select-sm"
                                                name="from_warehouse_id" data-control="select2"
                                                data-hide-search="false">
                                                <option value="">Select Warehouse</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-5 pt-0 pb-2">
                                            <label>{{ __('To Warehouse') }}</label>
                                            <select id="to_warehouse_id" class="form-select form-select-sm"
                                                name="to_warehouse_id" data-control="select2" data-hide-search="false">
                                                <option value="">Select Warehouse</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="form-group col-md-6 pt-0 pb-2">
                                            <label>{{ __('Select Product') }}</label>
                                            <select id="changeProduct" class="form-select form-select-sm"
                                                name="product_id" data-control="select2" data-hide-search="false">
                                                <option value="">Select Product</option>
                                                {{-- @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">Name:
                                                        {{ $product->product_name }}
                                                        (Code: {{ $product->product_code }})
                                                    </option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4 my-3">
                                            <label for="order">{{ __('Product Name') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid" readonly
                                                id="product_name" name="product_name " />
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Code') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid" readonly
                                                id="product_code" name="product_code " />
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Unit Name') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid" readonly
                                                id="sales_unit" name="sales_unit " />
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Present Stock') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid"
                                                id="present_stock" name="present_stock" step="1" min="0"
                                                oninput="this.value = this.value.replace(/\D/g, '');" readonly>
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Price (Per Unit)') }}</label>
                                            <input type="text" class="form-control form-control-sm" id="sales_price"
                                                name="sales_price"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                            <input type="hidden" class="form-control form-control-sm"
                                                id="purchase_price" name="purchase_price"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>

                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Quantity') }}</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="productQuantity" name="quantity"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Amount') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid"
                                                id="quantityAmount" name="Amount" value=0 readonly />
                                        </div>

                                        <div class="form-group col-md-2 my-3">
                                            <button type="button" id="addProduct"
                                                class="btn btn-sm btn-primary px-5 mt-6">{{ __('Add to Cart') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- add to product --}}
                        <div class="col-md-12 card shadow my-5 px-5 mx-0">
                            <div id="cart_product">
                                <table id="product_add" class="table table-bordered mt-4">
                                    <tr class="text-center">
                                        <th>SL</th>
                                        <th class="w-80px">Product ID</th>
                                        <th>Product Name</th>
                                        <th class="w-60px">Unit</th>
                                        <th class="w-80px">Quantity</th>
                                        <th>Sale Price</th>
                                        <th>Amount</th>
                                        <th></th>
                                    </tr>
                                </table>

                                <table id="sumAmountShow" class="table table-bordered">

                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Product sales summary Content Row -->
                <div class="col-md-4">
                    <div class="card shadow px-10 pb-8 ms-0 ms-md-5">
                        <div class="card-header my-3 mx-0 px-2">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h1 class="h3 mb-0 text-gray-800">{{ __('Warehouse Transfer Summary') }}</h1>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="order">{{ __('Net Total Amount') }}</label>
                            <input type="number" class="form-control form-control-sm form-control-solid"
                                id="netTotalAmount" name="netTotalAmount" value=0 readonly />
                        </div>

                        <div class="form-group">
                            <label for="remarks">{{ __('Remarks') }}</label>
                            <input type="text" class="form-control form-control-sm" id="remarks"
                                name="remarks" />
                        </div>
                        <button type="submit" id="submitBtn"
                            class="btn btn-sm btn-success px-5 mt-5">{{ __('Warehouse Transfer') }}</button>

                    </div>
                </div>
            </div>
        </form>
    </div>

</x-default-layout>

<script type="text/javascript">
    document.getElementById("warehouseTransferForm").addEventListener("keydown", function(event) {
        // Prevent form submission on Enter key press
        if (event.key === "Enter") {
            event.preventDefault();
        }
    });

    document.getElementById("submitBtn").addEventListener("click", function(event) {
        event.preventDefault(); // Stop the form from submitting immediately

        // Get the elements for validation
        var voucherDate = document.getElementById("voucher_date");
        // var customerSelect = document.getElementById("changeCustomer");
        // var accountsName = document.getElementById("changeAccountsName");
        var productInputs = document.querySelectorAll('input[name="table_product_id[]"]');

        // 1st check: Voucher Date
        if (voucherDate.value === "") {
            Swal.fire({
                icon: 'error',
                title: 'No Voucher Date Selected',
                text: 'Please select a voucher date before submitting the form.'
            });
        }
        // 2nd check: Customer
        // else if (customerSelect.value === "") {
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'No Customer Selected',
        //         text: 'Please select a customer before submitting the form.'
        //     });
        // }
        // 4th check: Account Name must be selected
        // else if (accountsName.value === "") {
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'No Account Selected',
        //         text: 'Please select an account name before submitting the form.'
        //     });
        // } 
        // 5thcheck: Product selection (ensure at least one product is selected)
        else if (productInputs.length === 0 || Array.from(productInputs).every(input => input.value === "")) {
            Swal.fire({
                icon: 'error',
                title: 'No Products Selected',
                text: 'Please select at least one product before submitting the form.'
            });
        } else {
            // All checks passed: Show SweetAlert2 confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to submit the form?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit it!',
                cancelButtonText: 'No, cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // If Yes is clicked, submit the form
                    document.getElementById("warehouseTransferForm").submit();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // If No is clicked, do nothing
                    Swal.fire(
                        'Cancelled',
                        'Your form submission has been cancelled.',
                        'error'
                    );
                }
            });
        }
    });

    // Event handler for changing the from_warehouse_id details
    $(document).ready(function() {
        $('#from_warehouse_id').on('change', function() {
            let warehouseId = $(this).val();

            // Clear previous error messages and dropdown
            $('#jsdataerror').text('');
            $('#changeProduct').empty().append('<option value="" selected>Select Product</option>');

            if (warehouseId) {
                // Dynamically generate the route URL
                let url =
                    "{{ route('warehouse_wise_product_list', ['warehouseId' => ':warehouseId']) }}"
                    .replace(':warehouseId', warehouseId);

                // Make AJAX call
                $.ajax({
                    url: url,
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}" // CSRF token for security
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data && data.length > 0) {
                            // Populate dropdown with product list
                            $.each(data, function(index, product) {
                                $('#changeProduct').append(
                                    `<option value="${product.product_id}">${product.product_name} (code: ${product.product_code})</option>`
                                );
                            });
                        } else {
                            // Handle empty product list
                            $('#jsdataerror').text(
                                'No products found for the selected warehouse.');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Log and display error message
                        console.error('Error fetching product list:', error);
                        $('#jsdataerror').text(
                            'An error occurred while fetching the product list. Please try again.'
                        );
                    }
                });
            } else {
                // Handle empty warehouse ID
                $('#jsdataerror').text('Please select a valid warehouse.');
            }
        });
    });

    $(document).ready(function() {
        // Fetch product details based on selected product ID and warehouse ID
        function fetchProductDetails(productId, warehouseId) {
            if (!productId || !warehouseId) {
                console.error("Missing productId or warehouseId.");
                return;
            }
            let url =
                "{{ route('warehouse_wise_product_details', ['productId' => ':productId', 'warehouseId' => ':warehouseId']) }}"
                .replace(':productId', productId)
                .replace(':warehouseId', warehouseId);

            console.log(url);
            $.ajax({
                url: url,
                type: "GET",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" // CSRF token for security
                },
                dataType: "json",
                success: function(data) {
                    if (data) {
                        console.log(data);
                        $('#product_id').val(data.product_id);
                        $('#product_name').val(data.product_name);
                        $('#product_code').val(data.product_code);
                        $('#sales_unit').val(data.unit_name);
                        $('#sales_price').val(data.sales_price);
                        $('#purchase_price').val(data.purchase_price);
                        $('#present_stock').val(data.present_stock);
                        $('#productQuantity').val(data.pack_size);
                    } else {
                        console.warn("No product details found for the selected product.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching product details:", error);
                }
            });
        }

        // Event handler for product selection change
        $('#changeProduct').on('change', function() {
            let productId = $(this).val();
            let warehouseId = $('#from_warehouse_id').val(); // Fixed ID selector

            if (productId && warehouseId) {
                fetchProductDetails(productId, warehouseId);
            } else {
                console.warn("Product ID or Warehouse ID is missing.");
            }
        });

        // Calculate total amount when quantity or sales price changes
        function onChangeCalculateTotalAmount() {
            let quantity = parseFloat($('#productQuantity').val()) || 0;
            let sales_price = parseFloat($('#sales_price').val()) || 0;

            let totalAmount = (quantity * sales_price).toFixed(2);
            $('#quantityAmount').val(totalAmount > 0 ? totalAmount : 0);
        }

        // Attach event handler to inputs for real-time calculation
        $('#productQuantity, #sales_price').on('keyup change', onChangeCalculateTotalAmount);
    });


    // Trigger the addProduct function on Enter key press for inputs
    $('input').on('keypress', function(event) {
        if (event.key === "Enter") {
            $('#addProduct').click();
        }
    });

    // -------------------------------- add to card start ---------------------------------------------------------

    let id = 1;
    // Add Product button click event
    $('#addProduct').on('click', function() {
        // Get values for validation
        var voucherDate = $('#voucher_date').val();
        var from_warehouse_id = $('#from_warehouse_id').val();
        var to_warehouse_id = $('#to_warehouse_id').val();
        var product_id = $('#changeProduct').val();
        var product_name = $('#product_name').val();
        var sales_unit = $('#sales_unit').val();
        var quantity = parseFloat($('#productQuantity').val());
        var sales_price = parseFloat($('#sales_price').val()) || 0;
        var purchase_price = parseFloat($('#purchase_price').val()) || 0;
        var quantityAmount = parseFloat($('#quantityAmount').val()) || 0;
        var totalAmount = parseFloat($('#netTotalAmount').val()) || 0;

        // Validation checks using SweetAlert
        if (!voucherDate) {
            Swal.fire({
                icon: 'error',
                title: 'No Date Selected',
                text: 'Please select a  date before adding a product.'
            });
        } else if (!from_warehouse_id) {
            Swal.fire({
                icon: 'error',
                title: 'No From Warehouse Selected',
                text: 'Please select from warehouse before adding a product.'
            });
        } else if (!to_warehouse_id) {
            Swal.fire({
                icon: 'error',
                title: 'No To Warehouse Selected',
                text: 'Please select to warehouse before adding a product.'
            });
        } else if (!product_id) {
            Swal.fire({
                icon: 'error',
                title: 'No Product Selected',
                text: 'Please select a product before adding it to the list.'
            });
        } else if (!quantity || quantity <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Quantity',
                text: 'Please enter a valid product quantity before adding.'
            });
        } else {
            // All fields are valid, proceed with adding the product logic
            let duplicate_found = false;
            let existingRow;

            // Check for duplicate rows
            $('#product_add tr').each(function() {
                if ($(this).find('input[name="table_product_id[]"]').val() === product_id) {
                    duplicate_found = true;
                    existingRow = $(this);
                    return false; // Break loop on finding a duplicate
                }
            });

            if (duplicate_found) {
                // Update the existing row
                let oldQuantity = parseFloat(existingRow.find('.table_quantity').val()) || 0;
                let newQuantity = oldQuantity + quantity;
                let oldAmount = parseFloat(existingRow.find('.cart_amount').text()) || 0;
                let newAmount = oldAmount + quantityAmount;
                newAmount = Math.round(newAmount);

                // Update the row with the new data
                existingRow.find('.table_quantity').val(newQuantity).next().val(newQuantity);
                existingRow.find('.table_cart_amount').val(newAmount).next().val(newAmount);
                existingRow.find('.quantity_text').text(newQuantity);
                existingRow.find('.cart_amount').text(newAmount);

                // Update net total
                let netTotal = totalAmount - oldAmount + newAmount;
                $('#netTotalAmount').val(netTotal.toFixed(2));

            } else {
                // Add new product row if no duplicate
                let totalAmountSum = quantityAmount;
                let cartAmount = Math.round(totalAmountSum);
                let finalAmount = (totalAmount + cartAmount).toFixed(2);

                $('#product_add').append(`
                <tr class="each_row" data-id="${id}">
                    <td class="text-center">${id} <input type="hidden" name="table_purchase_price[]" value="${purchase_price}"></td>
                    <td class="text-center">${product_id}<input type="hidden" name="table_product_id[]" value="${product_id}"></td>
                    <td>${product_name}</td>
                    <td class="text-center">${sales_unit}</td>
                    <td class="text-center"><span class="quantity_text">${quantity}</span><input type="hidden" class="table_quantity" name="table_product_quantity[]" value="${quantity}"></td>
                    <td class="text-end">${sales_price}<input type="hidden" class="table_price" name="table_product_price[]" value="${sales_price}"></td>
                    <td id="cart${id}" class="text-end"><span class="cart_amount">${cartAmount}</span><input type="hidden" class="table_cart_amount" name="table_product_cart_amount[]" value="${cartAmount}"></td>
                    <td class="text-center"><button type="button" data-id="${id}" class="add_product_delete btn btn-sm btn-danger py-1 px-3">X</button></td>
                </tr>
            `);
                id++;
                $('#netTotalAmount').val(finalAmount);
            }

            // Clear input fields after adding product
            resetFields();

            // SweetAlert success notification
            // Swal.fire({
            //     icon: 'success',
            //     title: 'Product Added',
            //     text: 'The product has been added successfully!'
            // });
        }
    });

    // Function to reset the form fields after adding a product
    function resetFields() {
        // Add a 'readonly' class to prevent interaction with the select fields
        $('#from_warehouse_id').addClass('readonly');
        $('#to_warehouse_id').addClass('readonly');
        // Reset and re-open the Select2 dropdown for product selection
        $('#changeProduct').val('').trigger('change').select2('open');

        // Clear the other input fields
        $('#product_name').val('');
        $('#sales_unit').val('');
        $('#sales_price').val('');
        $('#purchase_price').val('');
        $('#present_stock').val('');
        $('#productQuantity').val('');
        $('#quantityAmount').val('');
    }

    // Deleting product row logic
    $(document).on('click', '.add_product_delete', function() {
        let rowId = $(this).data('id');
        let row = $(`tr[data-id="${rowId}"]`);
        let cartAmount = parseFloat(row.find('.cart_amount').text()) || 0;
        let netTotal = parseFloat($('#netTotalAmount').val()) || 0;

        // Update net total after removing the row
        $('#netTotalAmount').val((netTotal - cartAmount).toFixed(2));

        // Remove the row from the table
        row.remove();
    });

    // -------------------------------- add to card end -----------------------------------------------------------
</script>
