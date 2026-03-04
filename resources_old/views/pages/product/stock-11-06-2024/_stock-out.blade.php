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
        <form id="salesform" method="POST" action="{{ route('sales.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row pt-10">
                <!-- Product add to card Content Row -->

                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12 card shadow">
                            <div class="card-header">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('Sales') }}</h1>
                                </div>
                                {{-- <label class="badge text-warning" id="jsdataerror"></label> --}}
                            </div>
                            <div class="card-body py-3">
                                <div class="col-md-12 pb-3">

                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>{{ __(' Product') }}</label>
                                            <select id="changeProduct" class="form-select form-select-sm"
                                                name="product_id" data-control="select2" data-hide-search="false">
                                                <option>Select Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->product_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="order">{{ __('Unit Name') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid" readonly
                                                id="sales_unit" name="sales_unit " />
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="order">{{ __('Price (Per Unit)') }}</label>
                                            <input type="number" class="form-control form-control-sm" id="sales_price"
                                                name="sales_price" step="1" min="0"
                                                oninput="this.value = this.value.replace(/\D/g, '');">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="order">{{ __('Present Stock') }}</label>
                                            <input type="number"
                                                class="form-control form-control-sm form-control-solid"
                                                id="present_stock" name="present_stock" step="1" min="0"
                                                oninput="this.value = this.value.replace(/\D/g, '');" readonly>
                                        </div>
                                        {{-- <div class="form-group col-md-3">
                                            <label for="order">{{ __('Quantity') }}</label>
                                            <input type="text" class="form-control form-control-sm"
                                                   id="productQuantity" name="quantity" step="1" min="0"
                                                   oninput="this.value = this.value.replace(/\D/g, '');">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="order">{{ __('Amount') }}</label>
                                            <input type="number"
                                                   class="form-control form-control-sm form-control-solid"
                                                   id="quantityAmount" name="Amount" step="1" pattern="\d+"
                                                   min="0" readonly>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="discount">{{ __('Discount') }}</label>
                                            <input type="number" class="form-control form-control-sm" id="discount"
                                                   name="discount" step="1" min="0"
                                                   oninput="this.value = this.value.replace(/\D/g, '');">
                                        </div> --}}
                                        <div class="form-group col-md-2">
                                            <label for="order">{{ __('Quantity') }}</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="productQuantity" name="quantity"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="order">{{ __('Amount') }}</label>
                                            <input type="number"
                                                class="form-control form-control-sm form-control-solid"
                                                id="quantityAmount" name="Amount" value=0 readonly />
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="">Discount Type</label>
                                            <select class="form-select form-select-sm" name="type"
                                                id="percentageType">
                                                <option value="">--Select Type--</option>
                                                <option value="1" selected>Fixed</option>
                                                <option value="2">Percentage</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2 d-none" id="percentageInputContainer">
                                            <label class=""><span class="">Percentage </span></label>
                                            <input type="text" class="form-control form-control-sm" name="percentage"
                                                id="percentageInput"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>

                                        <div class="form-group col-md-2" id="amountInputContainer">
                                            <label class=""><span class="required">Discount </span> </label>
                                            <input type="text" class="form-control form-control-sm" id="discount"
                                                name="discount"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <button type="button" id="addProduct"
                                                class="btn btn-sm btn-primary px-5 mt-6">{{ __('Add to Cart') }}</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 card shadow my-5 px-5">
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
                    </div>
                </div>
                <!-- Product sales summary Content Row -->
                <div class="col-md-4">
                    <div class="card shadow px-10 pb-8 ms-0 ms-md-5">
                        <div class="card-header mb-4">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h1 class="h3 mb-0 text-gray-800">{{ __('Sales Summary') }}</h1>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="order">{{ __('Net Total Amount') }}</label>
                            <input type="number" class="form-control form-control-sm form-control-solid"
                                id="netTotalAmount" name="netTotalAmount" value=0 readonly />
                        </div>

                        <div class="form-group">
                            <label>{{ __('Select Customer') }}</label>
                            <select id="changeCustomer" class="form-control form-control-sm" name="customer_id"
                                data-control="select2" data-placeholder="Select Customer" required>
                                <option></option>
                                @foreach ($customerAccounts as $customerAccount)
                                    <option value="{{ $customerAccount->id }}">{{ $customerAccount->account_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="card">
                            <label for="" id="customerAdd4"></label>
                            <label for="" id="customerAdd5"></label>
                            <label for="" id="customerAdd"></label>
                            <label for="" id="customerAdd2"></label>
                            <label for="" id="customerAdd3"></label>
                        </div>

                        {{-- <table id="customerAdd" class="table table-success table-striped">

                        </table> --}}
                        <!-- <div class="form-group">
                            <label for="order">{{ __('Invoice No.') }}</label>
                            <input type="text" class="form-control form-control-sm" id="" name="customer_invoice_no" value="" />
                        </div> -->

                        <div class="form-group">
                            <label>{{ __('Receive Account') }}</label>
                            <select id="" class="form-control form-control-sm" name="receive_account"
                                data-control="select2" data-placeholder="Select Payment Account">
                                <option></option>
                                @foreach ($toAccounts as $toAccount)
                                    <option value="{{ $toAccount->id }}">
                                        {{ $toAccount->account_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="givenAmount">{{ __('Receive Amount') }}</label>
                            <input type="text" class="form-control form-control-sm" id="givenAmount"
                                name="givenAmount" value="0"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                        </div>
                        <div class="form-group">
                            <label for="returnAmount"><span id="dueOrReturn"></span> Amount</label>
                            <input type="number" class="form-control form-control-sm form-control-solid"
                                id="returnAmount" name="returnAmount" value="0" readonly />
                        </div>
                        <div class="form-group">
                            <label for="remarks">{{ __('Remarks') }}</label>
                            <input type="text" class="form-control form-control-sm" id="remarks"
                                name="remarks" />
                        </div>
                        <button type="submit" id=""
                            class="btn btn-sm btn-success px-5 mt-5">{{ __('Sales') }}</button>
                            
                    </div>
                </div>
            </div>
        </form>
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

        if (productID) {
            $.ajax({
                url: 'productDetails/' + productID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {

                    if (data) {
                        $('#quantityAmount').val(0);
                        $('#productQuantity').val(0);
                        $('#discount').val(0);
                        $.each(data, function(key, value) {
                            var unit = value.unit.unit_name;
                            var price = value.sales_price;
                            var sum_stock_in = value.product_stock_sum_stock_in_quantity;
                            var sum_stock_out = value.product_stock_sum_stock_out_quantity;
                            var present_stock = (sum_stock_in - sum_stock_out).toFixed(2);

                            $('#sales_unit').val(unit);
                            $('#sales_price').val(price);
                            $('#present_stock').val(present_stock);
                        });
                    } else {

                    }
                }
            });
        } else {

        }
    });

    $('#productQuantity').on('keyup', function() {
        var quantity = $('#productQuantity').val();
        var purchasePrice = $('#sales_price').val();

        // Check if quantity and purchasePrice are not empty
        if (quantity && purchasePrice) {
            let totalAmount = quantity * purchasePrice;
            $('#quantityAmount').val(totalAmount.toFixed(2));
        } else {
            // Handle the case where either quantity or purchasePrice is empty
            $('#quantityAmount').val(0);
        }
    });

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

    let id = 1;
    $('#addProduct').on('click', function() {
        // $( "#myselect option:selected" ).text();
        let product_name = $('#changeProduct option:selected').text();
        let product_id = $('#changeProduct option:selected').val();
        let sales_unit = $('#sales_unit').val();
        let quantity = $('#productQuantity').val();
        let sales_price = $('#sales_price').val();
        let discount = $('#discount').val();
        let totalAmount = $('#netTotalAmount').val();

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
                    let cartAmount = (sales_price * quantity) - discount;
                    let sumAmount = parseFloat(cartAmount) + parseFloat(totalAmount);
                    let setAmount = $('#netTotalAmount').val(sumAmount);

                    $('#product_add').append(
                        `<tr class="each_row" data-id="${id}">
                            <td class="text-center">` + id + `</td>
                            <td>` + product_name + `<input type="hidden" name="table_product_id[]" value=` +
                        product_id + ` ></td>
                            <td class="text-center">` + sales_unit + `</td>
                        <td>
                            <input type="text" class="form-control table_quantity py-0 text-end" name="table_product_quantity[]" step="1" min="0"
                            oninput="this.value = this.value.replace(/\D/g, '');" 
                            value=` + quantity + `>
                        </td>
                            <td class="text-end">` + sales_price +
                        `<input type="hidden" class="table_price" name="table_product_price[]" value=` +
                        sales_price + ` ></td>
                            <td class="text-end">` + discount +
                        `<input type="hidden" class="table_discount" name="table_product_discount[]" value=` +
                        discount + ` ></td>
                            <td id="cart${id}" class="cart_amount text-end" >` + cartAmount + `</td>
                            <td class="text-center my-0"> 
                                <button type="button" data-id="${id}" class="add_product_delete btn btn-sm btn-danger py-1 px-3">X</button>
                            </td>
                        </tr>`
                    );
                    ++id;
                    // $('#changeProduct').val('');
                    $('#quantity').val('');
                }
            } else {
                alert("Please Input Quantity Number");
            }
        } else {
            alert("Please Select Product");
        }
    });

    // $('#product_add').on('click', 'input[name="table_product_quantity[]"]', function(e) {
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
        $('#netTotalAmount').val(totalAmount);
    });

    $('#cart_product').on('keyup', 'input[name="table_product_quantity[]"]', function(e) {
        var quantity = $(this).val();

        let sales_price = parseFloat($(this).closest('.each_row').find('input[name="table_product_price[]"]')
            .val()) || 0;
        let discount = parseFloat($(this).closest('.each_row').find('input[name="table_product_discount[]"]')
            .val()) || 0;

        let update_amount = (quantity * sales_price) - discount;

        let currentTotal = parseFloat($(this).closest('.each_row').find('.cart_amount').text()) || 0;

        // Subtract the previous total amount of this row
        let getAmount = parseFloat($('#netTotalAmount').val()) || 0;
        let calculateNetTotal = update_amount - currentTotal;
        let finalAmount = getAmount + calculateNetTotal;

        // Update the total amount
        $('#netTotalAmount').val(finalAmount);

        // Update the cart amount for this row
        $(this).closest('.each_row').find('.cart_amount').text(update_amount);
    });

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
</script>
