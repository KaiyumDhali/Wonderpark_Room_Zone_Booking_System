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
        <form id="ordereditform" method="POST" action="{{ route('order.update', $orders->id) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row pt-10">
                <!-- Content Row -->

                <div class="col-md-8">
                    {{-- <div class="row">
                        <div class="col-md-12 card shadow">
                            <div class="card-header px-2 mx-0">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('Order') }}</h1>
                                </div>
                            </div>
                            <div class="card-body py-3 px-2">
                                <div class="col-md-12 pb-3">
                                    <div class="row">
                                        <div class="form-group col-md-6 my-3">
                                            <label>{{ __('Select Product') }}</label>
                                            <select id="changeProduct" class="form-select form-select-sm" name="product_id"
                                                data-control="select2" data-hide-search="false">
                                                <option>Select Product</option>
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
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                readonly id="product_name" name="product_name " />
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Code') }}</label>
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                readonly id="product_code" name="product_code " />
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Unit Name') }}</label>
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                readonly id="sales_unit" name="sales_unit " />
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Price (Per Unit)') }}</label>
                                            <input type="text" class="form-control form-control-sm" id="sales_price"
                                                name="sales_price"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Present Stock') }}</label>
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                id="present_stock" name="present_stock" step="1" min="0"
                                                oninput="this.value = this.value.replace(/\D/g, '');" readonly>
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Quantity') }}</label>
                                            <input type="text" class="form-control form-control-sm" id="productQuantity"
                                                name="quantity"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Amount') }}</label>
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                id="quantityAmount" name="Amount" value=0 readonly />
                                        </div>
    
                                        <div class="form-group col-md-2 my-3">
                                            <label class="">Discount Type</label>
                                            <select class="form-select form-select-sm" data-control="select2" name="type" id="percentageType">
                                                <option value="">--Select Type--</option>
                                                <option value="1" selected>Fixed</option>
                                                <option value="2">Percentage</option>
                                            </select>
                                        </div>
    
                                        <div class="form-group col-md-2 d-none my-3" id="percentageInputContainer">
                                            <label class=""><span class="">Percentage </span></label>
                                            <input type="text" class="form-control form-control-sm" name="percentage"
                                                id="percentageInput"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>
    
                                        <div class="form-group col-md-2 my-3" id="amountInputContainer">
                                            <label class=""><span>Discount </span> </label>
                                            <input type="text" class="form-control form-control-sm" id="discount"
                                                name="discount"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>
    
                                        <div class="form-group col-md-2 my-3">
                                            <button type="button" id="addProduct"
                                                class="btn btn-sm btn-primary px-5 mt-6">{{ __('Add to Cart') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12 card shadow my-5 px-5 mx-0">
                            <div id="cart_product">
                                <table id="product_add" class="table table-bordered mt-4">
                                    <tr class="text-center">
                                        <th>SL</th>
                                        <th>Product Name</th>
                                        <th>Unit</th>
                                        <th class="w-100px">Quantity</th>
                                        <th>Sale Price</th>
                                        <th>Discount</th>
                                        <th>Amount</th>
                                        <th></th>
                                    </tr>
                                </table>

                                <table id="sumAmountShow" class="table table-bordered">

                                </table>
                            </div>
                        </div>
                    </div> --}}

                    <div class="row">
                        <div class="col-md-12 card shadow px-5">
                            <div class="card-header px-0">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('Order Edit') }}</h1>
                                </div>
                            </div>
                            <div id="cart_product_old py-3">
                                <table id="product_add" class="table table-bordered mt-4">
                                    <tr>
                                        <th class="w-40px">SL</th>
                                        <th class="w-200px">Product Name</th>
                                        <th class="w-50px">Unit</th>
                                        <th class="w-150px">Quantity</th>
                                        <th class="w-150px">Sale Price</th>
                                        <th class="w-150px">Discount</th>
                                        <th>Amount</th>
                                        <th class="w-100px">Delete</th>
                                    </tr>
                                </table>

                                <table id="sumAmountShowOld" class="table table-bordered">
                                    @php
                                        $sl=1;
                                    @endphp
                                    @foreach ($orders->orderdetail as $order)
                                        <tr>
                                            <input type="hidden" name="id[]" value="{{ $order->id }}">

                                            <td class="w-40px">
                                                {{ $sl }}
                                            </td>
                                            <td class="w-200px">
                                                <input type="hidden" class="form-control py-0" style=""
                                                    name="product_id[]" value="{{ $order->product_id }}">
                                                {{ $order->product->product_name }}
                                            </td>
                                            <td class="w-50px">
                                                {{ $order->product->unit->unit_name }}
                                            </td>
                                            <td class="w-150px">
                                                <input type="text" class="form-control table_quantity py-0"
                                                    style="" name="orderquantity[]"
                                                    value="{{ $order->stock_out_quantity }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                            </td>
                                            <td class="w-150px">
                                                <input type="text" class="form-control unit_price py-0"
                                                    style="" name="stock_out_unit_price[]"
                                                    value="{{ $order->stock_out_unit_price }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                            </td>
                                            <td class="w-150px">
                                                <input type="text" class="form-control discount py-0"
                                                    style="" name="stock_out_discount[]"
                                                    value="{{ $order->stock_out_discount }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                            </td>
                                            <td>
                                                <span class="total">{{ $order->stock_out_total_amount }}</span>
                                            </td>

                                            <td class="text-center my-0 w-100px">
                                                <a class="btn btn-sm btn-danger py-0"
                                                    onclick="return confirm('Are you sure you want to delete ?')"
                                                    href="{{ route('orderDetailsDestroy', $order->id) }}">
                                                    Delete</a>
                                            </td>
                                        </tr>
                                        @php
                                        $sl ++;
                                    @endphp
                                    @endforeach
                                    
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Content Row -->
                <div class="col-md-4">
                    <div class="card shadow px-10 pb-8 ms-0 ms-md-5">
                        <div class="card-header mb-4">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h1 class="h3 mb-0 text-gray-800">{{ __('Order Summary') }}</h1>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="order">{{ __('Net Total Amount') }}</label>
                            <input type="number" class="form-control form-control-sm form-control-solid"
                                id="totalSumLabel" value=0 readonly />
                        </div>
                        <div class="form-group">
                            <label for="order">{{ __('Customer Name') }}</label>
                            <input type="text" class="form-control form-control-sm form-control-solid"
                                id="" value="{{ $orders->customer->account_name }}" readonly />
                        </div>
                        <div class="form-group">
                            <label for="order">{{ __('Mobile Number') }}</label>
                            <input type="text" class="form-control form-control-sm form-control-solid"
                                id="" value="{{ $orders->customer->account_mobile }}" readonly />
                        </div>
                        <div class="form-group">
                            <label for="remarks">{{ __('Order Date') }}</label>
                            <input type="date" class="form-control form-control-sm" id="orderdate"
                                value="{{ $orders->order_date }}" name="order_date" />
                        </div>
                        <div class="form-group">
                            <label for="remarks">{{ __('Delivery Date') }}</label>
                            <input type="date" class="form-control form-control-sm" id="deliverydate"
                                value="{{ $orders->delivery_date }}" name="delivery_date" />
                        </div>
                        <div class="form-group">
                            <label for="remarks">{{ __('Remarks') }}</label>
                            <input type="text" class="form-control form-control-sm" id="remarks"
                                value="{{ $orders->remarks }}" name="remarks" />
                        </div>

                        {{-- <div class="form-group">
                            <label for="remarks">{{ __('Attach File') }}</label>
                            <input type="file" class="form-control form-control-sm" id="file"
                                name="file" />
                        </div> --}}

                        <div class="form-group">
                            <label for="remarks">{{ __('Status') }}</label>
                            <select class="form-select form-select-sm" data-control="select2" name="status">
                                <option value="">--Select Status--</option>
                                <option value="0" {{ $orders->status == 0 ? 'selected' : '' }}>Pending</option>
                                <option value="1" {{ $orders->status == 1 ? 'selected' : '' }}>Approved</option>
                            </select>
                        </div>
                        <button type="submit" id="" class="btn btn-sm btn-success px-5 mt-5">{{ __('Order Update') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-default-layout>

<script type="text/javascript">
    $(document).ready(function() {
        // Attach change event handlers to table_quantity, unit_price, and discount inputs
        $('.table_quantity, .unit_price, .discount').on('keyup', function() {
            // Get the quantity, unit price, and discount values
            var quantity = parseFloat($(this).closest('tr').find('.table_quantity').val()) || 0;
            var unitPrice = parseFloat($(this).closest('tr').find('.unit_price').val()) || 0;
            var discount = parseFloat($(this).closest('tr').find('.discount').val()) || 0;


            // Calculate the total amount with discount
            var totalAmount = (quantity * unitPrice) - discount;

            // Update the total span with the calculated total amount
            $(this).closest('tr').find('.total').text(totalAmount.toFixed(2));
        });

        // Update the total sum function
        function updateTotalSum() {
            var totalSum = 0;
            $('.total').each(function() {
                totalSum += parseFloat($(this).text()) || 0;
            });
            $('#totalSumLabel').val(totalSum.toFixed(2));
        }
        // Initial update
        updateTotalSum();

        // Attach input event handler to relevant inputs
        $('.table_quantity, .unit_price, .discount').on('keyup', function() {
            // Update the total sum when any relevant input changes
            updateTotalSum();
        });

    });

    document.getElementById("ordereditform").addEventListener("keydown", function(event) {
        // Check if the pressed key is Enter
        if (event.key === "Enter") {
            // Prevent default form submission
            event.preventDefault();
        }
    });

    // Function to fetch product details based on selected product ID
    function fetchProductDetails(productID) {
        let url = '{{ route('productDetails', ['id' => ':productID']) }}';
        url = url.replace(':productID', productID);
        $.ajax({
            url: url,
            type: "GET",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(data) {
                if (data) {
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
                    $('#sales_price').val(product.sales_price);
                    $('#present_stock').val(present_stock);
                    $('#productQuantity').val(product.pack_size);
                    $('#quantityAmount').val(product.pack_size * product.sales_price);
                    // product focus
                    $('#productQuantity').focus();
                    // click add product
                    // $('#addProduct').click();
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
        if (productID) {
            fetchProductDetails(productID);
        }
    });

    // Event handler for adding the selected product to the cart
    let id = 1;
    $('#addProduct').on('click', function() {
        let product_id = $('#changeProduct').val();
        let product_name = $('#product_name').val();
        let sales_unit = $('#sales_unit').val();
        let quantity = $('#productQuantity').val();
        let sales_price = $('#sales_price').val();
        let discount = $('#discount').val();
        let totalAmount = parseFloat($('#netTotalAmount').val()) || 0;

        if (product_id && quantity > 0) {
            let duplicate_found = false;
            let existingRow;

            $('#product_add tr').each(function() {
                if ($(this).find('input[name="table_product_id[]"]').val() === product_id) {
                    duplicate_found = true;
                    existingRow = $(this);
                    return false; // break out of loop if duplicate found
                }
            });

            if (duplicate_found) {
                // Update the existing row
                let oldQuantity = parseFloat(existingRow.find('.table_quantity').val()) || 0;
                let newQuantity = oldQuantity + parseFloat(quantity);
                let oldDiscount = parseFloat(existingRow.find('.table_discount').val()) || 0;
                let newDiscount = oldDiscount + parseFloat(discount || 0);
                let oldAmount = parseFloat(existingRow.find('.cart_amount').text()) || 0;
                let newAmount = (newQuantity * sales_price - newDiscount).toFixed(2);

                // Update the row with the new quantity and new amount
                existingRow.find('.table_quantity').val(newQuantity).next().val(newQuantity);
                existingRow.find('.table_discount').val(newDiscount).next().val(newDiscount);
                existingRow.find('.quantity_text').text(newQuantity);
                existingRow.find('.discount_text').text(newDiscount);
                existingRow.find('.cart_amount').text(newAmount);

                // Update the net total by adjusting for the difference between the old and new amounts
                let netTotal = totalAmount - oldAmount + parseFloat(newAmount);
                $('#netTotalAmount').val(netTotal.toFixed(2));
            } else {
                // Adding a new row if no duplicate is found
                let totalAmountSum = quantity * sales_price;
                let cartAmount = (totalAmountSum - discount).toFixed(2);
                let finalAmount = (totalAmount + parseFloat(cartAmount)).toFixed(2);

                $('#product_add').append(`
                    <tr class="each_row" data-id="${id}">
                        <td class="text-center">${id}</td>
                        <td>${product_name}<input type="hidden" name="table_product_id[]" value="${product_id}"></td>
                        <td class="text-center">${sales_unit}</td>
                        <td class="text-end"><span class="quantity_text">${quantity}</span><input type="hidden" class="table_quantity" name="table_product_quantity[]" value="${quantity}"></td>
                        <td class="text-end">${sales_price}<input type="hidden" class="table_price" name="table_product_price[]" value="${sales_price}"></td>
                        <td class="text-end"><span class="discount_text">${discount}</span><input type="hidden" class="table_discount" name="table_product_discount[]" value="${discount}"></td>
                        <td id="cart${id}" class="cart_amount text-end">${cartAmount}</td>
                        <td class="text-center"><button type="button" data-id="${id}" class="add_product_delete btn btn-sm btn-danger py-1 px-3">X</button></td>
                    </tr>
                `);
                id++;
                $('#netTotalAmount').val(finalAmount);

            }
            // Clear the input fields after successfully adding the product
            $('#changeProduct').val('');
            $('#changeProduct').select2('open');

            $('#product_name').val('');
            $('#product_code').val('');
            $('#sales_unit').val('');
            $('#sales_price').val('');
            $('#present_stock').val('');
            $('#productQuantity').val('');
            $('#quantityAmount').val('');

            $('#discount').val('');
            $('#percentageInput').val('');
            $('#percentageType').val('1');

            $('#percentageInputContainer').addClass('d-none');
            $('#discount').prop('readonly', false);

        } else {
            alert(product_id ? "Please Input Quantity Number" : "Please Select Product");
        }
    });

    // Trigger the addProduct function on Enter key press for inputs
    $('input').on('keypress', function(event) {
        if (event.key === "Enter") {
            $('#addProduct').click();
        }
    });
    
    // Event handler for calculating quantity amount on keyup
    function onChangeCalculateTotalAmount() {
        let quantity = $('#productQuantity').val();
        let sales_price = $('#sales_price').val();

        if (quantity && sales_price) {
            let totalAmount = quantity * sales_price;
            $('#quantityAmount').val(totalAmount.toFixed(2));
        } else {
            $('#quantityAmount').val(0);
        }
    }
    $('#productQuantity, #sales_price').on('keyup', onChangeCalculateTotalAmount);

    // Event handler for changing the discount percentage type
    $('#percentageType').on('change', function() {
        // $('#discount').val();
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

    // Event handler for removing a product from the cart
    $('#product_add').on('click', function(e) {
        let totalAmount = parseFloat($('#netTotalAmount').val()) || 0;
        let cartAmount = 0;
        if (e.target.matches('.add_product_delete')) {
            let id = e.target.dataset.id;
            let selector = `tr[data-id="${id}"]`;
            let cartAmountElement = $(`#cart${id}`);
            cartAmount = parseFloat(cartAmountElement.text()) || 0;

            // Update totalAmount by subtracting cartAmount
            totalAmount -= cartAmount;

            // Remove the row
            $(selector).remove();
        }
        // Update the netTotalAmount input
        $('#netTotalAmount').val(totalAmount.toFixed(2));
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

    // $('#changeProduct').on('change', function() {
    //     var productID = $(this).val();
    //     let url = '{{ route('orderProductDetails', ['productID' => ':productID']) }}';
    //     url = url.replace(':productID', productID);

    //     if (productID) {
    //         $.ajax({
    //             url: url,
    //             type: "GET",
    //             data: {
    //                 "_token": "{{ csrf_token() }}"
    //             },
    //             dataType: "json",
    //             success: function(data) {
    //                 //                console.log(data);
    //                 if (data) {
    //                     $('#quantityAmount').val(0);
    //                     $('#productQuantity').val(0);
    //                     $('#discount').val(0);
    //                     $.each(data, function(key, value) {
    //                         var unit = value.unit.unit_name;
    //                         var price = value.sales_price;
    //                         var sum_stock_in = value.product_stock_sum_stock_in_quantity;
    //                         var sum_stock_out = value.product_stock_sum_stock_out_quantity;
    //                         var present_stock = sum_stock_in - sum_stock_out;

    //                         $('#sales_unit').val(unit);
    //                         $('#sales_price').val(price);
    //                         $('#present_stock').val(present_stock);
    //                     });
    //                 } else {

    //                 }
    //             }
    //         });
    //     } else {

    //     }
    // });

    // $('#productQuantity').on('keyup', function() {
    //     var quantity = $('#productQuantity').val();
    //     var purchasePrice = $('#sales_price').val();

    //     // Check if quantity and purchasePrice are not empty
    //     if (quantity && purchasePrice) {
    //         let totalAmount = quantity * purchasePrice;
    //         $('#quantityAmount').val(totalAmount);
    //     } else {
    //         // Handle the case where either quantity or purchasePrice is empty
    //         $('#quantityAmount').val(0);
    //     }
    // });

    // let id = 0;
    // $('#addProduct').on('click', function() {
    //     // $( "#myselect option:selected" ).text();
    //     let product_name = $('#changeProduct option:selected').text();
    //     let product_id = $('#changeProduct option:selected').val();
    //     let sales_unit = $('#sales_unit').val();
    //     let quantity = $('#productQuantity').val();
    //     let sales_price = $('#sales_price').val();
    //     let discount = $('#discount').val();
    //     let totalAmount = $('#netTotalAmount').val();

    //     if (product_id > 0) {
    //         if (quantity > 0) {
    //             // Check for duplicate consume_product_id
    //             let duplicate_found = false;
    //             $('#product_add tr').each(function() {
    //                 if ($(this).find('input[name="table_product_id[]"]').val() === product_id) {
    //                     duplicate_found = true;
    //                     return false; // break out of loop if duplicate found
    //                 }
    //             });

    //             if (duplicate_found) {
    //                 alert('This product has already been added to the list.');
    //             } else {
    //                 let cartAmount = (sales_price * quantity) - discount;
    //                 let sumAmount = parseFloat(cartAmount) + parseFloat(totalAmount);
    //                 let setAmount = $('#netTotalAmount').val(sumAmount);

    //                 $('#product_add').append(
    //                     `<tr class="each_row" data-id="${id}">
    //                         <td class="text-center my-0"> <button data-id="${id}" class="delete btn btn-sm btn-danger py-0" >Delete</button></td>
    //                         <td>` + product_name + `<input type="hidden" name="table_product_id[]" value=` +
    //                     product_id + ` ></td>
    //                         <td>` + sales_unit + `</td>
    //                         <td>
    //                             <input type="text" class="form-control table_quantity py-0" name="table_product_quantity[]" step="1" min="0"
    //                             oninput="this.value = this.value.replace(/\D/g, '');" 
    //                             value=` + quantity + `>
    //                         </td>
    //                         <td>` + sales_price +
    //                     `<input type="hidden" class="table_price" name="table_product_price[]" value=` +
    //                     sales_price + ` ></td>
    //                             <td>` + discount +
    //                     `<input type="hidden" class="table_discount" name="table_product_discount[]" value=` +
    //                     discount + ` >
    //                         </td>
    //                         <td id="cart${id}" class="cart_amount" >` + cartAmount + `</td>
    //                     </tr>`
    //                 );
    //                 ++id;
    //                 // $('#changeProduct').val('');
    //                 $('#quantity').val('');
    //             }
    //         } else {
    //             alert("Please Input Quantity Number");
    //         }
    //     } else {
    //         alert("Please Select Product");
    //     }
    // });

    // // $('#product_add').on('click', 'input[name="table_product_quantity[]"]', function(e) {
    // $('#product_add').on('click', function(e) {
    //     let totalAmount = parseFloat($('#netTotalAmount').val()) || 0;
    //     let cartAmount = 0;

    //     if (e.target.matches('.delete')) {
    //         const {
    //             id
    //         } = e.target.dataset;
    //         const selector = `tr[data-id="${id}"]`;
    //         const cartAmountElement = $('#cart' + id);
    //         cartAmount = parseFloat(cartAmountElement.text()) || 0;

    //         // Update totalAmount by subtracting cartAmount
    //         totalAmount -= cartAmount;

    //         // Remove the row
    //         $(selector).remove();
    //     }

    //     // Update the netTotalAmount input
    //     $('#netTotalAmount').val(totalAmount);
    // });
    // $('#cart_product').on('keyup', 'input[name="table_product_quantity[]"]', function(e) {
    //     var quantity = $(this).val();

    //     let sales_price = parseFloat($(this).closest('.each_row').find('input[name="table_product_price[]"]')
    //         .val()) || 0;
    //     let discount = parseFloat($(this).closest('.each_row').find('input[name="table_product_discount[]"]')
    //         .val()) || 0;

    //     let update_amount = (quantity * sales_price) - discount;

    //     let currentTotal = parseFloat($(this).closest('.each_row').find('.cart_amount').text()) || 0;

    //     // Subtract the previous total amount of this row
    //     let getAmount = parseFloat($('#netTotalAmount').val()) || 0;
    //     let calculateNetTotal = update_amount - currentTotal;
    //     let finalAmount = getAmount + calculateNetTotal;

    //     // Update the total amount
    //     $('#netTotalAmount').val(finalAmount);

    //     // Update the cart amount for this row
    //     $(this).closest('.each_row').find('.cart_amount').text(update_amount);
    // });

    // $('#givenAmount').on('keyup', function() {
    //     var quantity = $('#givenAmount').val();
    //     var netTotalAmount = $('#netTotalAmount').val();
    //     var givenAmount = $('#givenAmount').val();
    //     let returnAmount = netTotalAmount - givenAmount;
    //     $('#returnAmount').val(returnAmount);
    // });
</script>
