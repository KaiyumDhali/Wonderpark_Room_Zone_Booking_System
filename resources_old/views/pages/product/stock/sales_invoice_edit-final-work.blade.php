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
        <div class="row ">
            <!-- Product Add to card Content Row -->
            <div class="col-md-8">
                <div class="row">
                    {{-- select product --}}
                    <div class="col-md-12 card shadow">
                        <div class="card-header px-2 mx-0">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h1 class="h3 mb-0 text-gray-800">{{ __('Sales Update') }}</h1>
                            </div>
                        </div>
                        <div class="card-body py-3 px-2 mx-0">
                            <div class="col-md-12 pb-3">
                                <div class="row">
                                    <div class="form-group col-md-6 my-3">
                                        <label>{{ __('Select Product') }}</label>
                                        <select id="changeProduct" class="form-select form-select-sm" name="product_id"
                                            data-control="select2" data-hide-search="false">
                                            <option selected disabled>Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">
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
                                            id="present_stock" name="present_stock" readonly />
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
                                        <select class="form-select form-select-sm" name="type" id="percentageType">
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
                    <form class="px-0" id="salesform" method="POST"
                        action="{{ route('sales_invoice_update', $invoiceNo) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        {{-- add to product --}}
                    <div class="col-12 card shadow my-5 px-5">
                        <div id="cart_product">
                            <table id="product_add" class="table table-bordered mt-4">
                                <tr class="text-center">
                                    <th>SL</th>
                                    <th>Product Name</th>
                                    <th>Unit</th>
                                    <th class="w-100px">Quantity</th>
                                    <th>sales Price</th>
                                    <th>Discount</th>
                                    <th>Amount</th>
                                    <th></th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- @foreach ($collection as $item)
            @endforeach --}}
            <!--  sales Summary Content Row -->
            <div class="col-md-4">
                <div class="card shadow px-10 pb-8 ms-0 ms-md-5">
                    <div class="card-header my-3 mx-0 px-2">
                        <div class="d-sm-flex align-items-center justify-content-between">
                            <h1 class="h3 mb-0 text-gray-800">{{ __('Sales Summary') }}</h1>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="order">{{ __('Net Total Amount') }}</label>
                        <input type="number" class="form-control form-control-sm form-control-solid"
                            id="netTotalAmount" name="netTotalAmount" value=0 readonly />

                        <input type="hidden" class="form-control form-control-sm form-control-solid"
                            id="voucher_no" name="voucher_no" value="{{ $customerFinance->voucher_no }}"/>
                        <input type="hidden" class="form-control form-control-sm form-control-solid"
                            id="delivery_challan_no" name="delivery_challan_no" value="{{ $customerFinance->delivery_challan_no }}"/>
                    </div>
                    <div class="form-group">
                        <label for="order">{{ __('Sales Date') }}</label>
                        <input type="date" class="form-control form-control-sm form-control-solid"
                            id="voucher_date" name="voucher_date" value="{{ $customerFinance->voucher_date }}"/>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 d-flex align-items-center">
                        <div class="flex-grow-1 pt-2">
                            <label>{{ __('Select Customer') }}</label>
                            <select id="changeCustomer" class="form-control form-control-sm" name="customer_id"
                                data-control="select2" data-placeholder="Select Customer" required>
                                <option></option>
                                @foreach ($customerAccounts as $customerAccount)
                                    <option value="{{ $customerAccount->id }}"
                                        @if ($customerAccount->id == $stocks[0]->customer_id) selected @endif>
                                        {{ $customerAccount->account_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <a href="#" class="btn btn-sm btn-flex btn-light-primary ms-2 mt-8"
                            data-bs-toggle="modal" data-bs-target="#add_supplier_modal">
                            Add
                        </a>
                    </div>

                    <div class="card p-5 mb-4 d-none" id="customerInfo">
                        <label for="" id="customerAdd4"></label>
                        <label for="" id="customerAdd5"></label>
                        <label for="" id="customerAdd"></label>
                        <label for="" id="customerAdd2"></label>
                        <label for="" id="customerAdd3"></label>
                    </div>
                    <div class="form-group">
                        <label>{{ __('From Account') }}</label>
                        <select id="" class="form-control form-control-sm" name="receive_account"
                            data-control="select2" data-placeholder="Select Received Account">
                            <option></option>
                            @foreach ($toAccounts as $toAccount)
                                <option value="{{ $toAccount->id }}"
                                    @if ($customerPayment && $customerPayment->to_acc_name && $toAccount->account_name == $customerPayment->to_acc_name) 
                                        selected 
                                    @endif>

                                    {{ $toAccount->account_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="givenAmount">{{ __('Received Amount') }}</label>
                        <input type="text" class="form-control form-control-sm" id="givenAmount"
                            name="givenAmount" value="{{ $customerPayment->amount ?? 0 }}"
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                    </div>
                    <div class="form-group">
                        <label for="returnAmount"><span id="dueOrReturn"></span> Amount</label>
                        <input type="number" class="form-control form-control-sm form-control-solid"
                            id="returnAmount" name="returnAmount" value="0" readonly />
                    </div>
                    <div class="form-group">
                        <label for="remarks">{{ __('Remarks') }}</label>
                        <input type="text" class="form-control form-control-sm" id="remarks" name="remarks"
                            value="{{ $stocks[0]->remarks }}" />
                    </div>
                    <button type="submit" id=""
                        class="btn btn-sm btn-success px-5 mt-5">{{ __('Sales update') }}</button>
                </div>
            </div>
            </form>
        </div>
    </div>



</x-default-layout>

<script type="text/javascript">
    document.getElementById("salesform").addEventListener("keydown", function(event) {
        // Check if the pressed key is Enter
        if (event.key === "Enter") {
            // Prevent default form submission
            event.preventDefault();
        }
    });

    $('#changeProduct').on('change', function() {
        var productID = $(this).val();
        var url = '{{ route('productDetails', ':productID') }}';
        url = url.replace(':productID', productID);
        if (productID) {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {

                    if (data) {
                        // Clear the values
                        $('#quantityAmount').val('');
                        $('#productQuantity').val('');
                        $('#discount').val('');
                        $.each(data, function(key, value) {
                            var product_name = value.product_name;
                            var product_code = value.product_code;
                            var unit = value.unit.unit_name;
                            var price = value.sales_price;
                            var sum_stock_out = value.product_stock_sum_stock_out_quantity;
                            var sum_stock_out = value.product_stock_sum_stock_out_quantity;
                            var present_stock = (sum_stock_out - sum_stock_out).toFixed(2);

                            $('#product_name').val(product_name);
                            $('#product_code').val(product_code);
                            $('#sales_unit').val(unit);
                            $('#sales_price').val(price);
                            $('#present_stock').val(present_stock);

                            // set foucus
                            $('#productQuantity').focus();
                        });
                    } else {

                    }
                }
            });
        } else {

        }
    });

    // $('#productQuantity').on('keyup', function() {
    //     var quantity = $('#productQuantity').val();
    //     var salesPrice = $('#sales_price').val();

    //     // Check if quantity and salesPrice are not empty
    //     if (quantity && salesPrice) {
    //         let totalAmount = quantity * salesPrice;
    //         $('#quantityAmount').val(totalAmount.toFixed(3));
    //     } else {
    //         // Handle the case where either quantity or salesPrice is empty
    //         $('#quantityAmount').val(0);
    //     }
    // });

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
    
    $('#percentageType').on('change', function() {
        if ($(this).val() === '2') {
            $('#percentageInputContainer').removeClass('d-none');
            $('#discount').prop('readonly', true);
        } else {
            $('#percentageInputContainer').addClass('d-none');

            $('#discount').prop('readonly', false);
        }
    });

    $('#percentageInput').on('keyup', function() {
        const percentageValue = $(this).val();
        var quantityAmount = $('#quantityAmount').val();
        const totalAmount = parseFloat(quantityAmount);
        const discountAmount = (percentageValue / 100) * totalAmount;

        // console.log(percentageValue, employeeSalaryAmount, calculatedAmount);
        $('#discount').val(discountAmount.toFixed(2));
    });

    // Trigger the addProduct function on Enter key press
    $('input').on('keypress', function(event) {
        if (event.key === "Enter") {
            $('#addProduct').click();
        }
    });

    // getData array is from your backend PHP data
    var getData = @json($stocks);
    console.log('getData', getData);
    let id = 1;

    function addProductRow(product) {
        let cartAmount = product.quantity_amount - product.discount;
            cartAmount =  Math.round(cartAmount);
        let sumAmount = parseFloat(cartAmount) + parseFloat($('#netTotalAmount').val());
        $('#netTotalAmount').val(sumAmount);

        var row =
            `<tr class="each_row" data-id="${id}">
                <td class="text-center">` + id + `</td>
                <td>` + product.product_name + `<input type="hidden" name="table_product_id[]" value=` +
            product.product_id + ` ></td>
                <td class="text-center">` + product.sales_unit + `</td>
                <td>` + product.quantity + `
                    <input type="hidden" class="form-control table_quantity py-0 my-0 text-end" name="table_product_quantity[]" 
                    step="1" min="0" oninput="this.value = this.value.replace(/\D/g, '');" 
                    value="` + product.quantity + `">
                </td>
                <td class="text-end">` + product.sales_price +
            `<input type="hidden" name="table_product_price[]" value=` + product.sales_price + ` ></td>
                <td class="text-end">` + product.discount +
            `<input type="hidden" name="table_product_discount[]" value=` + product.discount + ` ></td>
                <td id="cart${id}" class="text-end"><span class="cart_amount">${cartAmount}</span><input type="hidden" class="table_cart_amount" name="table_product_cart_amount[]" value="${cartAmount}"></td>
                <td class="text-center my-0"> 
                    <button type="button" data-id="${id}" class="add_product_delete btn btn-sm btn-danger py-1 px-3">X</button>
                </td>
            </tr>`;
        $('#product_add').append(row);
        ++id;
    }

    $('#addProduct').on('click', function() {
        let product_name = $('#changeProduct option:selected').text();
        let product_id = $('#changeProduct option:selected').val();
        let sales_unit = $('#sales_unit').val();
        let quantity = $('#productQuantity').val();
        let sales_price = $('#sales_price').val();
        let discount = $('#discount').val();
        let quantity_amount = $('#quantityAmount').val() || 0;

        if (product_id > 0) {
            if (quantity > 0) {
                // Check for duplicate consume_product_id
                let duplicate_found = false;
                $('#product_add tr').each(function() {
                    if ($(this).find('input[name="table_product_id[]"]').val() === product_id) {
                        duplicate_found = true;
                        return false; // break out of loop if duplicate found
                    }
                });

                if (duplicate_found) {
                    alert('This product has already been added to the list.');
                } else {
                    // Create product object
                    let product = {
                        product_name: product_name,
                        product_id: product_id,
                        sales_unit: sales_unit,
                        quantity: quantity,
                        sales_price: sales_price,
                        discount: discount,
                        quantity_amount: quantity_amount
                    };

                    // Add row
                    addProductRow(product);
                }
            } else {
                alert("Please Input Quantity Number");
            }
        } else {
            alert("Please Select Product");
        }
    });

    // Function to loop through getData array and dynamically add rows
    function loadProductsFromData() {
        getData.forEach(product => {
            const stock_out_total_amount = parseFloat(product.stock_out_total_amount) || 0; // Convert to number, default to 0 if NaN
            const discount = parseFloat(product.stock_out_discount) || 0; // Convert to number, default to 0 if NaN

            addProductRow({
                product_id: product.product_id || 0, // If product_id is null, use 0
                product_name: product.product?.product_name ||
                'Unknown Product', // Handle null product or product_name
                sales_unit: product.unit?.unit_name || 'N/A', // Handle null unit or unit_name
                quantity: product.stock_out_quantity || 0, // If quantity is null, use 0
                sales_price: product.stock_out_unit_price || 0, // If sales_price is null, use 0
                // discount: product.stock_out_discount || 0, // If discount is null, use 0
                // quantity_amount: product.stock_out_total_amount || 0 // If discount is null, use 0
                discount: discount, // If discount is null, use 0
                quantity_amount: stock_out_total_amount + discount, // Add total amount and discount
            });
        });
    }
    // Call this function if you want to load products dynamically from getData on page load
    loadProductsFromData();

    $('#product_add').on('click', function(e) {
        let totalAmount = parseFloat($('#netTotalAmount').val()) || 0;
        let cartAmount = 0;

        if (e.target.matches('.add_product_delete')) {
            const {
                id
            } = e.target.dataset;
            const selector = `tr[data-id="${id}"]`;
            const cartAmountElement = $('#cart' + id);
            cartAmount = parseFloat(cartAmountElement.text()) || 0;

            // Update totalAmount by subtracting cartAmount
            totalAmount -= cartAmount;

            // Remove the row
            $(selector).remove();
        }

        // Update the netTotalAmount input
        $('#netTotalAmount').val(totalAmount.toFixed(2));
    });

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

    // Event handler for changing the customer and updating customer details
    $('#changeCustomer').on('change', function() {
        var acName = $(this).val();
        if (acName) {
            var url = '{{ route('productCustomerDetails', ['id' => ':ac_name']) }}'.replace(':ac_name', acName);
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
                        // console.log(data);
                        $('#jsdataerror').text('');
                        $('#customerAdd').text('Total Debit: ' + data.total_debits);
                        $('#customerAdd2').text('Total Credit: ' + data.total_credits);
                        $('#customerAdd3').text('Net Balance: ' + data.net_balance);
                        $('#customerAdd4').text('Mobile: ' + data.account_mobile);
                        $('#customerAdd5').text('Address: ' + data.account_address);
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

    $('#givenAmount').on('keyup', function() {
        var quantity = $('#givenAmount').val();
        var netTotalAmount = $('#netTotalAmount').val();
        var givenAmount = $('#givenAmount').val();
        let returnAmount = (netTotalAmount - givenAmount).toFixed(2);
        if (returnAmount >= 0) {
            $('#dueOrReturn').text('Due');
        } else {
            $('#dueOrReturn').text('Return');
        }
        $('#returnAmount').val(returnAmount);
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
