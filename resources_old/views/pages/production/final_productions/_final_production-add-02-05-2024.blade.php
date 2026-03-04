<x-default-layout>
    <div class="container-fluid">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form id="final_productions_store" method="POST" action="{{ route('finalproductions.store') }}"
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
                                        <h1 class="h3 mb-0 text-gray-800">{{ __('Requisition Pannel') }}</h1>
                                    </div>
                                    <label class="badge text-warning" id="jsdataerror"></label>
                                </div>
                                <div class="card-body py-3 px-2">
                                    <div class="col-md-12 pb-3">
                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <h5 class="mb-0 text-gray-800 pt-7">{{ __('Produceable Products:') }}
                                                </h5>
                                            </div>

                                            <!-- <div class="form-group col-md-3">
                                                <label for="order">{{ __('Batch No') }}</label>
                                                <input type="text" class="form-control form-control-sm" id=""
                                                       name="">
                                                <input type="hidden" class="form-control form-control-sm" id=""
                                                       name="">
                                            </div> -->

                                            <div class="form-group col-md-3">
                                                <label>{{ __('Product') }}</label>
                                                <select id="changeProduct" class="form-control form-control-sm"
                                                    name="production_product_id" required>
                                                    <option selected disabled>Select Product</option>
                                                    @foreach ($produceable_products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->product_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-1">
                                                <label for="order">{{ __('Product Unit') }}</label>
                                                <input type="text"
                                                    class="form-control form-control-sm form-control-solid" readonly
                                                    id="productionUnitName" name="">
                                                <input type="hidden"
                                                    class="form-control form-control-sm form-control-solid" readonly
                                                    value="" id="productionUnitId"
                                                    name="production_product_unit_id">
                                            </div>
                                            <div class="form-group col-md-1">
                                                <label for="order">{{ __('Quantity') }}</label>
                                                <input type="text" class="form-control form-control-sm" value="" id="productionQuantity" name="production_product_quantity" step="1" min="0"
                                                oninput="this.value = this.value.replace(/\D/g, '');">

                                                {{-- <input type="number"
                                                    class="form-control form-control-sm form-control-solid"
                                                    value="" id="productionQuantity"
                                                    name="production_product_quantity" step="1"
                                                    pattern="^[-\d]\d*$"> --}}
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="order">{{ __('Unit Cost') }}</label>
                                                <input type="number" id="productionUnitCost"
                                                    class="form-control form-control-sm form-control-solid" readonly
                                                    value="" id="unit_price" name="production_product_unit_price"
                                                    step="1" pattern="^[-\d]\d*$">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="order">{{ __('Total Price') }}</label>
                                                <input type="number" id="productionTotalPrice"
                                                    class="form-control form-control-sm form-control-solid" readonly
                                                    value="" id="produceable_total_price"
                                                    name="production_product_total_price" step="1"
                                                    pattern="^[-\d]\d*$">
                                            </div>
                                            <div class="form-group col-md-1">
                                                <button type="button" id="addProductionProduct"
                                                    class="btn btn-sm btn-primary px-5 mt-6">{{ __('Add to Cart') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Consumable Products Content -->
                    <div class="col-md-12 px-5">
                        <div class="row">
                            <div class="col-md-12 card shadow px-5 d-none" id="consume_product_div">

                                <div class="d-sm-flex align-items-center justify-content-between py-5">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('Requisition items') }}</h1>
                                    <a class="btn btn-sm btn-danger" id="consume_product_div_close">X</a>
                                </div>
                                <table class="table table-striped table-bordered mt-4">
                                    <thead>
                                        <tr>
                                            <th>Product ID</th>
                                            <th>Product Name</th>
                                            <th>Product Unit</th>
                                            <th>Quantity</th>
                                            <th>Unit Cost</th>
                                            <th>Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody id="consume_product_add">

                                    </tbody>
                                    <tfooter>
                                        <tr>
                                            <th colspan="5" class="fs-5 text-dark text-end">Total Price</th>
                                            <th id="consume_product_total_price"></th>
                                        </tr>
                                    </tfooter>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Wastage Products Content -->
                    {{-- <div class="col-md-6 my-5">
                        <div class="row ms-0 ms-md-2">
                            <div class="col-md-12 card shadow px-5 d-none" id="wastage_product_div">
                                <div class="d-sm-flex align-items-center justify-content-between py-5">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('By Product / Wastage Product') }}</h1>
                                </div>
                                <table class="table table-striped table-bordered mt-4">
                                    <thead>
                                        <tr>
                                            <th>Product ID</th>
                                            <th>Product Name</th>
                                            <th>Product Unit</th>
                                            <th>Quantity</th>
                                            <th>Unit Cost</th>
                                            <th>Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody id="wastage_product_add">

                                    </tbody>
                                    <tfooter>
                                        <tr>
                                            <th colspan="5" class="fs-5 text-dark text-end">Total Price</th>
                                            <th id="wastage_product_total_price"></th>
                                        </tr>
                                    </tfooter>
                                </table>
                            </div>
                        </div>
                    </div> --}}

                    {{-- production_product_add --}}
                    <div class="col-md-12 p-5">
                        <div class="row">
                            <div class="col-md-12 card shadow px-5">

                                <div class="d-sm-flex align-items-center justify-content-between py-5">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('Product to be Produce') }}</h1>
                                </div>

                                <table id="production_product_add" class="table table-bordered mt-4">
                                    <tr class="text-center">
                                        <th>Product ID</th>
                                        <th>Product Name</th>
                                        <th>Product Unit</th>
                                        <th>Quantity</th>
                                        <th>Unit Cost</th>
                                        <th>Total Price</th>
                                        <th style="width: 100px;">Action</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- add_to_Consumable add New Requisition Items  -->
                    <div class="col-md-12 d-none" id="addNewRequisitionItems">
                        <div class="row me-0 me-md-2">
                            <div class="col-md-12 card shadow my-5">
                                <div class="card-body py-3">
                                    <div class="col-md-12 pb-3">
                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <h5 class="mb-0 text-gray-800 pt-7">
                                                    {{ __('Add New Requisition items:') }}</h5>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>{{ __(' Product') }}</label>
                                                <select id="newChangeConsumeProduct"
                                                    class="form-control form-control-sm" name="" required>
                                                    <option selected disabled>Select Product</option>
                                                    @foreach ($consumable_products as $product)
                                                        <option value="{{ $product->id }}">
                                                            {{ $product->product_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="Quantity">{{ __('Quantity') }}</label>
                                                <input type="text" class="form-control form-control-sm" value="" id="newConsumeQuantity" name="" step="1" min="0"
                                                oninput="this.value = this.value.replace(/\D/g, '');">

                                                {{-- <input type="number" class="form-control form-control-sm"
                                                    id="newConsumeQuantity" name="" step="1"
                                                    pattern="^[-\d]\d*$"> --}}
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="Unit">{{ __('Unit') }}</label>
                                                <input type="text" class="form-control form-control-sm form-control-solid" readonly
                                                    id="newConsumeProductUnit" name="" />
                                            </div>
                                            <input type="hidden"
                                                class="form-control form-control-sm form-control-solid" readonly
                                                id="newConsumeProductUnitId" name="" />
                                            <input type="hidden"
                                                class="form-control form-control-sm form-control-solid" readonly
                                                id="newConsumePurchasePrice" name="" />
                                            <div class="form-group col-md-2">
                                                <button type="button" id="addNewConsumeProduct"
                                                    class="btn btn-sm btn-primary px-5 mt-6">{{ __('Add to Cart') }}</button>
                                            </div>
                                            <div class="form-group col-md-1 text-end">
                                                <a class="btn btn-sm btn-danger mt-5" id="addNewRequisitionItems_div_close">X</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- add_to_Consumable Products Content -->
                    <div class="col-md-12 p-5">
                        <div class="row">
                            <div class="col-md-12 card shadow px-5 d-none" id="add_to_consume_product_div">
                                <div class="d-sm-flex align-items-center justify-content-between py-5">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('Requisition items') }}</h1>
                                    <a class="btn btn-sm btn-primary" id="addNewRequisitionItems_div_open">+ Add New Requisition Items</a>
                                </div>
                                <table class="table table-striped table-bordered mt-4">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Product ID</th>
                                            <th>Product Name</th>
                                            <th>Product Unit</th>
                                            <th>Quantity</th>
                                            <th>Unit Cost</th>
                                            <th>Total Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="add_to_consume_product_add">

                                    </tbody>
                                    {{-- <tfooter>
                                        <tr>
                                            <th colspan="5" class="fs-5 text-dark text-end">Total Price</th>
                                            <th id="add_to_consume_product_total_price"></th>
                                        </tr>
                                    </tfooter> --}}
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- add_to_Wastage Products Content -->
                    {{-- <div class="col-md-6 my-5">
                        <div class="row ms-0 ms-md-2">
                            <div class="col-md-12 card shadow px-5 d-none" id="add_to_wastage_product_div">
                                <div class="d-sm-flex align-items-center justify-content-between py-5">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('By Product / Wastage Product') }}</h1>
                                </div>
                                <table class="table table-striped table-bordered mt-4">
                                    <thead>
                                        <tr>
                                            <th>Product ID</th>
                                            <th>Product Name</th>
                                            <th>Product Unit</th>
                                            <th>Quantity</th>
                                            <th>Unit Cost</th>
                                            <th>Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody id="add_to_wastage_product_add">

                                    </tbody>
                                    <tfooter>
                                        <tr>
                                            <th colspan="5" class="fs-5 text-dark text-end">Total Price</th>
                                            <th id="add_to_wastage_product_total_price"></th>
                                        </tr>
                                    </tfooter>
                                </table>
                            </div>
                        </div>
                    </div> --}}

                </div>
                <div class="d-flex justify-content-end">
                    {{-- <a class="btn btn-sm btn-success px-10 mt-5 me-5" href="{{ route('finalproductions.index') }}">Cancel</a> --}}
                    @can('create finalproductions')
                        <button type="submit" id="submitAllProceed"
                            class="btn btn-sm btn-primary px-5 mx-5 mb-10">{{ __('All Stock Out') }}</button>
                    @endcan
                </div>
            </form>
        </div>
        <!--end::Content wrapper-->
    </div>
