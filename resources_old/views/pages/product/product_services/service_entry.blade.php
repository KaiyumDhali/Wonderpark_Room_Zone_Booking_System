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

        <!-- Product add to card Content Row -->
        <form class="px-0 mx-0" id="serviceForm" method="POST" action="{{ route('product_services_entry_store', $getProductServicesEntry?->id) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        {{-- select product --}}
                        <div class="col-md-12 card shadow">
                            <div class="card-header px-2 mx-0">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('Product Services') }} -
                                        {{ $getProductServicesEntry?->product_name }}</h1>
                                </div>
                            </div>
                            <div class="card-body py-3 px-2">
                                <div class="col-md-12 pb-3">
                                    <div class="row">

                                        <div class="form-group col-md-2 pt-0 pb-2">
                                            <label for="order">{{ __('Purchase Invoice') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid" id="invoice_no"
                                                name="invoice_no" value="{{ $getProductServicesEntry?->invoice_no }}"
                                                readonly>
                                        </div>
                                        <div class="form-group col-md-2 pt-0 pb-2">
                                            <label for="order">{{ __('Service Number') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid"
                                                id="service_number" name="service_number"
                                                value="{{ $getProductServicesEntry?->service_number }}" readonly>
                                        </div>


                                        <div class="form-group col-md-2 pt-0 pb-2">
                                            <label for="order">{{ __('Customer Name') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid" id=""
                                                name="" value="{{ $getProductServicesEntry?->customer_name }}"
                                                readonly>
                                            <input type="hidden" class="form-control form-control-sm" id="customer_id"
                                                name="customer_id"
                                                value="{{ $getProductServicesEntry?->customer_id }}">
                                        </div>
                                        <div class="form-group col-md-2 pt-0 pb-2">
                                            <label for="order">{{ __('Customer Mobile') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid"
                                                id="customer_mobile" name="customer_mobile"
                                                value="{{ $getProductServicesEntry?->customer_mobile }}" readonly>
                                        </div>
                                        <div class="form-group col-md-4 pt-0 pb-2">
                                            <label for="order">{{ __('Customer Address') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid"
                                                id="customer_address" name="customer_address"
                                                value="{{ $getProductServicesEntry?->customer_address }}" readonly>
                                        </div>



                                        <div class="form-group col-md-3 d-none customerInfo">
                                            <div class="card mt-5 p-2">
                                                <label for="" id="customerAdd4"></label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3 d-none customerInfo">
                                            <div class="card mt-5 p-2">
                                                <label for="" id="customerAdd3"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-2 pt-0 pb-2">
                                            <label class="required">{{ __('Service Date') }}</label>
                                            <input type="date"
                                                class="form-control form-control-sm form-control-solid"
                                                id="voucher_date" name="voucher_date" value="" required />
                                        </div>
                                        {{-- <div class="form-group col-md-4 pt-0 pb-2">
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
                                        </div> --}}
                                        <div class="form-group col-md-4 pt-0 pb-2">
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

                                        <div class="form-group col-md-4 pt-0 pb-2">
                                            <label for="order">{{ __('Product Name') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid" readonly
                                                id="product_name" name="product_name " />
                                        </div>
                                        <div class="form-group col-md-2 pt-0 pb-2">
                                            <label for="order">{{ __('Code') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid" readonly
                                                id="product_code" name="product_code " />
                                        </div>
                                    </div>

                                    <div class="row">

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
                                                id="present_stock" name="present_stock" step="1"
                                                min="0" oninput="this.value = this.value.replace(/\D/g, '');"
                                                readonly>
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Quantity') }}</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="productQuantity" name="quantity"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Price (Per Unit)') }}</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="sales_price" name="sales_price"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                            <input type="hidden" class="form-control form-control-sm"
                                                id="purchase_price" name="purchase_price"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>
                                        <div class="form-group col-md-2 my-3">
                                            <label for="order">{{ __('Amount') }}</label>
                                            <input type="text"
                                                class="form-control form-control-sm form-control-solid"
                                                id="quantityAmount" name="amount" value=0 readonly />
                                        </div>

                                        <div class="form-group col-md-2 my-3">
                                            <label class="">Discount Type</label>
                                            <select class="form-select form-select-sm" data-control="select2"
                                                name="type" id="percentageType">
                                                <option value="">--Select Type--</option>
                                                <option value="1" selected>Fixed</option>
                                                <option value="2">Percentage</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2 d-none my-3" id="percentageInputContainer">
                                            <label class=""><span class="">Percentage </span></label>
                                            <input type="text" class="form-control form-control-sm"
                                                name="percentage" id="percentageInput"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>

                                        <div class="form-group col-md-2 my-3" id="amountInputContainer">
                                            <label class=""><span>Discount </span> </label>
                                            <input type="text" class="form-control form-control-sm" id="discount"
                                                name="discount"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </div>
                                        <div class="form-group col-md-6 my-3">
                                            <label for="order">{{ __('Remark') }}</label>
                                            <input type="text" class="form-control form-control-sm" id="remarks"
                                                name="remarks" />
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
                        <div class="card-header my-3 mx-0 px-2">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h1 class="h3 mb-0 text-gray-800">{{ __('Service Summary') }}</h1>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="order">{{ __('Net Total Amount') }}</label>
                            <input type="number" class="form-control form-control-sm form-control-solid"
                                id="netTotalAmount" name="netTotalAmount" value=0 readonly />
                        </div>

                        <div class="card card-flush h-xl-100 my-3">
                            <!--begin::Header-->
                            <div class="card-header py-0">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Payment Method</span>
                                </h3>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-0">
                                <!--begin::Nav-->
                                <ul class="nav nav-pills nav-pills-custom my-0">
                                    <!--begin::Item-->
                                    <li class="nav-item my-3 me-3 me-lg-6">
                                        <!--begin::Link-->
                                        <a class="paymentType nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-60px h-65px pt-3 pb-2 active"
                                            id="kt_stats_widget_16_tab_link_1" data-bs-toggle="pill"
                                            href="#kt_stats_widget_16_tab_1" data-value="Cash">
                                            <!--begin::Icon-->
                                            <div class="nav-icon">
                                                <i class="fa-solid fa-bangladeshi-taka-sign pb-2"
                                                    style="font-size: 20px;"></i>
                                            </div>
                                            <span class="nav-text text-gray-800 fw-bold fs-7 lh-1">Cash</span>
                                            <span
                                                class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                                            <!--end::Bullet-->
                                        </a>
                                        <!--end::Link-->
                                    </li>

                                    <li class="nav-item my-3 me-3 me-lg-6">
                                        <!--begin::Link-->
                                        <a class="paymentType nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-60px h-65px pt-3 pb-2"
                                            id="kt_stats_widget_16_tab_link_2" data-bs-toggle="pill"
                                            href="#kt_stats_widget_16_tab_2" data-value="Bank">
                                            <!--begin::Icon-->
                                            <div class="nav-icon">
                                                <i class="fa-solid fa-building-columns pb-2"
                                                    style="font-size: 20px;"></i>
                                            </div>
                                            <span class="nav-text text-gray-800 fw-bold fs-7 lh-1">Bank</span>
                                            <span
                                                class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                                            <!--end::Bullet-->
                                        </a>
                                        <!--end::Link-->
                                    </li>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <li class="nav-item my-3 me-3 me-lg-6">
                                        <!--begin::Link-->
                                        <a class="paymentType nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-60px h-65px pt-3 pb-2"
                                            id="kt_stats_widget_16_tab_link_3" data-bs-toggle="pill"
                                            href="#kt_stats_widget_16_tab_3" data-value="mBank">
                                            <!--begin::Icon-->
                                            <i class="fa-solid fa-mobile pb-2" style="font-size: 20px;"></i>
                                            <span class="nav-text text-gray-800 fw-bold fs-7 lh-1">mBank</span>
                                            <span
                                                class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                                            <!--end::Bullet-->
                                        </a>
                                        <!--end::Link-->
                                    </li>
                                    <!--end::Item-->
                                </ul>
                                <!--end::Nav-->
                                <!--begin::Tab Content-->
                                <div class="tab-content">
                                    <div class="form-group">
                                        <label>{{ __('Receive Account') }}</label>
                                        <select id="changeAccountsName" class="form-control form-control-sm"
                                            name="receive_account" data-control="select2">
                                            <option value="">Select Payment Account</option>
                                            @foreach ($toAccounts as $toAccount)
                                                <option value="{{ $toAccount->id }}">
                                                    {{ $toAccount->account_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--begin::Tap cash pane-->

                                    <!--begin::Tap bank pane-->
                                    <div class="tab-pane fade" id="kt_stats_widget_16_tab_2">

                                        <div class="form-group">
                                            <label>{{ __('Select Type') }}</label>
                                            <select id="chequeType" class="form-control form-control-sm"
                                                name="cheque_type" data-control="select2"
                                                data-placeholder="Select Cheque Type">
                                                <option value="">Select Type</option>
                                                <option value="Cheque">Cheque </option>
                                                <option value="Cash Deposit">Cash Deposit </option>
                                                <option value="Online">Online</option>
                                                <option value="CHT">CHT</option>
                                                <option value="RTGS">RTGS</option>
                                            </select>
                                        </div>
                                        <div id="bankInfo" class="d-none">
                                            <div class="form-group">
                                                <label for="returnAmount">Cheque No</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="cheque_no" value="0" />
                                            </div>
                                            <div class="form-group">
                                                <label for="">Cheque Date</label>
                                                <input type="date" class="form-control form-control-sm"
                                                    name="cheque_date" />
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Tap pane-->
                                    <!--begin::Tap mobile bank pane-->
                                    <div class="tab-pane fade" id="kt_stats_widget_16_tab_3">
                                        <div class="form-group">
                                            <label>{{ __('Select Mobile Bank') }}</label>
                                            <select id="" class="form-control form-control-sm"
                                                name="mobile_bank_name" data-control="select2"
                                                data-placeholder="Select Mobile Bank">
                                                <option value="">Select Mobile Bank</option>
                                                <option value="bKash">bKash</option>
                                                <option value="Nagad">Nagad</option>
                                                <option value="Rocket">Rocket</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Mobile Number</label>
                                            <input type="text" class="form-control form-control-sm"
                                                name="mobile_number" value="0" />
                                        </div>
                                        <div class="form-group">
                                            <label for="">Transaction ID</label>
                                            <input type="text" class="form-control form-control-sm"
                                                name="transaction_id" value="0" />
                                        </div>
                                    </div>
                                    <!--end::Tap pane-->

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
                                </div>
                                <!--end::Tab Content-->
                            </div>
                            <!--end: Card Body-->
                        </div>
                        <div class="form-group">
                            <label for="order">{{ __('Service Man Name') }}</label>
                            <input type="text" class="form-control form-control-sm" id="service_man_name"
                                name="service_man_name">
                        </div>
                        <div class="form-group">
                            <label for="order">{{ __('Service Man Mobile') }}</label>
                            <input type="text" class="form-control form-control-sm" id="service_man_mobile"
                                name="service_man_mobile">
                        </div>
                        <div class="form-group">
                            <label for="remarks">{{ __('Remarks') }}</label>
                            <input type="text" class="form-control form-control-sm" id="remarks"
                                name="remarks" />
                        </div>
                        <button type="submit" id="submitBtn"
                            class="btn btn-sm btn-success px-5 mt-5">{{ __('Sales') }}</button>

                    </div>
                </div>
            </div>
        </form>
    </div>



</x-default-layout>

<script type="text/javascript">
    document.getElementById("serviceForm").addEventListener("keydown", function(event) {
        // Prevent form submission on Enter key press
        if (event.key === "Enter") {
            event.preventDefault();
        }
    });

    // Function to fetch product details based on selected product ID
    function fetchProductDetails(productID) {
        let url;
        url = "{{ route('productDetails', ['id' => ':id']) }}";
        url = url.replace(':id', productID);
        $.ajax({
            // url: 'productDetails/' + productID,
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
                    $('#purchase_price').val(product.purchase_price);
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
        var voucherDate = $('#voucher_date').val();
        var customer = $('#customer_id').val();
        // var warehouseId = $('#warehouseId').val();
        var product_id = $('#changeProduct').val();
        var product_name = $('#product_name').val();
        var sales_unit = $('#sales_unit').val();
        var quantity = parseFloat($('#productQuantity').val());
        var sales_price = parseFloat($('#sales_price').val()) || 0;
        var purchase_price = parseFloat($('#purchase_price').val()) || 0;
        var discount = parseFloat($('#discount').val()) || 0;
        var quantityAmount = parseFloat($('#quantityAmount').val()) || 0;
        var totalAmount = parseFloat($('#netTotalAmount').val()) || 0;
        var remarks = $('#remarks').val() || 'remarks..';
        
        // Validation checks using SweetAlert
        if (!voucherDate) {
            Swal.fire({
                icon: 'error',
                title: 'No Date Selected',
                text: 'Please select a date before adding a product.'
            });
        } else if (!customer) {
            Swal.fire({
                icon: 'error',
                title: 'No Customer Selected',
                text: 'Please select a customer before adding a product.'
            });
        }
        // else if (!warehouseId) {
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'No Warehouse Selected',
        //         text: 'Please select a warehouse before adding a product.'
        //     });
        // } 
        else if (!product_id) {
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
                    <td class="text-end"><span class="discount_text">${discount}</span><input type="hidden" class="table_discount" name="table_product_discount[]" value="${discount}"></td>
                    <td id="cart${id}" class="text-end"><span class="cart_amount">${cartAmount}</span>
                        <input type="hidden" class="table_cart_amount" name="table_product_cart_amount[]" value="${cartAmount}">
                        <input type="hidden" class="table_cart_amount" name="table_remarks[]" value="${remarks}">
                    </td>
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
        $('#remarks').val('');
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
    // $('#cart_product').on('keyup', 'input[name="table_product_quantity[]"]', function(e) {
    //     let quantity = $(this).val();
    //     let sales_price = parseFloat($(this).closest('.each_row').find('input[name="table_product_price[]"]')
    //         .val()) || 0;
    //     let discount = parseFloat($(this).closest('.each_row').find('input[name="table_product_discount[]"]')
    //         .val()) || 0;

    //     let newAmount = ((quantity * sales_price) - discount).toFixed(2);
    //     let currentTotal = parseFloat($(this).closest('.each_row').find('.cart_amount').text()) || 0;

    //     // Subtract the previous total amount of this row
    //     let netTotal = parseFloat($('#netTotalAmount').val()) || 0;
    //     netTotal += parseFloat(newAmount) - currentTotal;

    //     // Update the total amount
    //     $('#netTotalAmount').val(netTotal.toFixed(2));

    //     // Update the cart amount for this row
    //     $(this).closest('.each_row').find('.cart_amount').text(newAmount);
    // });

    // -------------------------------- add to card end -----------------------------------------------------------

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

    // Event handler for updating the return amount based on the given amount
    $('#givenAmount').on('keyup', function() {
        let totalAmount = parseFloat($('#netTotalAmount').val()) || 0;
        let givenAmount = parseFloat($(this).val()) || 0;
        let returnAmount = givenAmount - totalAmount;
        $('#returnAmount').val(returnAmount.toFixed(2));
    });

    //submitBtn submit button check all required manage
    document.getElementById("submitBtn").addEventListener("click", function(event) {
        event.preventDefault(); // Stop the form from submitting immediately

        // Get the elements for validation
        var voucherDate = document.getElementById("voucher_date");
        var customerSelect = document.getElementById("customer_id");
        var productInputs = document.querySelectorAll('input[name="table_product_id[]"]');
        var givenAmount = parseFloat(document.getElementById("givenAmount").value) || 0;
        var accountsName = document.getElementById("changeAccountsName");

        // 1st check: Voucher Date
        if (voucherDate.value === "") {
            Swal.fire({
                icon: 'error',
                title: 'No Voucher Date Selected',
                text: 'Please select a voucher date before submitting the form.'
            });
        }
        // 2nd check: Customer
        else if (customerSelect.value === "") {
            Swal.fire({
                icon: 'error',
                title: 'No Customer Selected',
                text: 'Please select a customer before submitting the form.'
            });
        }
        // 3rd check: Product selection (ensure at least one product is selected)
        else if (productInputs.length === 0 || Array.from(productInputs).every(input => input.value === "")) {
            Swal.fire({
                icon: 'error',
                title: 'No Products Selected',
                text: 'Please select at least one product before submitting the form.'
            });
        }
        // 4th check: Given Amount > 0
        else if (givenAmount > 0 && accountsName.value === "") {
            Swal.fire({
                icon: 'error',
                title: 'No Account Selected',
                text: 'Please select an account name before submitting the form.'
            });
        }
        // 5th check: Account Name must be selected
        // else if (accountsName.value === "") {
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'No Account Selected',
        //         text: 'Please select an account name before submitting the form.'
        //     });
        // } 
        else {
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
                    document.getElementById("serviceForm").submit();
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
