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
                                <h1 class="h3 mb-0 text-gray-800">{{ __('Purchase Update') }}</h1>
                            </div>
                        </div>
                        <div class="card-body py-3 px-2 mx-0">
                            <div class="col-md-12 pb-3">
                                <div class="row">
                                    <div class="form-group col-md-6 my-3">
                                        <label>{{ __('Select Warehouse') }}</label>
                                        <select id="warehouseId" class="form-select form-select-sm" name="warehouse_id"
                                            data-control="select2" data-hide-search="false">
                                            <option value="" disabled selected>Select Warehouse</option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}"
                                                    @if ($stocks && isset($stocks[0]->warehouse_id) && $warehouse->id == $stocks[0]->warehouse_id) selected @endif>
                                                    {{ $warehouse->name }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="form-group col-md-6 my-3">
                                        <label>{{ __('Select Product') }}</label>
                                        <select id="changeProduct" class="form-select form-select-sm" name="product_id"
                                            data-control="select2" data-hide-search="false">
                                            <option value="" selected disabled>Select Product</option>
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
                                            readonly id="purchase_unit" name="purchase_unit " />
                                    </div>
                                    <div class="form-group col-md-2 my-3">
                                        <label for="order">{{ __('Price (Per Unit)') }}</label>
                                        <input type="text" class="form-control form-control-sm" id="purchase_price"
                                            name="purchase_price"
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
                                        <select class="form-select form-select-sm" data-control="select2" name="type" id="percentageType">
                                            <option value="">Select Type</option>
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
                    <form class="px-0" id="purchaseform" method="POST"
                        action="{{ route('purchase_invoice_update', $invoiceNo) }}" enctype="multipart/form-data">
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
                                        <th>Purchase Price</th>
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
            <!--  Purchase Summary Content Row -->
            <div class="col-md-4">
                <div class="card shadow px-10 pb-8 ms-0 ms-md-5">
                    <div class="card-header my-3 mx-0 px-2">
                        <div class="d-sm-flex align-items-center justify-content-between">
                            <h1 class="h3 mb-0 text-gray-800">{{ __('Purchase Summary') }}</h1>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="order">{{ __('Net Total Amount') }}</label>
                        <input type="number" class="form-control form-control-sm form-control-solid"
                            id="netTotalAmount" name="netTotalAmount" value=0 readonly />
                        <input type="hidden" class="form-control form-control-sm form-control-solid" id="voucher_no"
                            name="voucher_no" value="{{ $supplierFinance->voucher_no }}" />
                    </div>
                    <div class="form-group">
                        <label for="order">{{ __('Purchase Date') }}</label>
                        <input type="date" class="form-control form-control-sm form-control-solid"
                            id="voucher_date" name="voucher_date" value="{{ $supplierFinance->voucher_date }}" />
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 d-flex align-items-center">
                        <div class="flex-grow-1 pt-2">
                            <label>{{ __('Select Supplier') }}</label>
                            <select id="changeSupplier" class="form-control form-control-sm" name="supplier_id"
                                data-control="select2" data-placeholder="Select Supplier" required>
                                <option value=""></option>
                                @foreach ($supplierAccounts as $supplierAccount)
                                    <option value="{{ $supplierAccount->id }}"
                                        @if ($supplierAccount->id == $stocks[0]->supplier_id) selected @endif>
                                        {{ $supplierAccount->account_name }}
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
                        <label for="" id="supplierAdd4"></label>
                        <label for="" id="supplierAdd5"></label>
                        <label for="" id="supplierAdd"></label>
                        <label for="" id="supplierAdd2"></label>
                        <label for="" id="supplierAdd3"></label>
                    </div>

                    {{-- <table id="supplierAdd" class="table table-success table-striped">

                        </table> --}}

                    <div class="form-group">
                        <label for="order">{{ __('Supplier Invoice No') }}</label>
                        <input type="text" class="form-control form-control-sm" id=""
                            name="supplier_invoice_no" value="{{ $stocks[0]->supplier_invoice_no }}" />
                    </div>

                    <div class="form-group">
                        <label>{{ __('From Account') }}</label>
                        <select id="changeAccountsName" class="form-control form-control-sm" name="pay_account"
                            data-control="select2" data-placeholder="Select Payment Account">
                            <option value="" ></option>
                            @foreach ($fromAccounts as $fromAccount)
                                <option value="{{ $fromAccount->id }}"
                                    @if ($supplierPayment && $supplierPayment->to_acc_name && $fromAccount->account_name == $supplierPayment->to_acc_name) selected @endif>
                                    {{ $fromAccount->account_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="givenAmount">{{ __('Paid Amount') }}</label>
                        <input type="text" class="form-control form-control-sm" id="givenAmount"
                            name="givenAmount" value="{{ $supplierPayment->amount ?? 0 }}"
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
                    <button type="submit" id="submitBtn"
                        class="btn btn-sm btn-success px-5 mt-5">{{ __('Purchase Update') }}</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    {{-- add new customer modal --}}
    <div class="modal fade" id="add_supplier_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add Supplier</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i style="font-size: 20px" class="fas fa-times"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                    <form id="kt_ecommerce_add_category_form"
                        class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework"
                        method="POST" action="{{ route('suppliers.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!--begin::Main column-->
                        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                            <!--begin::General options-->
                            <div class="row">
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Start Date</label>
                                            <input type="date" name="start_date"
                                                class="form-control form-control-sm mb-2" placeholder="Start Date"
                                                value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Supplier Code</label>
                                            <input type="text" name="supplier_code"
                                                class="form-control form-control-sm mb-2" placeholder="Supplier Code"
                                                value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class="required form-label">Supplier Name</label>
                                            <input type="text" name="supplier_name"
                                                class="form-control form-control-sm mb-2" placeholder="Supplier Name"
                                                value="" required>
                                            <input type="text" class="form-control form-control-sm"
                                                name="sumite_type" value="1" hidden>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Supplier Proprietor Name</label>
                                            <input type="text" name="supplier_proprietor_name"
                                                class="form-control form-control-sm mb-2"
                                                placeholder="Supplier Proprietor Name" value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class="required form-label">Supplier Mobile</label>
                                            <input type="text" name="supplier_mobile"
                                                class="form-control form-control-sm mb-2"
                                                placeholder="Supplier Mobile" value="" required>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Supplier Email</label>
                                            <input type="text" name="supplier_email"
                                                class="form-control form-control-sm mb-2" placeholder="Supplier Email"
                                                value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Supplier Address</label>
                                            <input type="text" name="supplier_address"
                                                class="form-control form-control-sm mb-2"
                                                placeholder="Supplier Address" value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Representative Name</label>
                                            <input type="text" name="representative_name"
                                                class="form-control form-control-sm mb-2"
                                                placeholder="Representative Name" value="">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-3">
                                        <div class="fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Representative Mobile</label>
                                            <input type="text" name="representative_mobile"
                                                class="form-control form-control-sm mb-2"
                                                placeholder="Representative Mobile" value="">
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
                            <!--end::General options-->
                            <div class="text-center pt-5">
                                <button class="btn btn-sm btn-success me-5" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" id="kt_ecommerce_add_category_submit"
                                    class="btn btn-sm btn-primary ">
                                    <span class="indicator-label">Save</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Button-->
                            </div>
                        </div>
                        <!--end::Main column-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
    </div>
    <!--end::Unit Modal - Create App-->

</x-default-layout>

<script type="text/javascript">
    document.getElementById("purchaseform").addEventListener("keydown", function(event) {
        // Check if the pressed key is Enter
        if (event.key === "Enter") {
            // Prevent default form submission
            event.preventDefault();
        }
    });

    document.getElementById("submitBtn").addEventListener("click", function(event) {
        event.preventDefault(); // Stop the form from submitting immediately

        // Get the elements for validation
        var voucherDate = document.getElementById("voucher_date");
        var supplierSelect = document.getElementById("changeSupplier");
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
        // 2nd check: supplier
        else if (supplierSelect.value === "") {
            Swal.fire({
                icon: 'error',
                title: 'No Supplier Selected',
                text: 'Please select a supplier before submitting the form.'
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
                    document.getElementById("purchaseform").submit();
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
                            var price = value.purchase_price;
                            var sum_stock_in = value.product_stock_sum_stock_in_quantity;
                            var sum_stock_out = value.product_stock_sum_stock_out_quantity;
                            var present_stock = (sum_stock_in - sum_stock_out).toFixed(2);

                            $('#product_name').val(product_name);
                            $('#product_code').val(product_code);
                            $('#purchase_unit').val(unit);
                            $('#purchase_price').val(price);
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

    function onChangeCalculateTotalAmount() {
        let quantity = $('#productQuantity').val();
        let purchase_price = $('#purchase_price').val();

        if (quantity && purchase_price) {
            let totalAmount = quantity * purchase_price;
            totalAmount = (totalAmount).toFixed(3);
            // totalAmount = Math.round(totalAmount); // Round to nearest integer
            $('#quantityAmount').val(totalAmount);
        } else {
            $('#quantityAmount').val(0);
        }
    };
    $('#productQuantity, #purchase_price').on('keyup', onChangeCalculateTotalAmount);

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
        // let cartAmount = (product.purchase_price * product.quantity) - product.discount;
        let cartAmount = product.quantity_amount - product.discount;
        cartAmount = Math.round(cartAmount);
        let sumAmount = parseFloat(cartAmount) + parseFloat($('#netTotalAmount').val());
        $('#netTotalAmount').val(sumAmount);

        var row =
            `<tr class="each_row" data-id="${id}">
                <td class="text-center">` + id + `</td>
                <td>` + product.product_name + `<input type="hidden" name="table_product_id[]" value=` +
            product.product_id + ` ><input type="hidden" name="warehouse_id[]" value=` +
            product.warehouse_id + ` ></td>
                <td class="text-center">` + product.purchase_unit + `</td>
                <td>` + product.quantity + `
                    <input type="hidden" class="form-control table_quantity py-0 my-0 text-end" name="table_product_quantity[]" 
                    step="1" min="0" oninput="this.value = this.value.replace(/\D/g, '');" 
                    value="` + product.quantity + `">
                </td>
                <td class="text-end">` + product.purchase_price +
            `<input type="hidden" name="table_product_price[]" value=` + product.purchase_price + ` ></td>
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

    // $('#addProduct').on('click', function() {
    //     let product_name = $('#changeProduct option:selected').text();
    //     let product_id = $('#changeProduct option:selected').val();
    //     let warehouse_id = $('#warehouseId option:selected').val();
    //     let purchase_unit = $('#purchase_unit').val();
    //     let quantity = $('#productQuantity').val();
    //     let purchase_price = $('#purchase_price').val();
    //     let discount = $('#discount').val();
    //     let quantity_amount = $('#quantityAmount').val() || 0;

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
    //                 // Create product object
    //                 let product = {
    //                     product_name: product_name,
    //                     product_id: product_id,
    //                     warehouse_id: warehouse_id,
    //                     purchase_unit: purchase_unit,
    //                     quantity: quantity,
    //                     purchase_price: purchase_price,
    //                     discount: discount,
    //                     quantity_amount: quantity_amount
    //                 };

    //                 // Add row
    //                 addProductRow(product);
    //             }
    //         } else {
    //             alert("Please Input Quantity Number");
    //         }
    //     } else {
    //         alert("Please Select Product");
    //     }
    // });

    $('#addProduct').on('click', function() {
        let product_name = $('#changeProduct option:selected').text();
        let product_id = $('#changeProduct option:selected').val();
        let warehouse_id = $('#warehouseId option:selected').val();
        let purchase_unit = $('#purchase_unit').val();
        let quantity = $('#productQuantity').val();
        let purchase_price = $('#purchase_price').val();
        let discount = $('#discount').val();
        let quantity_amount = $('#quantityAmount').val() || 0;

        // SweetAlert2 check for warehouse_id
        if (!warehouse_id) {
            Swal.fire({
                icon: 'error',
                title: 'No Warehouse Selected.',
                text: 'Please select a warehouse.',
            });
            return;
        }

        // SweetAlert2 check for product_id
        if (!product_id) {
            Swal.fire({
                icon: 'error',
                title: 'No Product Selected.',
                text: 'Please select a product.',
            });
            return;
        }

        // SweetAlert2 check for purchase_price
        if (!purchase_price || purchase_price <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Sales Price.',
                text: 'Please enter a valid purchase price.',
            });
            return;
        }

        // SweetAlert2 check for quantity
        if (quantity <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Quantity.',
                text: 'Please input a quantity number.',
            });
            return;
        }

        // Check for duplicate consume_product_id
        let duplicate_found = false;
        $('#product_add tr').each(function() {
            if ($(this).find('input[name="table_product_id[]"]').val() === product_id) {
                duplicate_found = true;
                return false; // break out of loop if duplicate found
            }
        });

        if (duplicate_found) {
            Swal.fire({
                icon: 'error',
                title: 'Duplicate Product.',
                text: 'This product has already been added to the list.',
            });
        } else {
            // Create product object
            let product = {
                product_name: product_name,
                product_id: product_id,
                warehouse_id: warehouse_id,
                purchase_unit: purchase_unit,
                quantity: quantity,
                purchase_price: purchase_price,
                discount: discount,
                quantity_amount: quantity_amount
            };

            // Add row
            addProductRow(product);
        }
    });


    // Function to loop through getData array and dynamically add rows
    function loadProductsFromData() {
        getData.forEach(product => {
            const stock_in_total_amount = parseFloat(product.stock_in_total_amount) ||
            0; // Convert to number, default to 0 if NaN
            const discount = parseFloat(product.stock_in_discount) ||
            0; // Convert to number, default to 0 if NaN

            addProductRow({
                product_id: product.product_id || 0, // If product_id is null, use 0
                warehouse_id: product.warehouse_id || 0, // If warehouse_id is null, use 0
                product_name: product.product?.product_name ||
                'Unknown Product', // Handle null product or product_name
                purchase_unit: product.unit?.unit_name || 'N/A', // Handle null unit or unit_name
                quantity: product.stock_in_quantity || 0, // If quantity is null, use 0
                purchase_price: product.stock_in_unit_price || 0, // If purchase_price is null, use 0
                discount: discount, // If discount is null, use 0
                quantity_amount: stock_in_total_amount + discount, // Add total amount and discount
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

    // purchase success after pdf open 
    var sessionInvoice = {!! json_encode(session('invoice')) !!};
    if (sessionInvoice) {
        window.open("{{ route('purchase_invoice_details_Pdf', '') }}/" + sessionInvoice, '_blank');
    }
</script>
