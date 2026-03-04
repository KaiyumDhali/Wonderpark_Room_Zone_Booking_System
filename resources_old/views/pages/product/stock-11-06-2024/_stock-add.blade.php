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
        <form id="purchaseform" method="POST" action="{{ route('purchase.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row pt-10">
                <!-- Product Add to card Content Row -->
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12 card shadow">
                            <div class="card-header">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('Purchase') }}</h1>
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
                                                {{-- <select class="form-select p-2 form-select-solid" data-control="select2" data-hide-search="false"> --}}

                                                <option selected disabled>Select Product</option>
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
                                                id="purchase_unit" name="purchase_unit " />
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="order">{{ __('Price (Per Unit)') }}</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="purchase_price" name="purchase_price" step="1" min="0"
                                                oninput="this.value = this.value.replace(/\D/g, '');">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="order">{{ __('Present Stock') }}</label>
                                            <input type="number"
                                                class="form-control form-control-sm form-control-solid"
                                                id="present_stock" name="present_stock" readonly />
                                        </div>
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

                                        {{-- <div class="form-group col-md-3">
                                            <label for="discount">{{ __('Discount') }}</label>
                                            <input type="text" class="form-control form-control-sm" id="discount" name="discount"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div> --}}
                                        <div class="form-group col-md-2">
                                            <button type="button" id="addProduct"
                                                class="btn btn-sm btn-primary px-5 mt-6">{{ __('Add to Cart') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 card shadow my-5 px-5">
                            <div id="cart_product">
                                <table id="product_add" class="table table-bordered mt-4">
                                    <tr class="text-center">
                                        <th>SL</th>
                                        <th>Product Name</th>
                                        <th>Unit</th>
                                        <th class="w-100px">Quantity</th>
                                        <th>Purchase Price</th>
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
                <!--  Purchase Summary Content Row -->
                <div class="col-md-4">
                    <div class="card shadow px-10 pb-8">
                        <div class="card-header mb-4">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h1 class="h3 mb-0 text-gray-800">{{ __('Purchase Summary') }}</h1>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="order">{{ __('Net Total Amount') }}</label>
                            <input type="number" class="form-control form-control-sm form-control-solid"
                                id="netTotalAmount" name="netTotalAmount" value=0 readonly />
                        </div>
                        <div class="form-group">
                            <label>{{ __('Select Supplier') }}</label>
                            <select id="changeSupplier" class="form-control form-control-sm" name="supplier_id"
                                data-control="select2" data-placeholder="Select Supplier" required>
                                <option></option>
                                @foreach ($supplierAccounts as $supplierAccount)
                                    <option value="{{ $supplierAccount->account_name }}">
                                        {{ $supplierAccount->account_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="card">
                            <label for="" id="supplierAdd4"></label>
                            <label for="" id="supplierAdd5"></label>
                            <label for="" id="supplierAdd"></label>
                            <label for="" id="supplierAdd2"></label>
                            <label for="" id="supplierAdd3"></label>
                        </div>
                        {{-- <table id="supplierAdd" class="table table-success table-striped">

                        </table> --}}

                        <div class="form-group">
                            <label for="order">{{ __('Supplier Invoice No.') }}</label>
                            <input type="text" class="form-control form-control-sm" id=""
                                name="supplier_invoice_no" value="" />
                        </div>

                        <div class="form-group">
                            <label>{{ __('From Account') }}</label>
                            <select id="" class="form-control form-control-sm" name="pay_account"
                                data-control="select2" data-placeholder="Select Payment Account">
                                <option></option>
                                @foreach ($fromAccounts as $fromAccount)
                                    <option value="{{ $fromAccount->id }}">
                                        {{ $fromAccount->account_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="givenAmount">{{ __('Paid Amount') }}</label>
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
                            class="btn btn-sm btn-success px-5 mt-5">{{ __('Purchase') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>


</x-default-layout>

<script type="text/javascript">
    document.getElementById("purchaseform").addEventListener("keydown", function(event) {
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
                            var price = value.purchase_price;
                            var sum_stock_in = value.product_stock_sum_stock_in_quantity;
                            var sum_stock_out = value.product_stock_sum_stock_out_quantity;
                            var present_stock = (sum_stock_in - sum_stock_out).toFixed(2);

                            $('#purchase_unit').val(unit);
                            $('#purchase_price').val(price);
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
        var purchasePrice = $('#purchase_price').val();

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
        let purchase_unit = $('#purchase_unit').val();
        let quantity = $('#productQuantity').val();
        let purchase_price = $('#purchase_price').val();
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
                    let cartAmount = (purchase_price * quantity) - discount;
                    let sumAmount = parseFloat(cartAmount) + parseFloat(totalAmount);
                    let setAmount = $('#netTotalAmount').val(sumAmount);

                    $('#product_add').append(
                        `<tr class="each_row" data-id="${id}">
                            <td class="text-center">` + id + `</td>
                            <td>` + product_name + `<input type="hidden" name="table_product_id[]" value=` +
                        product_id + ` ></td>
                            <td class="text-center">` + purchase_unit + `</td>
                            <td>
                                <input type="text" class="form-control table_quantity py-0 my-0 text-end" name="table_product_quantity[]" step="1" min="0"
                                oninput="this.value = this.value.replace(/\D/g, '');" 
                                value=` + quantity + `>
                            </td>
                            <td class="text-end">` + purchase_price +
                        `<input type="hidden" name="table_product_price[]" value=` +
                        purchase_price + ` ></td>
                            <td class="text-end">` + discount +
                        `<input type="hidden" name="table_product_discount[]" value=` +
                        discount + ` ></td>
                            <td id="cart${id}" class="cart_amount text-end">` + cartAmount + `</td>
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

    $('#cart_product').on('keyup', 'input[name="table_product_quantity[]"]', function(e) {
        var quantity = $(this).val();

        let purchase_price = parseFloat($(this).closest('.each_row').find('input[name="table_product_price[]"]')
            .val()) || 0;
        let discount = parseFloat($(this).closest('.each_row').find('input[name="table_product_discount[]"]')
            .val()) || 0;

        let update_amount = (quantity * purchase_price) - discount;

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

    $('#changeSupplier').on('change', function() {
        var acName = $(this).val();
        if (acName) {
            var url = '{{ route('supplierDetails', ['ac_name' => ':ac_name']) }}'.replace(':ac_name', acName);
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
