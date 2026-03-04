<x-default-layout>
    <div class="col-xl-12 px-5">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('message'))
            <div class="alert alert-{{ session('alert-type') }}">
                {{ session('message') }}
            </div>
        @endif
    </div>
    <div class="container-fluid">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <form id="additional_requisitions_store" method="POST" action="{{ route('additional_requisition_store') }}"
                enctype="multipart/form-data">
                @csrf
                <!-- @method('PUT') -->
                <div class="row px-5">
                    <!-- Produceable Products Content -->
                    <div class="col-md-12 p-5">
                        <div class="row">
                            <div class="col-md-12 card shadow">
                                <div class="card-header py-0 px-2">
                                    <div class="d-sm-flex align-items-center justify-content-between">
                                        <h1 class="h3 mb-0 text-gray-800">{{ __('Additional Requisition Pannel') }}</h1>
                                    </div>
                                    {{-- <label class="badge text-warning" id="jsdataerror"></label> --}}
                                </div>
                                <div class="card-body py-3 px-2">
                                    <div class="col-md-12 pb-3">
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <label>{{ __('Requisition Invoice') }}</label>
                                                <select class="form-select form-select-sm" data-control="select2" id="changeInvoiceNo"
                                                    name="invoice_no" data-control="select2" data-hide-search="false">
                                                    <option selected disabled>Select Product</option>
                                                    @foreach ($requisition_invoices as $invoice)
                                                        <option value="{{ $invoice->invoice_no }}">
                                                            {{ $invoice->invoice_no }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-1 d-none">
                                                <label for="order">{{ __('Status') }}</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="status" name="status">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <button type="button" id="addProductionProduct"
                                                    class="btn btn-sm btn-primary px-5 mt-6">{{ __('Add Additional Requisition') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- requisitions_list_div Produceable And Consumable Material Items List --}}
                    <div class="col-md-12 p-5 d-none" id="requisitions_list_div">
                        <div class="row">
                            <div class="col-md-12 card shadow px-5" id="produceable_product_div">
                                <!-- Produceable Products Content -->
                                <div class="d-sm-flex align-items-center justify-content-between pt-5">
                                    <h5 class="mb-0 text-gray-800">{{ __('Produceable Product:') }}</h5>
                                </div>
                                <table class="table table-striped table-bordered mt-4">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Product ID</th>
                                            <th>Product Name</th>
                                            <th>Unit</th>
                                            <th>Quantity</th>
                                            <th>Unit Price (BDT)</th>
                                            <th>Total Price (BDT)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="produceable_product_list_table">

                                    </tbody>
                                </table>
                                <!-- Consumable Products Content -->
                                <div class="d-sm-flex align-items-center justify-content-between pt-5">
                                    <h5 class="mb-0 text-gray-800">{{ __('Material Items List:') }}</h5>
                                </div>
                                <table class="table table-striped table-bordered mt-4">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Material ID</th>
                                            <th>Material Name</th>
                                            <th>Unit</th>
                                            <th>Quantity</th>
                                            <th>Unit Cost (BDT)</th>
                                            <th>Total Cost (BDT)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="consumable_product_list_table">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Add to Additional Consumable Products Content -->
                    <div class="col-md-12 p-5">
                        <div class="row">
                            <div class="col-md-12 card shadow px-5 d-none" id="add_to_consume_product_div">
                                <div class="d-sm-flex py-5 row">
                                    {{-- <div class="form-group col-md-2">
                                        <h5 class="mb-0 text-gray-800 pt-7">
                                            {{ __('Add New Requisition items:') }}</h5>
                                    </div> --}}
                                    <div class="form-group col-md-3">
                                        <label>{{ __('Select Material Product') }}</label>
                                        <select class="form-select form-select-sm" data-control="select2"
                                        id="newChangeConsumeProduct" name="" data-control="select2" data-hide-search="false">
                                            <option selected disabled>Select Material Item</option>
                                            @foreach ($consumable_products as $product)
                                                <option value="{{ $product->id }}">
                                                    {{ $product->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="Unit">{{ __('Unit') }}</label>
                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            readonly id="newConsumeProductUnit" name="" />
                                        <input type="hidden" class="form-control form-control-sm form-control-solid"
                                            readonly id="newConsumeProductUnitId" name="" />
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="Quantity">{{ __('Quantity') }}</label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="newConsumeQuantity" name=""
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                    </div>

                                    <input type="hidden" class="form-control form-control-sm form-control-solid"
                                        readonly id="newConsumePurchasePrice" name="" />
                                    <div class="form-group col-md-2">
                                        <button type="button" id="addNewConsumeProduct"
                                            class="btn btn-sm btn-primary px-5 mt-6">{{ __('Add Material Item') }}</button>
                                    </div>
                                </div>
                                {{-- new items list --}}
                                <table class="table table-striped table-bordered mt-4">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Material ID</th>
                                            <th>Material Name</th>
                                            <th>Unit</th>
                                            <th>Quantity</th>
                                            <th>Unit Cost (BDT)</th>
                                            <th>Total Cost (BDT)</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="add_to_consume_product_add">

                                    </tbody>
                                </table>

                                {{-- form submit action --}}
                                <div class="d-flex justify-content-end">
                                    <button type="submit" id="submitAllProceed"
                                        class="btn btn-sm btn-success px-5 mx-5 mb-10">{{ __('Save Requisition') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </form>
        </div>
        <!--end::Content wrapper-->
    </div>
</x-default-layout>

<script type="text/javascript">
    document.getElementById("additional_requisitions_store").addEventListener("keydown", function(event) {
        // Check if the pressed key is Enter
        if (event.key === "Enter") {
            // Prevent default form submission
            event.preventDefault();
        }
    });
    {
        const intRx = /\d/,
            integerChange = (event) => {
                // console.log(event.key, )
                if (
                    (event.key.length > 1) ||
                    event.ctrlKey ||
                    ((event.key === "-") && (!event.currentTarget.value.length)) ||
                    intRx.test(event.key)
                ) return;
                event.preventDefault();
            };

        for (let input of document.querySelectorAll(
            'input[type="number"][step="1"]'
        )) input.addEventListener("keydown", integerChange);
    }
    // on change invoice number
    $('#changeInvoiceNo').on('change', function() {
        var invoiceNo = $(this).val();
        console.log('invoiceNo', invoiceNo);

        if (invoiceNo) {
            $.ajax({
                url: 'requisitionDetails/' + invoiceNo,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    const productionIn = response.productionIn;
                    const consumeOut = response.consumeOut;
                    // console.log('productionIn', productionIn);
                    // console.log('consumeOut', consumeOut);

                    // -------- IF productionIn Products get data ---------
                    if (productionIn) {
                        $('#requisitions_list_div').removeClass('d-none');

                        $('#produceable_product_list_table').empty();
                        $.each(productionIn, function(key, value) {
                            $('#produceable_product_list_table').append(
                                `<tr>
                                    <td class="text-center">` + value.product_id + `</td>
                                    <td>` + value.product.product_name + `</td>
                                    <td class="text-center">` + value.product.unit.unit_name + `</td>
                                    <td class="text-center">` + value.stock_in_quantity + `</td>
                                    <td class="text-end">` + value.stock_in_unit_price + `</td>
                                    <td class="text-end">` + value.stock_in_total_amount + `</td>
                                </tr>`
                            );
                        });
                    } else {
                        console.log('No data found');
                    }
                    // -------- IF Consumed Products get data ---------
                    if (consumeOut) {
                        $('#consumable_product_list_table').empty();
                        $.each(consumeOut, function(key, value) {
                            $('#consumable_product_list_table').append(
                                `<tr>
                                    <td class="text-center">` + value.product_id + `</td>
                                    <td>` + value.product.product_name + `</td>
                                    <td class="text-center">` + value.product.unit.unit_name + `</td>
                                    <td class="text-center">` + value.stock_out_quantity + `</td>
                                    <td class="text-end">` + value.stock_out_unit_price + `</td>
                                    <td class="text-end">` + value.stock_out_total_amount + `</td>
                                </tr>`
                            );
                        });
                    } else {
                        console.log('No data found');
                    }
                }
            });
        } else {

        }
    });
    // -------------- add to cart button action -----------
    $('#addProductionProduct').on('click', function(e) {
        $('#add_to_consume_product_div').removeClass('d-none');
        $('#add_to_consume_product_add').empty();
    });
    // open modal add product items
    $('#addNewRequisitionItems_div_open').on('click', function(e) {
        $('#addNewRequisitionItems').removeClass('d-none');
    });
    // close modal add product items
    $('#addNewRequisitionItems_div_close').on('click', function(e) {
        $('#addNewRequisitionItems').addClass('d-none');
    });
    // get product detailes modal add additional requisition material items 
    $('#newChangeConsumeProduct').on('change', function() {
        var productID = $(this).val();
        var url = '{{ route('billOfMatrialProductDetails', ':id') }}';
        url = url.replace(':id', productID);
        if (productID) {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    // console.log(data);
                    if (data) {
                        $('#newConsumeQuantity').val('');

                        $.each(data, function(key, value) {
                            var unit = value.unit.unit_name;
                            var unit_id = value.unit.id;
                            var purchase_price = value.purchase_price;
                            $('#newConsumeProductUnit').val(unit);
                            $('#newConsumeProductUnitId').val(unit_id);
                            $('#newConsumePurchasePrice').val(purchase_price);

                        });
                    } else {

                    }
                }
            });
        } else {

        }
    });
    // --- modal add to cart button submite and Added Consumable Products List Content ----
    let consume_product_table_id = 0;
    $('#addNewConsumeProduct').on('click', function() {
        let consume_product_name = $('#newChangeConsumeProduct option:selected').text();
        let consume_product_id = $('#newChangeConsumeProduct option:selected').val();
        let consume_quantity = $('#newConsumeQuantity').val();
        let consume_unit = $('#newConsumeProductUnit').val();
        let consume_unit_id = $('#newConsumeProductUnitId').val();
        let product_per_unit_price = $('#newConsumePurchasePrice').val();
        let product_total_price = consume_quantity * product_per_unit_price;
        product_total_price = parseFloat(product_total_price.toFixed(2));

        if (consume_product_id > 0) {
            if (consume_quantity > 0) {
                // Check for duplicate consume_product_id
                let duplicate_found = false;
                $('#new_consume_product_add tr').each(function() {
                    if ($(this).find('input[name="new_consume_product_add[]"]').val() ===
                        consume_product_id) {
                        duplicate_found = true;
                        return false; // break out of loop if duplicate found
                    }
                });
                if (duplicate_found) {
                    alert('This product has already been added to the list.');
                } else {
                    $('#add_to_consume_product_add').append(
                        `<tr data-id="${consume_product_table_id}">
                        <td class="text-center">` + consume_product_id + `<input type="hidden" name="" value=` +
                        consume_product_id + ` ></td>
                        <td>` + consume_product_name + `<input type="hidden" name="add_consume_product_id[]" value=` +
                        consume_product_id + ` ></td>
                        <td class="text-center">` + consume_unit +
                        `<input type="hidden" name="add_consume_product_unit[]" value=` + consume_unit_id + ` ></td>
                        <td class="text-center">` + consume_quantity +
                        `<input type="hidden" name="add_consume_product_quantity[]" value=` +
                        consume_quantity + ` ></td>
                        <td class="text-end">` + product_per_unit_price +
                        `<input type="hidden" name="add_consume_unit_price[]" value=` +
                        product_per_unit_price + ` ></td>
                        <td class="text-end">` + product_total_price +
                        `<input type="hidden" name="add_consume_total_price[]" value=` +
                        product_total_price + ` ></td>
                        <td class="text-end my-0 d-flex"> 
                            <button data-id="${consume_product_table_id}" type="button" class="add_to_new_consume_product_delete btn btn-sm btn-danger py-1 mx-3" >Delete</button>
                        </td>
                    </tr>`
                    );
                    ++consume_product_table_id;
                    $('#newConsumeQuantity').val('');
                }
            } else {
                alert("Please Input Consume Quantity Number");
            }
        } else {
            alert("Please Select Product");
        }
    });

    //  Delete to Consumable Products List Content
    $('#add_to_consume_product_add').on('click', function(e) {
        if (e.target.matches('.consume_product_id_delete')) {
            const {
                id
            } = e.target.dataset;

            const selector = `tr[data-id="${id}"]`;
            $(selector).remove();
        }
        if (e.target.matches('.add_to_new_consume_product_delete')) {
            const {
                id
            } = e.target.dataset;

            const selector = `tr[data-id="${id}"]`;
            $(selector).remove();
        }
    });

    // Confarm message after form on click ========= 
    document.getElementById("submitAllProceed").addEventListener('click', function (event) { ConfarmMsg("additional_requisitions_store", "Are You sure.. Want to Save?", "save", event) });
    // Show message after form submission ========= 
    var msg = {!! json_encode(session('message')) !!};
    var type = {!! json_encode(session('alert-type')) !!};
    document.addEventListener('DOMContentLoaded', AlertCall(msg, type));
</script>
