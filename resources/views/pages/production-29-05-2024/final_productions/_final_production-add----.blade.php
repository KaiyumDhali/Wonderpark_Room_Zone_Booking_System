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
            <form id="" method="POST" action="{{ route('finalproductions.store') }}"
                enctype="multipart/form-data">
                @csrf
                <!-- @method('PUT') -->

                <div class="row p-5">
                    <!-- Produceable Products Content -->
                    <div class="col-md-12 p-5">
                        <div class="row">
                            <div class="col-md-12 card shadow">
                                <div class="card-header py-0">
                                    <div class="d-sm-flex align-items-center justify-content-between">
                                        <h1 class="h3 mb-0 text-gray-800">{{ __('Final Production Pannel') }}</h1>
                                    </div>
                                    <label class="badge text-warning" id="jsdataerror"></label>
                                </div>
                                <div class="card-body py-3">
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

                                            <div class="form-group col-md-2">
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
                                            <div class="form-group col-md-2">
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
                                                <input type="number"
                                                    class="form-control form-control-sm form-control-solid"
                                                    value="" id="productionQuantity"
                                                    name="production_product_quantity" step="1"
                                                    pattern="^[-\d]\d*$">
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
                    <div class="col-md-6 my-5">
                        <div class="row me-0 me-md-2">
                            <div class="col-md-12 card shadow px-5 d-none" id="consume_product_div">
                                <div class="d-sm-flex align-items-center justify-content-between py-5">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('Product to be Consumed') }}</h1>
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
                    <div class="col-md-6 my-5">
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
                    </div>

                    {{-- production_product_add --}}
                    <div class="col-md-12 card shadow px-5">
                        <table id="production_product_add" class="table table-bordered mt-4">
                            <tr>
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

                    <!-- add_to_Consumable Products Content -->
                    <div class="col-md-6 my-5">
                        <div class="row me-0 me-md-2">
                            <div class="col-md-12 card shadow px-5 d-none" id="add_to_consume_product_div">
                                <div class="d-sm-flex align-items-center justify-content-between py-5">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('Product to be Consumed') }}</h1>
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
                                    <tbody id="add_to_consume_product_add">

                                    </tbody>
                                    <tfooter>
                                        <tr>
                                            <th colspan="5" class="fs-5 text-dark text-end">Total Price</th>
                                            <th id="add_to_consume_product_total_price"></th>
                                        </tr>
                                    </tfooter>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- add_to_Wastage Products Content -->
                    <div class="col-md-6 my-5">
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
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    {{-- <a class="btn btn-sm btn-success px-10 mt-5 me-5" href="{{ route('finalproductions.index') }}">Cancel</a> --}}
                    @can('create finalproductions')
                        <button type="submit" id="submitAllProceed"
                            class="btn btn-sm btn-primary px-10 mt-5">{{ __('All Save') }}</button>
                    @endcan
                </div>
            </form>
        </div>
        <!--end::Content wrapper-->
    </div>
</x-default-layout>

