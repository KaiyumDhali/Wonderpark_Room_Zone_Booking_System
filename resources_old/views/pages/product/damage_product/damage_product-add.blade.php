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
        
        

        <form class="px-0" id="damageform" method="POST" action="{{ route('damage.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Product add to card Content Row -->
                <div class="col-md-12">

                    <div class="row">
                        {{-- select product --}}
                        <div class="col-md-12 card shadow">
                            <div class="card-header px-2 mx-0">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('Damage Product Add') }}</h1>
                                </div>
                            </div>
                            <div class="card-body py-3 px-2">
                                <div class="col-md-12 pb-3">
                                    <div class="row">

                                        <div class="form-group col-md-2 pt-0 pb-2">
                                            <label class="required">{{ __('Damage Date') }}</label>
                                            <input type="date"
                                                class="form-control form-control-sm form-control-solid" id="damage_date"
                                                name="damage_date" value="" required />
                                        </div>

                                        <div class="form-group col-md-4 pt-0 pb-2">
                                            <div class="card-body px-0 pb-2 pt-0 d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <label class="required">{{ __('Select Supplier') }}</label>
                                                    <select id="changeSupplier" class="form-control form-control-sm"
                                                        name="supplier_id" data-control="select2"
                                                        data-placeholder="Select Supplier" required>
                                                        <option></option>
                                                        @foreach ($supplierAccounts as $supplierAccount)
                                                            <option value="{{ $supplierAccount->id }}">
                                                                {{ $supplierAccount->account_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                {{-- <a href="#"
                                                    class="btn btn-sm btn-flex btn-light-primary ms-2 mt-5"
                                                    data-bs-toggle="modal" data-bs-target="#add_supplier_modal">
                                                    Add
                                                </a> --}}
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 d-none supplierInfo">
                                            <div class="card mt-5 p-2">
                                                <label for="" id="supplierAdd4"></label>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 d-none supplierInfo">
                                            <div class="card mt-5 p-2">
                                                <label for="" id="supplierAdd3"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6 pt-0 pb-2">
                                            <label>{{ __('Select Warehouse') }}</label>
                                            <select id="warehouseId" class="form-select form-select-sm"
                                                name="warehouse_id" data-control="select2" data-hide-search="false">
                                                <option value="">Select Warehouse</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}">
                                                        {{ $warehouse->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6 pt-0 pb-2">
                                            <label>{{ __('Select Product') }}</label>
                                            <select id="changeProduct" class="form-select form-select-sm"
                                                name="product_id" data-control="select2" data-hide-search="false">
                                                <option value="">Select Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">Name:
                                                        {{ $product->product_name }}
                                                        (Code: {{ $product->product_code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
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
                                            <label for="order">{{ __('Sales Price') }}</label>
                                            <input type="text" class="form-control form-control-sm" id="sales_price"
                                                name="sales_price"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Purchase Price') }}</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="purchase_price" name="purchase_price"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Present Stock') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid"
                                                id="present_stock" name="present_stock" step="1" min="0"
                                                oninput="this.value = this.value.replace(/\D/g, '');" readonly>
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

                                        {{-- <div class="form-group col-md-2 my-3">
                                            <label class="">Discount Type</label>
                                            <select class="form-select form-select-sm" data-control="select2"
                                                name="type" id="percentageType">
                                                <option value="">--Select Type--</option>
                                                <option value="1" selected>Fixed</option>
                                                <option value="2">Percentage</option>
                                            </select>
                                        </div> --}}

                                        {{-- <div class="form-group col-md-2 d-none my-3" id="percentageInputContainer">
                                            <label class=""><span class="">Percentage </span></label>
                                            <input type="text" class="form-control form-control-sm"
                                                name="percentage" id="percentageInput"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div> --}}

                                        {{-- <div class="form-group col-md-2 my-3" id="amountInputContainer">
                                            <label class=""><span>Discount </span> </label>
                                            <input type="text" class="form-control form-control-sm" id="discount"
                                                name="discount"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div> --}}

                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Damage Reason') }}</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="damage_reason" name="damage_reason">
                                        </div>

                                        <div class="row">

                                            <div class="form-group col-md-2 my-3">
                                                <div class="card-body py-3">
                                                    <div class="product fv-row fv-plugins-icon-container">
                                                        <label
                                                            class="form-check form-switch form-check-custom form-check-solid">
                                                            <input class="form-check-input w-30px h-20px"
                                                                type="checkbox" value="1"
                                                                name="is_exchangeable" />
                                                            <span class="form-check-label text-muted fs-6">is
                                                                Exchangeable
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-2 my-3">
                                                <div class="card-body py-3">
                                                    <div class="product fv-row fv-plugins-icon-container">
                                                        <label
                                                            class="form-check form-switch form-check-custom form-check-solid">
                                                            <input class="form-check-input w-30px h-20px"
                                                                type="checkbox" value="1"
                                                                name="is_repairable" />
                                                            <span class="form-check-label text-muted fs-6">is
                                                                Repairable
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-2 my-3">
                                                <div class="card-body py-3">
                                                    <div class="product fv-row fv-plugins-icon-container">
                                                        <label
                                                            class="form-check form-switch form-check-custom form-check-solid">
                                                            <input class="form-check-input w-30px h-20px"
                                                                type="checkbox" value="1"
                                                                name="is_resaleable" />
                                                            <span class="form-check-label text-muted fs-6">is
                                                                Resaleable
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-2 my-3">
                                                <button type="button" id="addProduct"
                                                    class="btn btn-sm btn-primary px-5">{{ __('Add to Cart') }}</button>
                                            </div>

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
                                        <th>Product Name</th>
                                        <th>Unit</th>
                                        <th class="w-100px">Quantity</th>
                                        <th>Sale Price</th>
                                        <th>Purchase Price</th>
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



            </div>
        </form>

        <button type="submit" id="submitBtn"
        class="btn btn-sm btn-success px-5 mt-5">{{ __('Save') }}</button>

    </div>


</x-default-layout>

<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function() {
        var damageForm = document.getElementById("damageform");
        if (!damageForm) {
            console.error("The damageform element is missing.");
            return;
        }

        damageForm.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
            }
        });

        document.getElementById("submitBtn").addEventListener("click", function(event) {
            event.preventDefault();

            var damageDate = document.getElementById("damage_date");
            var supplierSelect = document.getElementById("changeSupplier");
            var productInputs = document.querySelectorAll('input[name="table_product_id[]"]');
            var givenAmount = parseFloat(document.getElementById("givenAmount")?.value) || 0;
            var accountsName = document.getElementById("changeAccountsName");

            if (!damageDate || damageDate.value === "") {
                Swal.fire({
                    icon: 'error',
                    title: 'No Voucher Date Selected',
                    text: 'Please select a voucher date before submitting the form.',
                });
            } else if (!supplierSelect || supplierSelect.value === "") {
                Swal.fire({
                    icon: 'error',
                    title: 'No supplier Selected',
                    text: 'Please select a supplier before submitting the form.',
                });
            } else if (
                productInputs.length === 0 ||
                Array.from(productInputs).every(input => input.value === "")
            ) {
                Swal.fire({
                    icon: 'error',
                    title: 'No Products Selected',
                    text: 'Please select at least one product before submitting the form.',
                });
            } else if (givenAmount > 0 && (!accountsName || accountsName.value === "")) {
                Swal.fire({
                    icon: 'error',
                    title: 'No Account Selected',
                    text: 'Please select an account name before submitting the form.',
                });
            } else {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you really want to submit the form?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, submit it!',
                    cancelButtonText: 'No, cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        damageForm.submit();
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire(
                            'Cancelled',
                            'Your form submission has been cancelled.',
                            'error'
                        );
                    }
                });
            }
        });
    });


    // Function to fetch product details based on selected product ID
    function fetchProductDetails(productID) {
        let url = '{{ route('productDetails', ['id' => ':productID']) }}';
        url = url.replace(':productID', productID);
        console.log(url);
        $.ajax({
            url: url,
            type: "GET",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(data) {
                if (data) {

                    console.log(data);

                    $('#discount').val('');
                    $('#percentageInput').val('');
                    $('#percentageType').val('1');
                    $('#percentageInputContainer').addClass('d-none');
                    $('#discount').prop('readonly', false);

                    let product = data[0];
                    let present_stock = (product.product_stock_sum_stock_in_quantity - product
                        .product_stock_sum_stock_out_quantity).toFixed(2);

                    $('#product_name').val(product.product_name);
                    $('#product_code').val(product.product_code);
                    $('#sales_unit').val(product.unit.unit_name);
                    $('#purchase_price').val(product.purchase_price);
                    $('#sales_price').val(product.sales_price);
                    $('#present_stock').val(present_stock);
                    $('#productQuantity').val(product.pack_size);
                    onChangeCalculateTotalAmount()
                    // $('#quantityAmount').val(product.pack_size * product.sales_price);
                    // product focus
                    $('#productQuantity').focus();

                }
            },
            error: function() {
                console.error("Error fetching product details");
            }
        });
    }


    // Event handler for when the product selection changes
    $('#changeProduct').on('change', function() {
        let productID = $(this).val();
        console.log('productID', productID);
        if (productID) {
            fetchProductDetails(productID);
        }
    });

    // Event handler for changing the #productQuantity, #sales_price
    function onChangeCalculateTotalAmount() {
        let quantity = $('#productQuantity').val();
        let sales_price = $('#sales_price').val();

        if (quantity && sales_price) {
            let totalAmount = quantity * sales_price;
            totalAmount = (totalAmount).toFixed(3);
            // totalAmount = Math.round(totalAmount); // Round to nearest integer
            $('#quantityAmount').val(totalAmount);
        } else {
            $('#quantityAmount').val(0);
        }
    }

    $('#productQuantity, #sales_price').on('keyup', onChangeCalculateTotalAmount);

    // Event handler for changing the discount percentage type
    $('#percentageType').on('change', function() {
        $('#discount').val('');
        if ($(this).val() === '2') {
            $('#percentageInputContainer').removeClass('d-none');
            $('#discount').prop('readonly', true);
        } else {
            $('#percentageInputContainer').addClass('d-none');
            $('#discount').prop('readonly', false);
        }
    });

    // Event handler for updating discount based on percentage input
    $('#percentageInput').on('keyup', function() {
        let percentageValue = $(this).val();
        let totalAmount = parseFloat($('#quantityAmount').val());
        let discountAmount = (percentageValue / 100) * totalAmount;
        $('#discount').val(discountAmount.toFixed(2));
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
        var damageDate = $('#damage_date').val();
        var supplier = $('#changeSupplier').val();
        var warehouseId = $('#warehouseId').val();
        var product_id = $('#changeProduct').val();
        var product_name = $('#product_name').val();
        var sales_unit = $('#sales_unit').val();
        var quantity = parseFloat($('#productQuantity').val());
        var sales_price = parseFloat($('#sales_price').val()) || 0;
        var purchase_price = parseFloat($('#purchase_price').val()) || 0;
        var discount = parseFloat($('#discount').val()) || 0;
        var quantityAmount = parseFloat($('#quantityAmount').val()) || 0;
        var totalAmount = parseFloat($('#netTotalAmount').val()) || 0;

        // Validation checks using SweetAlert
        if (!damageDate) {
            Swal.fire({
                icon: 'error',
                title: 'No Damage Date Selected',
                text: 'Please select a damage date before adding a product.'
            });
        } else if (!supplier) {
            Swal.fire({
                icon: 'error',
                title: 'No supplier Selected',
                text: 'Please select a supplier before adding a product.'
            });
        } else if (!warehouseId) {
            Swal.fire({
                icon: 'error',
                title: 'No Warehouse Selected',
                text: 'Please select a warehouse before adding a product.'
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
                let oldDiscount = parseFloat(existingRow.find('.table_discount').val()) || 0;
                let newDiscount = oldDiscount + discount;
                let oldAmount = parseFloat(existingRow.find('.cart_amount').text()) || 0;
                let newAmount = oldAmount + (quantityAmount - discount);
                newAmount = Math.round(newAmount);

                // Update the row with the new data
                existingRow.find('.table_quantity').val(newQuantity).next().val(newQuantity);
                existingRow.find('.table_discount').val(newDiscount).next().val(newDiscount);
                existingRow.find('.table_cart_amount').val(newAmount).next().val(newAmount);
                existingRow.find('.quantity_text').text(newQuantity);
                existingRow.find('.discount_text').text(newDiscount);
                existingRow.find('.cart_amount').text(newAmount);

                // Update net total
                let netTotal = totalAmount - oldAmount + newAmount;
                $('#netTotalAmount').val(netTotal.toFixed(2));

            } else {
                // Add new product row if no duplicate
                let totalAmountSum = quantityAmount;
                let cartAmount = Math.round(totalAmountSum - discount);
                let finalAmount = (totalAmount + cartAmount).toFixed(2);

                $('#product_add').append(`
                <tr class="each_row" data-id="${id}">
                    <td class="text-center">${id} <input type="hidden" name="table_purchase_price[]" value="${purchase_price}"></td>
                    <td>${product_name}<input type="hidden" name="table_product_id[]" value="${product_id}"></td>
                    <td class="text-center">${sales_unit}</td>
                    <td class="text-end"><span class="quantity_text">${quantity}</span><input type="hidden" class="table_quantity" name="table_product_quantity[]" value="${quantity}"></td>
                    <td class="text-end">${sales_price}<input type="hidden" class="table_price" name="table_product_price[]" value="${sales_price}"></td>
                    <td class="text-end">${purchase_price}<input type="hidden" class="table_pur_price" name="table_product_pur_price[]" value="${purchase_price}"></td>
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
        $('#changeProduct').val('').select2('open'); // Re-open the select2 dropdown for product selection
        $('#product_name').val('');
        $('#sales_unit').val('');
        $('#sales_price').val('');
        $('#purchase_price').val('');
        $('#present_stock').val('');
        $('#productQuantity').val('');
        $('#quantityAmount').val('');
        $('#discount').val('');
        $('#percentageInput').val('');
        $('#percentageType').val('1');
        $('#percentageInputContainer').addClass('d-none');
        $('#discount').prop('readonly', false);
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

    // Event handler for updating the cart amount on quantity change
    $('#cart_product').on('keyup', 'input[name="table_product_quantity[]"]', function(e) {
        let quantity = $(this).val();
        let sales_price = parseFloat($(this).closest('.each_row').find('input[name="table_product_price[]"]')
            .val()) || 0;
        let discount = parseFloat($(this).closest('.each_row').find('input[name="table_product_discount[]"]')
            .val()) || 0;

        let newAmount = ((quantity * sales_price) - discount).toFixed(2);
        let currentTotal = parseFloat($(this).closest('.each_row').find('.cart_amount').text()) || 0;

        // Subtract the previous total amount of this row
        let netTotal = parseFloat($('#netTotalAmount').val()) || 0;
        netTotal += parseFloat(newAmount) - currentTotal;

        // Update the total amount
        $('#netTotalAmount').val(netTotal.toFixed(2));

        // Update the cart amount for this row
        $(this).closest('.each_row').find('.cart_amount').text(newAmount);
    });

    // -------------------------------- add to card end -----------------------------------------------------------

    // Event handler for changing the supplier and updating supplier details
    $('#changeSupplier').on('change', function() {
        let supplierID = $(this).val();
        if (supplierID) {
            $.ajax({
                url: 'supplierDetails/' + supplierID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    if (data) {
                        $('.supplierInfo').removeClass('d-none');
                        $('#jsdataerror').text('');
                        $('#supplierAdd').text('Total Debit: ' + data.total_debits);
                        $('#supplierAdd2').text('Total Credit: ' + data.total_credits);
                        $('#supplierAdd3').text('Net Balance: ' + data.net_balance);
                        $('#supplierAdd4').text('Mobile: ' + data.account_mobile);
                        $('#supplierAdd5').text('Address: ' + data.account_address);
                    } else {
                        $('#jsdataerror').text('Not Found');
                    }
                },
                error: function() {
                    $('#jsdataerror').text('Error fetching data');
                }
            });
        } else {
            $('#jsdataerror').text('Not Found');
        }
    });

    // Event handler for updating the return amount based on the given amount
    $('#givenAmount').on('keyup', function() {
        let totalAmount = parseFloat($('#netTotalAmount').val()) || 0;
        let givenAmount = parseFloat($(this).val()) || 0;
        let returnAmount = givenAmount - totalAmount;
        $('#returnAmount').val(returnAmount.toFixed(2));
    });

    // Event handler for when the paymentType 
    $('.paymentType').on('click', function() {
        var paymentType = $(this).data('value');
        var acListData;
        let url;
        // Show the appropriate element based on the selected option
        if (paymentType === 'Bank') {
            $('#bankDetails').removeClass('d-none');
            $('#mobileBankDetails').addClass('d-none');
            url = "{{ route('account_list', ['code' => ':code']) }}";
            url = url.replace(':code', 100020004);
        } else if (paymentType === 'mBank') {
            $('#bankDetails').addClass('d-none');
            $('#mobileBankDetails').removeClass('d-none');
            url = "{{ route('account_list', ['code' => ':code']) }}";
            url = url.replace(':code', 100020003);
        } else if (paymentType === 'Cash') {
            $('#bankDetails').addClass('d-none');
            $('#mobileBankDetails').addClass('d-none');
            url = "{{ route('account_list', ['code' => ':code']) }}";
            url = url.replace(':code', 100020002);
        }
        // console.log(url);
        $.ajax({
            url: url,
            type: "GET",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(response) {
                acListData = response.acList;
                // console.log(acListData);
                // Populate options in changeAccountsName select
                $('#changeAccountsName').empty();

                $('#changeAccountsName').append(
                    '<option value="" selected>Select Account Name</option>');
                if (acListData && Object.keys(acListData).length > 0) {
                    $.each(acListData, function(id, accountName) {
                        $('#changeAccountsName').append(
                            `<option value="${id}">${accountName}</option>`);
                    });
                } else {
                    $('#jsdataerror').text('Account names not found or empty.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching account names:', error);
                $('#jsdataerror').text('Error fetching account names.');
            }
        });

    })
    // Event handler for when the chequeType = Cheque BankInfo Show
    $('#chequeType').on('change', function() {
        let chequeType = $(this).val();
        if (chequeType == 'Cheque') {
            $('#bankInfo').removeClass('d-none'); // Correct way to remove the 'd-none' class
        } else {
            $('#bankInfo').addClass('d-none');
        }
    });

    // sales success after pdf open 
    var sessionInvoice = {!! json_encode(session('invoice')) !!};
    var sessionDeliveryChallan = {!! json_encode(session('delivery_challan')) !!};
    if (sessionInvoice) {
        window.open("{{ route('sales_invoice_details_pdf', '') }}/" + sessionInvoice, '_blank');
    }
    if (sessionDeliveryChallan) {
        window.open("{{ route('sales_challan_details_pdf', '') }}/" + sessionDeliveryChallan, '_blank');
    }
</script>
