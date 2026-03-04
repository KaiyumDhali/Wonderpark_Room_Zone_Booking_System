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
        <div class="row pt-10">
            <!-- Content Row -->
            <div class="col-md-8">
                <div class="row">
                    {{-- select product --}}
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
                    <form class="px-0" id="orderform" method="POST" action="{{ route('order.store') }}"
                        enctype="multipart/form-data">
                        @csrf
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
                            id="netTotalAmount" name="netTotalAmount" value=0 readonly />
                    </div>
                    <div class="card-body px-0 pb-2 d-flex align-items-center">
                        <div class="flex-grow-1 pt-2">
                            <label>{{ __('Select Customer') }}</label>
                            <select id="changeCustomer" class="form-control form-control-sm" name="customer_id"
                                data-control="select2" data-placeholder="Select Customer" required>
                                <option></option>
                                @foreach ($customerAccounts as $customerAccount)
                                    <option value="{{ $customerAccount->id }}">
                                        {{ $customerAccount->account_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <a href="#" class="btn btn-sm btn-flex btn-light-primary ms-2 mt-8"
                            data-bs-toggle="modal" data-bs-target="#add_customer_modal">
                            Add
                        </a>
                    </div>
                    <div class="card p-5 my-3 d-none" id="customerInfo">
                        <label for="" id="customerAdd4"></label>
                        <label for="" id="customerAdd5"></label>
                        <label for="" id="customerAdd"></label>
                        <label for="" id="customerAdd2"></label>
                        <label for="" id="customerAdd3"></label>
                    </div>
                    {{-- <div class="form-group">
                        <label for="givenAmount">{{ __('Given Amount') }}</label>
                        <input type="text" class="form-control form-control-sm" id="givenAmount"
                            name="givenAmount" value=0 oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"/>
                    </div> --}}
                    {{-- <div class="form-group">
                        <label for="returnAmount">{{ __('Return Amount') }}</label>
                        <input type="number" class="form-control form-control-sm form-control-solid"
                            id="returnAmount" name="returnAmount" value="0" readonly />
                    </div> --}}
                    <div class="form-group">
                        <label for="remarks">{{ __('Order Date') }}</label>
                        <input type="date" class="form-control form-control-sm" id="remarks"
                            name="order_date" required/>
                    </div>
                    <div class="form-group">
                        <label for="remarks">{{ __('Delivery Date') }}</label>
                        <input type="date" class="form-control form-control-sm" id="remarks"
                            name="delivery_date" required/>
                    </div>
                    <div class="form-group">
                        <label for="remarks">{{ __('Remarks') }}</label>
                        <input type="text" class="form-control form-control-sm" id="remarks" name="remarks" />
                    </div>

                    {{-- <div class="form-group">
                        <label for="remarks">{{ __('Attach File') }}</label>
                        <input type="file" class="form-control form-control-sm" id="file" name="file" />
                    </div> --}}

                    <button type="submit" id=""
                        class="btn btn-sm btn-success px-5 mt-5">{{ __('Order') }}</button>
                </div>
            </div>
            </form>
        </div>
        {{-- add new customer modal --}}
        <div class="modal fade" id="add_customer_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Add Customer</h2>
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <i style="font-size: 20px" class="fas fa-times"></i>
                        </div>
                    </div>
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                        <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                            action="{{ route('customers.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class="required form-label">Customer Type</label>
                                            <select class="form-select form-select-sm" data-control="select2" name="customer_type" required>
                                                <option value="">Select Customer Type</option>
                                                @foreach ($customerTypes as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class="required form-label">Customer Code</label>
                                            <input type="text" name="customer_code"
                                                class="form-control form-control-sm mb-2" placeholder="Customer Code"
                                                value="" required>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class="required form-label">Name</label>
                                            <input type="text" name="customer_name"
                                                class="form-control form-control-sm mb-2" placeholder="Customer Name"
                                                value="" required>
                                            <input type="text" class="form-control form-control-sm" name="sumite_type"
                                                value="1" hidden>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class="form-label">Gender</label>
                                            <select class="form-select form-select-sm" data-control="select2" name="customer_gender">
                                                <option value="" selected>--Select Gender--</option>
                                                <option value="1">Male</option>
                                                <option value="0">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class="required form-label">Cell Number</label>
                                            <input type="text" name="customer_mobile"
                                                class="form-control form-control-sm mb-2" placeholder="Customer Mobile"
                                                value="" required>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label"> E-mail </label>
                                            <input type="text" name="customer_email"
                                                class="form-control form-control-sm mb-2" placeholder="Customer Email"
                                                value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">DOB</label>
                                            <input type="date" name="customer_DOB"
                                                class="form-control form-control-sm mb-2" placeholder="Customer DOB"
                                                value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">NID Number</label>
                                            <input type="text" name="nid_number"
                                                class="form-control form-control-sm mb-2" placeholder="NID Number"
                                                value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Vat Reg No</label>
                                            <input type="text" name="vat_reg_no"
                                                class="form-control form-control-sm mb-2" placeholder="Vat Reg No"
                                                value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label"> e-TIN No </label>
                                            <input type="text" name="tin_no"
                                                class="form-control form-control-sm mb-2" placeholder="Tin No"
                                                value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Trade License</label>
                                            <input type="text" name="trade_license"
                                                class="form-control form-control-sm mb-2" placeholder="Trade License"
                                                value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Commission Rate</label>
                                            <input type="text" name="discount_rate"
                                                class="form-control form-control-sm mb-2" placeholder="Discount Rate"
                                                value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Customer Address</label>
                                            <input type="text" name="customer_address"
                                                class="form-control form-control-sm mb-2" placeholder="Customer Address"
                                                value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Shipping Address</label>
                                            <input type="text" name="shipping_address"
                                                class="form-control form-control-sm mb-2" placeholder="Shipping Address"
                                                value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Previous Due</label>
                                            <input type="text" name="is_previous_due"
                                                class="form-control form-control-sm mb-2" placeholder="Previous Due"
                                                value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Status</label>
                                            <select class="form-select form-select-sm" data-control="select2" name="status">
                                                <option value="">--Select Status--</option>
                                                <option value="1" selected>Active</option>
                                                <option value="0">Disable</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="text-center pt-5">
                                <button class="btn btn-sm btn-success me-5" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary">
                                    <span class="indicator-label">Submit</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Modal body-->
                </div>
                <!--end::Modal content-->
            </div>
        </div>
        <!--end::Unit Modal - Create App-->

    </div>
</x-default-layout>

<script type="text/javascript">
    document.getElementById("orderform").addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
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

    $('#givenAmount').on('keyup', function() {
        var quantity = $('#givenAmount').val();
        var netTotalAmount = $('#netTotalAmount').val();
        var givenAmount = $('#givenAmount').val();
        let returnAmount = netTotalAmount - givenAmount;
        $('#returnAmount').val(returnAmount);
    });

    $('#changeCustomer').on('change', function() {
        var customerID = $(this).val();
        let url = '{{ route('productCustomerDetails', ['id' => ':customerID']) }}';
        url = url.replace(':customerID', customerID);
        if (customerID) {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    if (data) {
                        $('#customerInfo').removeClass('d-none');
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
</script>
