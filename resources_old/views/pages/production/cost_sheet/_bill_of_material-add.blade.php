<x-default-layout>
    <div class="container-fluid">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
            <form id="" method="POST" action="{{ route('billofmaterials.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row p-10">
                    <!-- Produceable Products Content -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 card shadow">
                                <div class="card-header">
                                    <div class="d-sm-flex align-items-center justify-content-between">
                                        <h1 class="h3 mb-0 text-gray-800">{{ __('Bill Of Materials Pannel') }}</h1>
                                    </div>
                                    <label class="badge text-warning" id="jsdataerror"></label>
                                </div>
                                <div class="card-body py-3">
                                    <div class="col-md-12 pb-3">
                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <h5 class="mb-0 text-gray-800 pt-7">{{ __('Produceable Products:') }}</h5>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>{{ __('Product') }}</label>
                                                <select id="changeProduct" class="form-control form-control-sm" name="produced_product_id" required>
                                                    <option selected disabled>Select Product</option>
                                                    @foreach($produceable_products as $product)
                                                    @if($product->productions->isEmpty())
                                                    <option value="{{ $product->id }}">{{ $product->product_name}}</option>
                                                    @else
                                                    <option disabled value="{{ $product->id }}" title="Product already in production">{{ $product->product_name}}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="order">{{ __('Unit') }}</label>
                                                <input type="text" class="form-control form-control-sm" id="ProductUnit"
                                                       name="">
                                                <input type="hidden" class="form-control form-control-sm" id="ProductUnitId"
                                                       name="produced_unit_id">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="order">{{ __('Quantity') }}</label>
                                                <input type="number" class="form-control form-control-sm" id="quantity" name="produced_quantity" step="1" pattern="^[-\d]\d*$" required>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <button type="button" id="proceedProduct" class="btn btn-sm btn-primary px-5 mt-6">{{ __('Proceed') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Consumable Products Content -->
                    <div class="col-md-6">
                        <div class="row me-0 me-md-2">
                            <div class="col-md-12 card shadow my-5">
                                <!-- <div class="card-header">
                                    <div class="d-sm-flex align-items-center justify-content-between">
                                        <h1 class="h3 mb-0 text-gray-800">{{ __('Product to be Consumed') }}</h1>
                                    </div>
                                    <label class="badge text-warning" id="jsdataerror"></label>
                                </div> -->
                                <div class="card-body py-3">
                                    <div class="col-md-12 pb-3">
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <h5 class="mb-0 text-gray-800 pt-7">{{ __('Product to be Consumed:') }}</h5>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>{{ __(' Product') }}</label>
                                                <select id="changeConsumeProduct" class="form-control form-control-sm" name="" required>
                                                    <option selected disabled>Select Product</option>
                                                    @foreach($consumable_products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->product_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="order">{{ __('Quantity') }}</label>
                                                <input type="number" class="form-control form-control-sm" id="consumeQuantity" name="" step="1" pattern="^[-\d]\d*$">
                                            </div>
                                            <input type="hidden" class="form-control form-control-sm form-control-solid" readonly id="consumeProductUnit"
                                                   name="" />
                                            <input type="hidden" class="form-control form-control-sm form-control-solid" readonly id="consumeProductUnitId"
                                                   name="" />
                                            <div class="form-group col-md-3">
                                                <button type="button" id="addConsumeProduct" class="btn btn-sm btn-primary px-5 mt-6">{{ __('Add to Cart') }}</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 card shadow px-5">
                                <table id="consume_product_add" class="table table-bordered mt-4">
                                    <tr>
                                        <th style="width: 50px;"></th>
                                        <th>Product ID</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Wastage Products Content -->
                    <div class="col-md-6">
                        <div class="row ms-0 ms-md-2">
                            <div class="col-md-12 card shadow my-5">
                                <!-- <div class="card-header">
                                    <div class="d-sm-flex align-items-center justify-content-between">
                                        <h1 class="h3 mb-0 text-gray-800">{{ __('By Product / Wastage Product') }}</h1>
                                    </div>
                                    <label class="badge text-warning" id="jsdataerror"></label>
                                </div> -->
                                <div class="card-body py-3">
                                    <div class="col-md-12 pb-3">
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <h5 class="mb-0 text-gray-800 pt-7">{{ __('By Product / Wastage Product:') }}</h5>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>{{ __(' Product') }}</label>
                                                <select id="changeWastageProduct" class="form-control form-control-sm" name="" required>
                                                    <option selected disabled>Select Product</option>
                                                    @foreach($wastage_products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->product_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="order">{{ __('Quantity') }}</label>
                                                <input type="number" class="form-control form-control-sm" id="wastageQuantity" name="" step="1" pattern="^[-\d]\d*$">
                                            </div>
                                            <input type="hidden" class="form-control form-control-sm form-control-solid" readonly id="wastageProductUnit"
                                                   name="" />
                                            <input type="hidden" class="form-control form-control-sm form-control-solid" readonly id="wastageProductUnitId"
                                                   name="" />
                                            <div class="form-group col-md-3">
                                                <button type="button" id="addWastageProduct" class="btn btn-sm btn-primary px-5 mt-6">{{ __('Add to Cart') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 card shadow px-5">
                                <table id="wastage_product_add" class="table table-bordered mt-4">
                                    <tr>
                                        <th style="width: 50px !importent;"></th>
                                        <th>Product ID</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end px-5">
                    <a class="btn btn-sm btn-success px-10 mt-5 me-5" href="{{ route('billofmaterials.index') }}">Back</a>
                    <button type="submit" id="submitAllProceed" class="btn btn-sm btn-primary px-10 mt-5">{{ __('All Save') }}</button>
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

    $('#submitAllProceed').on('click', function() {
        $("select#changeProduct").prop('disabled', false );
        $("input#ProductUnit").prop('disabled', false );
        $("input#quantity").prop('disabled', false );
       
    });
    $('#proceedProduct').on('click', function() {
        let change_product_id = $('#changeProduct option:selected').val();
        let change_product_quantity = $('#quantity').val();

        if(change_product_id > 0){
            if(change_product_quantity > 0){
                $("select#changeProduct").prop('disabled', true);
                $("input#ProductUnit").prop('disabled', true);
                $("input#quantity").prop('disabled', true);
            }else {
                alert("Please Input Quantity Number");
            }
        }else {
            alert("Please Select Product");
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
                        $.each(data, function(key, value) {
                            var unit = value.unit.unit_name;
                            var unit_id = value.unit.id;
                            $('#ProductUnit').val(unit);
                            $('#ProductUnitId').val(unit_id);

                        });
                    } else {

                    }
                }
            });
        } else {

        }
    });
    //========================consume_product_add====================
    $('#consume_product_add').on('click', function(e) {
            if(e.target.matches('.consume_delete')) {
                const { id } = e.target.dataset;
                const selector = `tr[data-id="${id}"]`;
                $(selector).remove();
            }
        });
            
    let consume_id = 0;
    $('#addConsumeProduct').on('click', function() {
        let consume_product_name = $('#changeConsumeProduct option:selected').text();
        let consume_product_id = $('#changeConsumeProduct option:selected').val();
        let consume_quantity = $('#consumeQuantity').val();
        let consume_unit = $('#consumeProductUnit').val();
        let consume_unit_id = $('#consumeProductUnitId').val();
        
        if(consume_product_id > 0){
            if (consume_quantity > 0 ) {
            // Check for duplicate consume_product_id
            let duplicate_found = false;
            $('#consume_product_add tr').each(function() {
                if ($(this).find('input[name="consume_product_id[]"]').val() === consume_product_id) {
                    duplicate_found = true;
                    return false; // break out of loop if duplicate found
                }
            });
            if (duplicate_found) {
                alert('This product has already been added to the list.');
            } else {
                $('#consume_product_add').append(
                    `<tr data-id="${consume_id}">
                        <td class="text-center my-0"> <button data-id="${consume_id}" type="button" class="consume_delete btn btn-sm btn-danger py-0" >Delete</button></td>
                        <td>` + consume_product_id + `<input type="hidden" name="" value=`+ consume_product_id +` ></td>
                        <td>` + consume_product_name + `<input type="hidden" name="consume_product_id[]" value=`+ consume_product_id +` ></td>
                        <td><input type="number" class="" style="width:80px" name="consume_product_quantity[]" value=`+ consume_quantity +` ></td>
                        <td>` + consume_unit + `<input type="hidden" name="consume_product_unit[]" value=`+ consume_unit_id +` ></td>
                    </tr>`
                );
                ++consume_id;

                $('#consumeQuantity').val('');
                $('#consumeProductUnit').val('');
                $('#consumeProductUnitId').val('');
            }
            } else {
                alert("Please Input Consume Quantity Number");
            }
        }else {
            alert("Please Select Product");
        }
    });

    $('#changeConsumeProduct').on('change', function() {
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
                        $.each(data, function(key, value) {
                            var unit = value.unit.unit_name;
                            var unit_id = value.unit.id;
                            $('#consumeProductUnit').val(unit);
                            $('#consumeProductUnitId').val(unit_id);
                            
                        });
                    } else {

                    }
                }
            });
        } else {

        }
    });
    //========================wastage_product_add====================
    $('#wastage_product_add').on('click', function(e) {
            if(e.target.matches('.wastage_delete')) {
                const { id2 } = e.target.dataset;
                const selector = `tr[data-id2="${id2}"]`;
                $(selector).remove();
            }
        });
                    
    let wastage_id = 0;
    $('#addWastageProduct').on('click', function() {
        let wastage_product_name = $('#changeWastageProduct option:selected').text();
        let wastage_product_id = $('#changeWastageProduct option:selected').val();
        let wastage_quantity = $('#wastageQuantity').val();
        let wastage_unit = $('#wastageProductUnit').val();
        let wastage_unit_id = $('#wastageProductUnitId').val();
  
        if(wastage_product_id > 0){
            if (wastage_quantity > 0) {
                // Check for duplicate wastage_product_id
                let duplicate_found = false;
                $('#wastage_product_add tr').each(function() {
                    if ($(this).find('input[name="wastage_product_id[]"]').val() === wastage_product_id) {
                        duplicate_found = true;
                        return false; // break out of loop if duplicate found
                    }
                });
                if (duplicate_found) {
                    alert('This product has already been added to the list.');
                } else {
                    $('#wastage_product_add').append(
                    `<tr data-id2="${wastage_id}">
                        <td class="text-center my-0"> <button data-id2="${wastage_id}" type="button" class="wastage_delete btn btn-sm btn-danger py-0" >Delete</button></td>
                        <td>` + wastage_product_id + `<input type="hidden" name="" value=`+ wastage_product_id +` ></td>
                        <td>` + wastage_product_name + `<input type="hidden" name="wastage_product_id[]" value=`+ wastage_product_id +` ></td>
                        <td><input type="number" class="" style="width:80px" name="wastage_product_quantity[]" value=`+ wastage_quantity +` ></td>
                        <td>` + wastage_unit + `<input type="hidden" name="wastage_product_unit[]" value=`+ wastage_unit_id +` ></td>
                    </tr>`
                    );
                    ++wastage_id;

                    $('#wastageQuantity').val('');
                    $('#wastageProductUnit').val('');
                    $('#wastageProductUnitId').val('');
                }
            } else {
                alert("Please Input Wastage Quantity Number");
            }
        }else {
            alert("Please Select Product");
        }
    });
        
    $('#changeWastageProduct').on('change', function() {
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
                        $.each(data, function(key, value) {
                            var unit = value.unit.unit_name;
                            var unit_id = value.unit.id;
                            $('#wastageProductUnit').val(unit);
                            $('#wastageProductUnitId').val(unit_id);

                        });
                    } else {

                    }
                }
            });
        } else {

        }
    });   
        
        
</script>