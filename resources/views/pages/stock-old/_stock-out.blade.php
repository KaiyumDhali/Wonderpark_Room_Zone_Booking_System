<x-default-layout>
    <style>
        .card .card-header {
            min-height: 40px;
        }
        .table > :not(caption) > * > * {
            padding: 0.3rem !important;
        }
    </style>
    <div class="container-fluid">
        <!-- Page Heading -->
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form id="" method="POST" action="{{ route('sales.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row pt-10">
                <!-- Content Row -->

                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12 card shadow">
                            <div class="card-header">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('Sales') }}</h1>
                                </div>
                                <label class="badge text-warning" id="jsdataerror"></label>
                            </div>
                            <div class="card-body py-3">
                                <div class="col-md-12 pb-3">
                                    
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <label>{{ __(' Product') }}</label>
                                                <select id="changeProduct" class="form-control form-control-sm" name="product_id">
                                                    <option>Select Product</option>
                                                    @foreach($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->product_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="order">{{ __('Unit Name') }}</label>
                                                <input type="text" class="form-control form-control-sm form-control-solid" readonly id="sales_unit"
                                                    name="sales_unit " />
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="order">{{ __('Price (Per Unit)') }}</label>
                                                <input type="number" class="form-control form-control-sm form-control-solid" id="sales_price"
                                                    name="sales_price" />
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="order">{{ __('Present Stock') }}</label>
                                                <input type="number" class="form-control form-control-sm form-control-solid" id="present_stock"
                                                    name="present_stock" />
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="order">{{ __('Quantity') }}</label>
                                                <input type="number" class="form-control form-control-sm" id="quantity"
                                                    name="quantity" step="1" pattern="^[-\d]\d*$">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="order">{{ __('Amount') }}</label>
                                                <input type="number" class="form-control form-control-sm" id="Amount" name="Amount"
                                                    value=0 />
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="discount">{{ __('Discount') }}</label>
                                                <input type="number" class="form-control form-control-sm" id="discount"
                                                    name="discount" />
                                            </div>
                                            <div class="form-group col-md-3">
                                                <button type="button" id="addProduct" class="btn btn-sm btn-primary px-5 mt-6">{{ __('Add to Cart') }}</button>
                                            </div>
                                        </div>
                                        
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 card shadow my-5 px-5">
                            <table id="product_add" class="table table-bordered mt-4">
                                <tr>
                                    <th></th>
                                    <th>Product Name</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                    <th>Sale Price</th>
                                    <th>Discount</th>
                                    <th>Amount</th>
                                </tr>
                            </table>
                            <table id="sumAmount" class="table table-bordered">
        
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Content Row -->
                <div class="col-md-4">
                    <div class="card shadow px-10 pb-8 ms-0 ms-md-5">
                        <div class="card-header mb-4">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h1 class="h3 mb-0 text-gray-800">{{ __('Sales Summary') }}</h1>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="order">{{ __('Net Total Amount') }}</label>
                            <input type="number" class="form-control form-control-sm" id="netTotalAmount" name="netTotalAmount"
                                value=0 readonly/>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Select Customer') }}</label>
                            <select id="changeCustomer" class="form-control form-control-sm" name="customer_id" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->customer_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <table id="customerAdd" class="table table-success table-striped">
        
                        </table>
                        <!-- <div class="form-group">
                            <label for="order">{{ __('Invoice No.') }}</label>
                            <input type="text" class="form-control form-control-sm" id="" name="customer_invoice_no" value="" />
                        </div> -->
                        <div class="form-group">
                            <label for="givenAmount">{{ __('Given Amount') }}</label>
                            <input type="number" class="form-control form-control-sm" id="givenAmount" name="givenAmount" value=0 />
                        </div>
                        <div class="form-group">
                            <label for="returnAmount">{{ __('Return Amount') }}</label>
                            <input type="number" class="form-control form-control-sm" id="returnAmount" name="returnAmount"
                                value="0" readonly/>
                        </div>
                        <div class="form-group">
                            <label for="remarks">{{ __('Remarks') }}</label>
                            <input type="text" class="form-control form-control-sm" id="remarks" name="remarks" />
                        </div>
                        <button type="submit" id="" class="btn btn-sm btn-success px-5 mt-5">{{ __('Sales') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    </x-default-layout>
    
    <script type="text/javascript">
        {
        const intRx = /\d/,
        integerChange = (event) => {
            console.log(event.key, )
            if (
            (event.key.length > 1) ||
            event.ctrlKey ||
            ( (event.key === "-") && (!event.currentTarget.value.length) ) ||
            intRx.test(event.key)
            ) return;
            event.preventDefault();
        };

        for (let input of document.querySelectorAll(
        'input[type="number"][step="1"]'
        )) input.addEventListener("keydown", integerChange);
        }

        $('#givenAmount').on('keyup', function() {
            var quantity = $('#givenAmount').val();
            var netTotalAmount = $('#netTotalAmount').val();
            var givenAmount = $('#givenAmount').val();
            let returnAmount = netTotalAmount - givenAmount;
            $('#returnAmount').val(returnAmount);
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
                            $('#jsdataerror').text('');
                            $.each(data, function(key, value) {
                                var customer_name = value.customer_name;
                                var customer_mobile = value.customer_mobile;
                                var debit = value.customer_ledger_sum_debit;
                                var credit = value.customer_ledger_sum_credit;
                                var due = debit-credit;
        
                                $("#customerAdd").empty();
                                $('#customerAdd').append(
        
                                    `<tr>
                                        <th style="width:40%">Customer Name:</th>
                                        <td>` + customer_name + `</td>
                                    </tr>
                                    <tr>
                                        <th>Mobile Number:</th>
                                        <td>` + customer_mobile + `</td>
                                    </tr>
                                    <tr>
                                        <th>Due Amount:</th>
                                        <td>` + due + `<input type="hidden" name="table_customer_due" value=`+ due +` ></td>
                                    </tr>`
        
                                );
                            });
                        } else {
                            $('#jsdataerror').text('Not Found');
                        }
                    }
                });
            } else {
                $('#jsdataerror').text('Not Found');
            }
        });
        
        $('#product_add').on('click', function(e) {
        
            let totalAmount = $('#netTotalAmount').val();
            var cartAmount = 0;
            if (e.target.matches('.delete')) {
                const { id } = e.target.dataset;
                const selector = `tr[data-id="${id}"]`;
                cartAmount = $('#cart' + id).text();
                
                if (!cartAmount.trim()) {
                    cartAmount = 0;
                }
        
                // var $cartAmount  = $('#cart1').innerHTML;
                console.log(cartAmount);
                $(selector).remove();
            }
            let sumAmount = (parseFloat(totalAmount)) - (parseFloat(cartAmount));
            let setAmount = $('#netTotalAmount').val(sumAmount);
        
        });

        $('#table_product_quantity').on('change', function() {
            
            var table_product_quantity= $('#table_product_quantity').val()
            var sales_price = $('#sales_price').val();
            var discount = $('#discount').val();
            let update_quantity= (sales_price * table_product_quantity) - discount;
            $('#cart_update_price').val(update_quantity);

        });

        let id = 0;
        $('#addProduct').on('click', function() {
            // $( "#myselect option:selected" ).text();
            let product_name = $('#changeProduct option:selected').text();
            let product_id = $('#changeProduct option:selected').val();
            let sales_unit = $('#sales_unit').val();
            let quantity = $('#quantity').val();
            let sales_price = $('#sales_price').val();
            let discount = $('#discount').val();
            let totalAmount = $('#netTotalAmount').val();

            if(product_id > 0){
                if(quantity > 0){
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
                        `<tr data-id="${id}">
                            <td class="text-center my-0"> <button data-id="${id}" class="delete btn btn-sm btn-danger py-0" >Delete</button></td>
                            <td>` + product_name + `<input type="hidden" name="table_product_id[]" value=`+ product_id +` ></td>
                            <td>` + sales_unit + `</td>
                            <td><input type="number" style="width:80px" id="table_product_quantity" name="table_product_quantity[]" value=`+ quantity +` step="1" pattern="^[-\d]\d*$" ></td>
                            <td>` + sales_price + `<input type="hidden" name="table_product_price[]" value=`+ sales_price +` ></td>
                            <td>` + discount + `<input type="hidden" name="table_product_discount[]" value=`+ discount +` ></td>
                            <td id="cart${id}" >` + cartAmount + `</td>
                        </tr>`
                        );
                        ++id;
                        // $('#changeProduct').val('');
                        $('#quantity').val('');
                    }
                }else{
                    alert("Please Input Quantity Number");
                }
            }else{
                alert("Please Select Product");
            }

            // $('#product_add').append(
        
            //     `<tr data-id="${id}">
            //         <td class="text-center my-0"> <button data-id="${id}" class="delete btn btn-sm btn-danger py-0" >Delete</button></td>
            //         <td>` + product_name + `<input type="hidden" name="table_product_id[]" value=`+ product_id +` ></td>
            //         <td>` + sales_unit + `</td>
            //         <td>` + quantity + `<input type="hidden" name="table_product_quantity[]" value=`+ quantity +` ></td>
            //         <td>` + sales_price + `<input type="hidden" name="table_product_price[]" value=`+ sales_price +` ></td>
            //         <td>` + discount + `<input type="hidden" name="table_product_discount[]" value=`+ discount +` ></td>
            //         <td id="cart${id}" >` + cartAmount + `</td>
            //     </tr>`
        
            // );
            // ++id;
        });
        
        $('#quantity').on('keyup', function() {
            var quantity = $('#quantity').val();
            var purchasePrice = $('#sales_price').val();
        
            let totalAmount = quantity * purchasePrice;
        
            $('#Amount').val(totalAmount);
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
                            $('#Amount').val(0);
                            $('#quantity').val(0);
                            $('#discount').val(0);
                            $.each(data, function(key, value) {
                                var unit = value.unit.unit_name;
                                var price = value.sales_price;
                                var sum_stock_in = value.product_stock_sum_stock_in_quantity;
                                var sum_stock_out = value.product_stock_sum_stock_out_quantity;
                                var present_stock = sum_stock_in-sum_stock_out;

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
        </script>