</x-default-layout>

<script type="text/javascript">
    document.getElementById("final_productions_store").addEventListener("keydown", function(event) {
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

    $('#submitAllProceed').on('click', function() {
        $("select#changeProduct").prop('disabled', false);
        $("input#productionUnitName").prop('disabled', false);
        $("input#productionQuantity").prop('disabled', false);

    });

    //================ Production Details ===============   

    // View button onclike call
    var OnchangeProductCallClick = function(p_id, p_qty) {

        // var productID =  $('#changeProduct').val();
        // var quantity =  $('#quantity').val();
        var productID = p_id;
        var quantity = p_qty;
        let consume_id = 0;
        let wastage_id = 0;

        if (productID) {
            $.ajax({
                url: 'productionDetails/' + productID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",

                success: function(response) {
                    const data = response.productDetails;
                    const data2 = response.productionDetails;

                    var consume_product_total_price = 0;
                    var wastage_product_total_price = 0;
                    if (data2) {
                        $('#consume_product_div').removeClass('d-none');
                        $('#consume_product_add').empty();
                        $('#wastage_product_div').removeClass('d-none');
                        $('#wastage_product_add').empty();

                        $.each(data2, function(key, value) {
                            // -------- Produceable Products get data ----------
                            var unit_id = value.production.unit_id;
                            var unit_name = data.unit.unit_name;
                            var Rqty = value.production.quantity;
                            // -------- Produceable Products Show data ---------
                            var produceable_qty = quantity ? quantity : Rqty;
                            // $('#productionQuantity').val(produceable_qty);
                            $('#productionUnitId').val(unit_id);
                            $('#productionUnitName').val(unit_name);

                            // -------- Consumed and ByProduct/Wastage Products get data ---------
                            var product_id = value.product_id;
                            var product_name = value.product.product_name;
                            var product_unit_id = value.product_unit;
                            var product_unit_name = value.unit.unit_name;
                            var product_quantity = quantity ? (value.quantity * quantity) /
                                Rqty : value.quantity;
                            var product_per_unit_price = value.product.purchase_price;
                            var product_total_price = product_per_unit_price * product_quantity;
                            var type = value.type;

                            if (type == 1) {
                                consume_product_total_price = consume_product_total_price +
                                    product_total_price;
                                var produceable_unit_price = consume_product_total_price /
                                    produceable_qty;
                                $('#productionUnitCost').val(produceable_unit_price);
                                $('#productionTotalPrice').val(consume_product_total_price);
                                $('#consume_product_total_price').text(
                                    consume_product_total_price);

                                $('#consume_product_add').append(
                                    `<tr data-id="${consume_id}">
                                    <td class="text-center">` + product_id + `<input type="hidden" name="" value=` + product_id + ` ></td>
                                    <td>` + product_name + `<input type="hidden" name="consume_product_id[]" value=` +
                                    product_id + ` ></td>
                                    <td class="text-center">` + product_unit_name +
                                    `<input type="hidden" name="consume_product_unit[]" value=` +
                                    product_unit_id + ` ></td>
                                    <td class="text-center">` + product_quantity +
                                    `<input type="hidden" name="consume_product_quantity[]" value=` +
                                    product_quantity + ` ></td>

                                    <td class="text-end">` + product_per_unit_price +
                                    `<input type="hidden" name="consume_unit_price[]" value=` +
                                    product_per_unit_price + ` ></td>
                                    <td class="text-end">` + product_total_price +
                                    `<input type="hidden" name="consume_total_price[]" value=` +
                                    product_total_price + ` ></td>
                                </tr>`
                                );
                                ++consume_id;
                            } else {
                                // wastage_product_total_price = wastage_product_total_price +
                                //     product_total_price;
                                // $('#wastage_product_total_price').text(
                                //     wastage_product_total_price);

                                // $('#wastage_product_add').append(
                                //     `<tr data-id2="${wastage_id}">
                                //     <td>` + product_id + `<input type="hidden" name="" value=` + product_id + ` ></td>
                                //     <td>` + product_name + `<input type="hidden" name="wastage_product_id[]" value=` +
                                //     product_id + ` ></td>
                                //     <td>` + product_unit_name +
                                //     `<input type="hidden" name="wastage_product_unit[]" value=` +
                                //     product_unit_id + ` ></td>
                                //     <td>` + product_quantity +
                                //     `<input type="hidden" name="wastage_product_quantity[]" value=` +
                                //     product_quantity + ` ></td>

                                //     <td>` + product_per_unit_price +
                                //     `<input type="hidden" name="wastage_unit_price[]" value=` +
                                //     product_per_unit_price + ` ></td>
                                //     <td>` + product_total_price +
                                //     `<input type="hidden" name="wastage_total_price[]" value=` +
                                //     product_total_price + ` ></td>
                                // </tr>`
                                // );
                                // ++wastage_id;
                            }
                        });

                    } else {

                    }
                }
            });
        } else {

        }
        // $('#productionQuantity').val('');
        // $('#consume_product_add').val('');
        // $('#wastage_product_add').val('');
    }
    // View button onclike open div close
    $('#consume_product_div_close').on('click', function(e) {
        $('#consume_product_div').addClass('d-none');
    });

    // Produceable Products on change
    var OnchangeProductCall = function() {

        var productID = $('#changeProduct').val();
        var quantity = $('#productionQuantity').val();
        console.log(quantity);
        let consume_id = 0;
        let wastage_id = 0;

        if (productID) {
            $.ajax({
                url: 'productionDetails/' + productID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",

                success: function(response) {
                    const data = response.productDetails;
                    const data2 = response.productionDetails;

                    var consume_product_total_price = 0;
                    var wastage_product_total_price = 0;
                    if (data2) {
                        // $('#consume_product_div').removeClass('d-none');
                        $('#consume_product_add').empty();
                        // $('#wastage_product_div').removeClass('d-none');
                        $('#wastage_product_add').empty();

                        $.each(data2, function(key, value) {
                            // -------- Produceable Products get data ----------
                            var unit_id = value.production.unit_id;
                            var unit_name = data.unit.unit_name;
                            var Rqty = value.production.quantity;
                            // -------- Produceable Products Show data ---------
                            var produceable_qty = quantity ? quantity : Rqty;
                            // $('#productionQuantity').val(produceable_qty);
                            $('#productionUnitId').val(unit_id);
                            $('#productionUnitName').val(unit_name);

                            // -------- Consumed and ByProduct/Wastage Products get data ---------
                            var product_id = value.product_id;
                            var product_name = value.product.product_name;
                            var product_unit_id = value.product_unit;
                            var product_unit_name = value.unit.unit_name;
                            var product_quantity = quantity ? (value.quantity * quantity) /
                                Rqty : value.quantity;
                            // product_quantity = parseFloat(product_quantity.toFixed(2));
                            var product_per_unit_price = value.product.purchase_price;
                            var product_total_price = parseFloat((product_per_unit_price *
                                product_quantity).toFixed(2));
                            var type = value.type;

                            if (type == 1) {
                                consume_product_total_price = consume_product_total_price +
                                    product_total_price;
                                var produceable_unit_price = consume_product_total_price /
                                    produceable_qty;
                                $('#productionUnitCost').val(produceable_unit_price);
                                $('#productionTotalPrice').val(consume_product_total_price);
                                $('#consume_product_total_price').text(
                                    consume_product_total_price);

                                $('#consume_product_add').append(
                                    `<tr data-id="${consume_id}">
                                    <td class="text-center">` + product_id + `<input type="hidden" name="" value=` + product_id + ` ></td>
                                    <td class="text-center">` + product_name + `<input type="hidden" name="consume_product_id[]" value=` +
                                    product_id + ` ></td>
                                    <td class="text-center">` + product_unit_name +
                                    `<input type="hidden" name="consume_product_unit[]" value=` +
                                    product_unit_id + ` ></td>
                                    <td class="text-center">` + product_quantity +
                                    `<input type="hidden" name="consume_product_quantity[]" value=` +
                                    product_quantity + ` ></td>

                                    <td class="text-end">` + product_per_unit_price +
                                    `<input type="hidden" name="consume_unit_price[]" value=` +
                                    product_per_unit_price + ` ></td>
                                    <td class="text-end">` + product_total_price +
                                    `<input type="hidden" name="consume_total_price[]" value=` +
                                    product_total_price + ` ></td>
                                </tr>`
                                );
                                ++consume_id;
                            } else {
                                // wastage_product_total_price = wastage_product_total_price +
                                //     product_total_price;
                                // $('#wastage_product_total_price').text(
                                //     wastage_product_total_price);

                                // $('#wastage_product_add').append(
                                //     `<tr data-id2="${wastage_id}">
                                //     <td>` + product_id + `<input type="hidden" name="" value=` + product_id + ` ></td>
                                //     <td>` + product_name + `<input type="hidden" name="wastage_product_id[]" value=` +
                                //     product_id + ` ></td>
                                //     <td>` + product_unit_name +
                                //     `<input type="hidden" name="wastage_product_unit[]" value=` +
                                //     product_unit_id + ` ></td>
                                //     <td>` + product_quantity +
                                //     `<input type="hidden" name="wastage_product_quantity[]" value=` +
                                //     product_quantity + ` ></td>

                                //     <td>` + product_per_unit_price +
                                //     `<input type="hidden" name="wastage_unit_price[]" value=` +
                                //     product_per_unit_price + ` ></td>
                                //     <td>` + product_total_price +
                                //     `<input type="hidden" name="wastage_total_price[]" value=` +
                                //     product_total_price + ` ></td>
                                // </tr>`
                                // );
                                // ++wastage_id;
                            }
                        });

                    } else {

                    }
                }
            });
        } else {

        }
        // $('#productionQuantity').val('');
        // $('#consume_product_add').val('');
        // $('#wastage_product_add').val('');
    }
    $('#changeProduct').on('change', OnchangeProductCall);
    $('#productionQuantity').on('change', OnchangeProductCall);

    //================ Production add to card =============== 
    $('#production_product_add').on('click', function(e) {
        if (e.target.matches('.production_product_delete')) {
            const {
                id
            } = e.target.dataset;

            const selector = `tr[id="${id}"]`;
            $(selector).remove();

            addConsumeProduct();
        }

        if (e.target.matches('.production_product_view')) {
            const {
                id
            } = e.target.dataset;

            const selector = `tr[data-id="${id}"]`;
            var add_to_product_id = 0;
            var add_to_product_Qty = 0;

            $(selector).each(function() {
                // Inside the iteration function, find specific input values within each table row

                if ($(this).find('input[name="add_to_product_id[]"]').val()) {
                    add_to_product_id = $(this).find('input[name="add_to_product_id[]"]').val();
                    add_to_product_Qty = $(this).find('input[name="add_to_quantity[]"]').val();
                }
                // Now you can use or process the found values as needed
                // console.log(`Product ID: ${add_to_product_id}, Quantity: ${add_to_product_Qty}`);

            });
            console.log(`Product ID: ${add_to_product_id}, Quantity: ${add_to_product_Qty}`);
            OnchangeProductCallClick(add_to_product_id, add_to_product_Qty);

        }
    });

    let production_product_id_add = 0;
    $('#addProductionProduct').on('click', function() {
        let add_to_product_name = $('#changeProduct option:selected').text();
        let add_to_product_id = $('#changeProduct option:selected').val();
        let add_to_unit_id = $('#productionUnitId').val();
        let add_to_unit_name = $('#productionUnitName').val();
        let add_to_quantity = $('#productionQuantity').val();
        let add_to_unit_cost = $('#productionUnitCost').val();
        let add_to_total_price = $('#productionTotalPrice').val();

        if (add_to_product_id > 0) {
            if (add_to_quantity > 0) {
                // Check for duplicate consume_product_id
                let duplicate_found = false;
                $('#production_product_add tr').each(function() {
                    if ($(this).find('input[name="add_to_product_id[]"]').val() === add_to_product_id) {
                        duplicate_found = true;
                        return false; // break out of loop if duplicate found
                    }
                });

                if (duplicate_found) {
                    $('#productionQuantity').val('');
                    alert('This product has already been added to the list.');

                } else {
                    $('#production_product_add').append(
                        `<tr id="${production_product_id_add}">
                        <td class="text-center">` + add_to_product_id + `<input type="hidden" name="" value=` + add_to_product_id + ` ></td>
                        <td>` + add_to_product_name + `<input type="hidden" name="add_to_product_id[]" value=` +
                        add_to_product_id + ` ></td>
                        <td class="text-center">` + add_to_unit_name + `<input type="hidden" name="add_to_unit_id[]" value=` +
                        add_to_unit_id + ` ></td>
                        <td class="text-center">` + add_to_quantity +
                        `<input type="hidden" class="" style="width:80px" name="add_to_quantity[]" value=` +
                        add_to_quantity + ` ></td>
                        <td class="text-end">` + add_to_unit_cost +
                        `<input type="hidden" class="" style="width:80px" name="add_to_unit_cost[]" value=` +
                        add_to_unit_cost + ` readonly></td>
                        <td class="text-end">` + add_to_total_price +
                        `<input type="hidden" class="" style="width:80px" name="add_to_total_price[]" value=` +
                        add_to_total_price + ` readonly></td>
                        <td class="text-right my-0 d-flex"> 
                            <button data-id="${production_product_id_add}" type="button" class="production_product_delete btn btn-sm btn-danger py-1 mx-3" >Delete</button>
                            <button  data-id="${production_product_id_add}" type="button" class="production_product_view btn btn-sm btn-success py-1 mx-3" >View</button>
                        </td>
                    </tr>`
                    );
                    ++production_product_id_add;

                    $('#productionQuantity').val('');
                    $('#productionUnitName').val('');
                    $('#productionUnitId').val('');
                }
            } else {
                alert("Please Input Quantity Number");
            }
        } else {
            alert("Please Select Product");
        }
    });

    // add_to_consume_product_div
    $('#addProductionProduct').on('click', addConsumeProduct);
    let consume_product_table_id = 0;

    function addConsumeProduct() {

        $('#add_to_consume_product_div').removeClass('d-none');
        $('#add_to_consume_product_add').empty();
        consume_product_table_id = 0;
        var consumeProductID;
        var consumeQuantity;
        $('#production_product_add tr').each(function() {
            consumeProductID = $(this).find('input[name="add_to_product_id[]"]').val();
            consumeQuantity = $(this).find('input[name="add_to_quantity[]"]').val();


            if (consumeProductID) {
                $.ajax({
                    url: 'productionDetails/' + consumeProductID,
                    type: "GET",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: "json",

                    success: function(response) {
                        const data = response.productDetails;
                        const data2 = response.productionDetails;

                        if (data2) {

                            $.each(data2, function(key, value) {

                                var Rqty = value.production.quantity;

                                var product_id = value.product_id;
                                var product_name = value.product.product_name;
                                var product_unit_id = value.product_unit;
                                var product_unit_name = value.unit.unit_name;
                                var product_quantity = (value.quantity / Rqty) *
                                    consumeQuantity;
                                product_quantity = parseFloat(product_quantity.toFixed(2));
                                var product_per_unit_price = value.product.purchase_price;
                                var product_total_price = parseFloat((
                                        product_per_unit_price * product_quantity)
                                    .toFixed(2));

                                if (value.type == 1) {

                                    $('#add_to_consume_product_add').append(

                                            `<tr data-id="${consume_product_table_id}">
                                            <td class="text-center">` + product_id + `<input type="hidden" name="" value=` + product_id + ` ></td>
                                            <td>` + product_name + `<input type="hidden" name="add_consume_product_id[]" value=` +
                                            product_id + ` ></td>
                                            <td class="text-center">` + product_unit_name +
                                            `<input type="hidden" name="add_consume_product_unit[]" value=` +
                                            product_unit_id + ` ></td>
                                            <td class="text-center">` + product_quantity +
                                            `<input type="hidden" name="add_consume_product_quantity[]" value=` +
                                            product_quantity + ` ></td>

                                            <td class="text-end">` + product_per_unit_price +
                                            `<input type="hidden" name="add_consume_unit_price[]" value=` +
                                            product_per_unit_price + ` ></td>
                                            <td class="text-end">` + product_total_price +
                                            `<input type="hidden" name="add_consume_total_price[]" value=` +
                                            product_total_price + ` ></td>
                                            <td class="text-right my-0 d-flex"> 
                                                <button data-id="${consume_product_table_id}" type="button" class="consume_product_id_delete btn btn-sm btn-danger py-1 mx-3" >Delete</button>
                                            </td>
                                        </tr>`
                                        )
                                        ++consume_product_table_id;
                                }
                            });
                        }
                    }
                });
            }
        });
    }

    // new add_to_consume_product
    $('#addNewRequisitionItems_div_open').on('click', function(e) {
        $('#addNewRequisitionItems').removeClass('d-none');
    });
    $('#addNewRequisitionItems_div_close').on('click', function(e) {
        $('#addNewRequisitionItems').addClass('d-none');
    });

    $('#newChangeConsumeProduct').on('change', function() {
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

    // let new_consume_id = 0;
    $('#addNewConsumeProduct').on('click', function() {
        let consume_product_name = $('#newChangeConsumeProduct option:selected').text();
        let consume_product_id = $('#newChangeConsumeProduct option:selected').val();
        let consume_quantity = $('#newConsumeQuantity').val();
        let consume_unit = $('#newConsumeProductUnit').val();
        let consume_unit_id = $('#newConsumeProductUnitId').val();
        let product_per_unit_price = $('#newConsumePurchasePrice').val();
        let product_total_price = consume_quantity*product_per_unit_price;
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
                        <td class="text-center">` + consume_product_id + `<input type="hidden" name="" value=` + consume_product_id + ` ></td>
                        <td>` + consume_product_name + `<input type="hidden" name="add_consume_product_id[]" value=` + consume_product_id + ` ></td>
                        <td class="text-center">` + consume_unit + `<input type="hidden" name="add_consume_product_unit[]" value=` + consume_unit_id + ` ></td>
                        <td class="text-center">` + consume_quantity + `<input type="hidden" name="add_consume_product_quantity[]" value=` + consume_quantity + ` ></td>
                        <td class="text-end">` + product_per_unit_price + `<input type="hidden" name="add_consume_unit_price[]" value=` + product_per_unit_price + ` ></td>
                        <td class="text-end">` + product_total_price + `<input type="hidden" name="add_consume_total_price[]" value=` + product_total_price + ` ></td>
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

     //================ Production add to card =============== 
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
</script>