<script type="text/javascript">
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
    // $('#proceedProduct').on('click', function() {
    //     $("select#changeProduct").prop('disabled', true);
    //     $("input#productionUnitName").prop('disabled', true);
    //     $("input#productionQuantity").prop('disabled', true);

    // });


    //================ Production Details ===============   


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
                                    <td>` + product_id + `<input type="hidden" name="" value=` + product_id + ` ></td>
                                    <td>` + product_name + `<input type="hidden" name="consume_product_id[]" value=` +
                                    product_id + ` ></td>
                                    <td>` + product_unit_name +
                                    `<input type="hidden" name="consume_product_unit[]" value=` +
                                    product_unit_id + ` ></td>
                                    <td>` + product_quantity +
                                    `<input type="hidden" name="consume_product_quantity[]" value=` +
                                    product_quantity + ` ></td>

                                    <td>` + product_per_unit_price +
                                    `<input type="hidden" name="consume_unit_price[]" value=` +
                                    product_per_unit_price + ` ></td>
                                    <td>` + product_total_price +
                                    `<input type="hidden" name="consume_total_price[]" value=` +
                                    product_total_price + ` ></td>
                                </tr>`
                                );
                                ++consume_id;
                            } else {
                                wastage_product_total_price = wastage_product_total_price +
                                    product_total_price;
                                $('#wastage_product_total_price').text(
                                    wastage_product_total_price);

                                $('#wastage_product_add').append(
                                    `<tr data-id2="${wastage_id}">
                                    <td>` + product_id + `<input type="hidden" name="" value=` + product_id + ` ></td>
                                    <td>` + product_name + `<input type="hidden" name="wastage_product_id[]" value=` +
                                    product_id + ` ></td>
                                    <td>` + product_unit_name +
                                    `<input type="hidden" name="wastage_product_unit[]" value=` +
                                    product_unit_id + ` ></td>
                                    <td>` + product_quantity +
                                    `<input type="hidden" name="wastage_product_quantity[]" value=` +
                                    product_quantity + ` ></td>

                                    <td>` + product_per_unit_price +
                                    `<input type="hidden" name="wastage_unit_price[]" value=` +
                                    product_per_unit_price + ` ></td>
                                    <td>` + product_total_price +
                                    `<input type="hidden" name="wastage_total_price[]" value=` +
                                    product_total_price + ` ></td>
                                </tr>`
                                );
                                ++wastage_id;
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

    var OnchangeProductCall = function() {

        var productID = $('#changeProduct').val();
        var quantity = $('#quantity').val();
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
                                    <td>` + product_id + `<input type="hidden" name="" value=` + product_id + ` ></td>
                                    <td>` + product_name + `<input type="hidden" name="consume_product_id[]" value=` +
                                    product_id + ` ></td>
                                    <td>` + product_unit_name +
                                    `<input type="hidden" name="consume_product_unit[]" value=` +
                                    product_unit_id + ` ></td>
                                    <td>` + product_quantity +
                                    `<input type="hidden" name="consume_product_quantity[]" value=` +
                                    product_quantity + ` ></td>

                                    <td>` + product_per_unit_price +
                                    `<input type="hidden" name="consume_unit_price[]" value=` +
                                    product_per_unit_price + ` ></td>
                                    <td>` + product_total_price +
                                    `<input type="hidden" name="consume_total_price[]" value=` +
                                    product_total_price + ` ></td>
                                </tr>`
                                );
                                ++consume_id;
                            } else {
                                wastage_product_total_price = wastage_product_total_price +
                                    product_total_price;
                                $('#wastage_product_total_price').text(
                                    wastage_product_total_price);

                                $('#wastage_product_add').append(
                                    `<tr data-id2="${wastage_id}">
                                    <td>` + product_id + `<input type="hidden" name="" value=` + product_id + ` ></td>
                                    <td>` + product_name + `<input type="hidden" name="wastage_product_id[]" value=` +
                                    product_id + ` ></td>
                                    <td>` + product_unit_name +
                                    `<input type="hidden" name="wastage_product_unit[]" value=` +
                                    product_unit_id + ` ></td>
                                    <td>` + product_quantity +
                                    `<input type="hidden" name="wastage_product_quantity[]" value=` +
                                    product_quantity + ` ></td>

                                    <td>` + product_per_unit_price +
                                    `<input type="hidden" name="wastage_unit_price[]" value=` +
                                    product_per_unit_price + ` ></td>
                                    <td>` + product_total_price +
                                    `<input type="hidden" name="wastage_total_price[]" value=` +
                                    product_total_price + ` ></td>
                                </tr>`
                                );
                                ++wastage_id;
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

            const selector = `tr[data-id="${id}"]`;
            $(selector).remove();

            OnchangeCall();

        }

        if (e.target.matches('.production_product_view')) {
            const {
                id
            } = e.target.dataset;

            const selector = `tr[data-id="${id}"]`;
            var add_to_product_id;
            var add_to_product_Qty;
            $(selector).each(function() {
                // Inside the iteration function, find specific input values within each table row
                add_to_product_id = $(this).find('input[name="add_to_product_id[]"]').val();
                add_to_product_Qty = $(this).find('input[name="add_to_quantity[]"]').val();

                // Now you can use or process the found values as needed
                console.log(`Product ID: ${add_to_product_id}, Quantity: ${add_to_product_Qty}`);
            });

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
                        `<tr data-id="${production_product_id_add}">
                        <td>` + add_to_product_id + `<input type="hidden" name="" value=` + add_to_product_id + ` ></td>
                        <td>` + add_to_product_name + `<input type="hidden" name="add_to_product_id[]" value=` +
                        add_to_product_id + ` ></td>
                        <td>` + add_to_unit_name + `<input type="hidden" name="add_to_unit_id[]" value=` +
                        add_to_unit_id + ` ></td>
                        <td>` + add_to_quantity +
                        `<input type="hidden" class="" style="width:80px" name="add_to_quantity[]" value=` +
                        add_to_quantity + ` ></td>
                        <td>` + add_to_unit_cost +
                        `<input type="hidden" class="" style="width:80px" name="add_to_unit_cost[]" value=` +
                        add_to_unit_cost + ` readonly></td>
                        <td>` + add_to_total_price +
                        `<input type="hidden" class="" style="width:80px" name="add_to_total_price[]" value=` +
                        add_to_total_price + ` readonly></td>
                        <td class="text-center my-0 d-flex"> 
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



    // $('#addProductionProduct').on('click', TableDataEtaret);

    $('#addProductionProduct').on('click', OnchangeCall);



    function OnchangeCall() {

        $('#add_to_consume_product_div').removeClass('d-none');
        $('#add_to_consume_product_add').empty();

        var productID;
        var quantity;

        $('#production_product_add tr').each(function() {
            productID = $(this).find('input[name="add_to_product_id[]"]').val();
            quantity = $(this).find('input[name="add_to_quantity[]"]').val();
            // console.log(productID, quantity);
            let consume_id = 0;
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

                            $.each(data2, function(key, value) {
                                // -------- Produceable Products get data ----------
                                var unit_id = value.production.unit_id;
                                var unit_name = data.unit.unit_name;
                                var Rqty = value.production.quantity;
                                // -------- Produceable Products Show data ---------
                                var produceable_qty = quantity ? quantity : Rqty;
                                $('#productionUnitId').val(unit_id);
                                $('#productionUnitName').val(unit_name);

                                // -------- Consumed and ByProduct/Wastage Products get data ---------
                                var product_id = value.product_id;
                                var product_name = value.product.product_name;
                                var product_unit_id = value.product_unit;
                                var product_unit_name = value.unit.unit_name;
                                var product_quantity = quantity ? (value.quantity *
                                    quantity) / Rqty : value.quantity;
                                var product_per_unit_price = value.product.purchase_price;
                                var product_total_price = product_per_unit_price *
                                    product_quantity;
                                var type = value.type;

                                if (type == 1) {
                                    consume_product_total_price =
                                        consume_product_total_price + product_total_price;
                                    var produceable_unit_price =
                                        consume_product_total_price / produceable_qty;
                                    $('#productionUnitCost').val(produceable_unit_price);
                                    $('#productionTotalPrice').val(
                                        consume_product_total_price);
                                    $('#add_to_consume_product_total_price').text(
                                        consume_product_total_price);


                                     var CheckProduct=true;

                                    $('#add_to_consume_product_add tr').each(function() {
                                        if ($(this).find(
                                                'input[name="consume_product_id[]"]'
                                            )
                                            .val() === product_id) {

                                                CheckProduct=false;
                                                return false;
                                        } 
                                    });
                                    

                                    if(CheckProduct){
                                        $('#add_to_consume_product_add').append(
                                                `<tr data-id="${consume_id}">
                                                <td>` + product_id + `<input type="hidden" name="" value=` +
                                                product_id + ` ></td>
                                                <td>` + product_name + `<input type="hidden" name="consume_product_id[]" value=` +
                                                product_id + ` ></td>
                                                <td>` + product_unit_name +
                                                `<input type="hidden" name="consume_product_unit[]" value=` +
                                                product_unit_id + ` ></td>
                                                <td>` + product_quantity +
                                                `<input type="hidden" name="consume_product_quantity[]" value=` +
                                                product_quantity + ` ></td>

                                                <td>` + product_per_unit_price +
                                                `<input type="hidden" name="consume_unit_price[]" value=` +
                                                product_per_unit_price + ` ></td>
                                                <td>` + product_total_price +
                                                `<input type="hidden" name="consume_total_price[]" value=` +
                                                product_total_price + ` ></td>
                                                </tr>`
                                            );
                                            ++consume_id;
                                    }
                                }
                            });

                        }
                    }
                });
            }

            // $('#add_to_consume_product_add').val('');
            // $('#add_to_wastage_product_add').val('');
        });
    }
</script>
