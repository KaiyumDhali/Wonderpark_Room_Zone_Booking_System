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
            <form id="" method="POST" action="{{ route('finalproductions.store') }}" enctype="multipart/form-data">
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
                                                <h5 class="mb-0 text-gray-800 pt-7">{{ __('Produceable Products:') }}</h5>
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
                                                <select id="changeProduct" class="form-control form-control-sm" name="production_product_id" required>
                                                    <option selected disabled>Select Product</option>
                                                    @foreach($produceable_products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->product_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="order">{{ __('Product Unit') }}</label>
                                                <input type="text" class="form-control form-control-sm form-control-solid" readonly id="productUnit" name="">
                                                <input type="hidden" class="form-control form-control-sm form-control-solid" readonly value="" id="unit_id" name="production_product_unit_id">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="order">{{ __('Quantity') }}</label>
                                                <input type="number" class="form-control form-control-sm form-control-solid" value="" id="quantity" name="production_product_quantity" >
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="order">{{ __('Per Unit Price') }}</label>
                                                <input type="number" class="form-control form-control-sm form-control-solid" readonly value="" id="unit_price" name="production_product_unit_price" step="1" pattern="^[-\d]\d*$">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="order">{{ __('Total Price') }}</label>
                                                <input type="number" class="form-control form-control-sm form-control-solid" readonly value="" id="produceable_total_price" name="production_product_total_price" step="1" pattern="^[-\d]\d*$">
                                            </div>
                                            
                                            <!-- <div class="form-group col-md-3">
                                                <button type="button" id="proceedProduct" class="btn btn-sm btn-primary px-5 mt-6">{{ __('Proceed') }}</button>
                                            </div> -->
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
                                            <th>Per Unit Price</th>
                                            <th>Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody id="consume_product_add">
                                        
                                    </tbody>
                                    <tfooter >
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
                                            <th>Per Unit Price</th>
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
                </div>
                <div class="d-flex justify-content-end">
                    {{-- <a class="btn btn-sm btn-success px-10 mt-5 me-5" href="{{ route('finalproductions.index') }}">Cancel</a> --}}
                    @can('create finalproductions')
                    <button type="submit" id="submitAllProceed" class="btn btn-sm btn-primary px-10 mt-5">{{ __('All Save') }}</button>
                    @endcan
                </div>
            </form>
        </div>
        <!--end::Content wrapper-->
    </div>
</x-default-layout>

<script type="text/javascript">
    // {
    //     const intRx = /\d/,
    //     integerChange = (event) => {
    //         console.log(event.key, )
    //         if (
    //         (event.key.length > 1) ||
    //         event.ctrlKey ||
    //         ( (event.key === "-") && (!event.currentTarget.value.length) ) ||
    //         intRx.test(event.key)
    //         ) return;
    //         event.preventDefault();
    //     };

    //     for (let input of document.querySelectorAll(
    //     'input[type="number"][step="1"]'
    //     )) input.addEventListener("keydown", integerChange);
    // }

    $('#submitAllProceed').on('click', function() {
        $("select#changeProduct").prop('disabled', false );
        $("input#ProductUnit").prop('disabled', false );
        $("input#quantity").prop('disabled', false );
       
    });
    // $('#proceedProduct').on('click', function() {
    //     $("select#changeProduct").prop('disabled', true);
    //     $("input#ProductUnit").prop('disabled', true);
    //     $("input#quantity").prop('disabled', true);
       
    // });
        


function OnchangeCall() {
        var productID =  $('#changeProduct').val();
        var quantity =  $('#quantity').val();
        console.log("Qty",quantity);
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

                    // Do something with the data
                    // console.log('Data 1:', data);
                    // console.log('Data 2:', data2);

                    var consume_product_total_price=0;
                    var wastage_product_total_price=0;
                    if (data2) {
                        $('#consume_product_div').removeClass('d-none');
                        $('#consume_product_add').empty();
                        $('#wastage_product_div').removeClass('d-none');
                        $('#wastage_product_add').empty();

                        $.each(data2, function(key, value) {
                            
                          var Rqty=value.production.quantity;
                           var unit_id = value.production.unit_id;
                            var unit_name = data.unit.unit_name;
                            // var unit_price = data.purchase_price;
                            // var produceable_total_price = unit_price*quantity;
                           
                            $('#unit_id').val(unit_id);
                            $('#productUnit').val(unit_name);

                            var product_id = value.product_id;
                            var product_name = value.product.product_name;
                            var product_unit_id = value.product_unit;
                            var product_unit_name = value.unit.unit_name;
                            var product_quantity = quantity? (value.quantity*quantity)/Rqty :value.quantity;
                            var new_product_quantity = 0;
                          
                            // $('#quantity').on('change', function() {
                            //     var change_product_quantity = $(this).val();
                            //     var per_product_quantity = product_quantity/quantity;
                            //     new_product_quantity = per_product_quantity*change_product_quantity;
                            //     console.log('new_product_quantity', new_product_quantity);

                            // });
                            // var final_product_quantity = new_product_quantity ? new_product_quantity : product_quantity;

                            var final_product_quantity =product_quantity;
                            console.log('final_product_quantity', new_product_quantity);
                            var product_per_unit_price = value.product.purchase_price;
                            var product_total_price = product_per_unit_price*final_product_quantity;
                            var type = value.type;

                            if(type == 1){
                                consume_product_total_price = consume_product_total_price + product_total_price;
                                var produceable_unit_price = (consume_product_total_price/quantity);
                                $('#consume_product_total_price').text(consume_product_total_price);
                                $('#produceable_total_price').val(consume_product_total_price);
                                $('#unit_price').val(produceable_unit_price);

                                $('#consume_product_add').append(
                                `<tr data-id="${consume_id}">
                                    <td>` + product_id + `<input type="hidden" name="" value=`+ product_id +` ></td>
                                    <td>` + product_name + `<input type="hidden" name="consume_product_id[]" value=`+ product_id +` ></td>
                                    <td>` + product_unit_name + `<input type="hidden" name="consume_product_unit[]" value=`+ product_unit_id +` ></td>
                                    <td>` + final_product_quantity + `<input type="hidden" name="consume_product_quantity[]" value=`+ final_product_quantity +` ></td>

                                    <td>` + product_per_unit_price + `<input type="hidden" name="consume_unit_price[]" value=`+ product_per_unit_price +` ></td>
                                    <td>` + product_total_price + `<input type="hidden" name="consume_total_price[]" value=`+ product_total_price +` ></td>
                                </tr>`
                            );
                            ++consume_id;
                            }else{
                                wastage_product_total_price = wastage_product_total_price + product_total_price;
                                $('#wastage_product_total_price').text(wastage_product_total_price);

                                $('#wastage_product_add').append(
                                `<tr data-id2="${wastage_id}">
                                    <td>` + product_id + `<input type="hidden" name="" value=`+ product_id +` ></td>
                                    <td>` + product_name + `<input type="hidden" name="wastage_product_id[]" value=`+ product_id +` ></td>
                                    <td>` + product_unit_name + `<input type="hidden" name="wastage_product_unit[]" value=`+ product_unit_id +` ></td>
                                    <td>` + final_product_quantity + `<input type="hidden" name="wastage_product_quantity[]" value=`+ final_product_quantity +` ></td>

                                    <td>` + product_per_unit_price + `<input type="hidden" name="wastage_unit_price[]" value=`+ product_per_unit_price +` ></td>
                                    <td>` + product_total_price + `<input type="hidden" name="wastage_total_price[]" value=`+ product_total_price +` ></td>
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
        // $('#quantity').val('');
        // $('#consume_product_add').val('');
        // $('#wastage_product_add').val('');
    }

//================ Production Details ===============   
    $('#changeProduct').on('change', OnchangeCall);
    $('#quantity').on('change', OnchangeCall);
                
</script>