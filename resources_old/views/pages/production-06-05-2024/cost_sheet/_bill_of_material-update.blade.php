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
                <div class="row p-10">
                    <div class="col-md-6">
                        <div class="row me-0 me-md-2">
                            <div class="col-md-12 card shadow">
                                <div class="card-body py-3">
                                    <div class="col-md-12 pb-3">
                                    <form id="" method="POST" action="{{ route('billofmaterials.store') }}" enctype="multipart/form-data">
                                            @csrf
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <h5 class="mb-0 text-gray-800 pt-7">{{ __('Product to be Consumed:') }}</h5>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>{{ __(' Product') }}</label>
                                                <input type="hidden" class="form-control form-control-sm form-control-solid" name="production_id" value="{{ $production->id }}" readonly/>
                                                <input type="hidden" class="form-control form-control-sm form-control-solid" name="type" value="1" readonly/>
                                                <select id="changeConsumeProduct" class="form-control form-control-sm" name="product_id" required>
                                                    <option selected disabled>Select Product</option>
                                                    @foreach($consumable_products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->product_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="order">{{ __('Unit') }}</label>
                                                <input type="text" class="form-control form-control-sm form-control-solid"  id="consumeProductUnit" name="" readonly/>
                                                <input type="hidden" class="form-control form-control-sm form-control-solid" name="product_unit" id="consumeProductUnitId" readonly/>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="order">{{ __('Quantity') }}</label>
                                                <input type="number" class="form-control form-control-sm" name="quantity" step="1" pattern="^[-\d]\d*$">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <button type="submit" class="btn btn-sm btn-success px-5 mt-6">{{ __('Add New') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <!-- Wastage Products Content -->
                    <div class="col-md-6">
                        <div class="row ms-0 ms-md-2">
                            <div class="col-md-12 card shadow">
                                <div class="card-body py-3">
                                    <div class="col-md-12 pb-3">
                                    <form id="" method="POST" action="{{ route('billofmaterials.store') }}" enctype="multipart/form-data">
                                            @csrf
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <h5 class="mb-0 text-gray-800 pt-7">{{ __('By Product / Wastage Product:') }}</h5>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>{{ __(' Product') }}</label>
                                                <input type="hidden" class="form-control form-control-sm form-control-solid" name="production_id" value="{{ $production->id }}" readonly/>
                                                <input type="hidden" class="form-control form-control-sm form-control-solid" name="type" value="2" readonly/>
                                                <select id="changeWastageProduct" class="form-control form-control-sm" name="product_id" required>
                                                    <option selected disabled>Select Product</option>
                                                    @foreach($wastage_products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->product_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="order">{{ __('Unit') }}</label>
                                                <input type="text" class="form-control form-control-sm form-control-solid"  id="wastageProductUnit" name="" readonly/>
                                                <input type="hidden" class="form-control form-control-sm form-control-solid" name="product_unit" id="wastageProductUnitId" readonly/>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="order">{{ __('Quantity') }}</label>
                                                <input type="number" class="form-control form-control-sm" name="quantity" step="1" pattern="^[-\d]\d*$">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <button type="submit" class="btn btn-sm btn-success px-5 mt-6">{{ __('Add New') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            <form id="" method="POST" action="{{ route('billofmaterials.update', $production->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" class="form-control form-control-sm form-control-solid" name="production_id" value="{{ $production->id }}" readonly/>
                <div class="row px-10">
                    <div class="col-md-6">
                        <div class="row me-0 me-md-2">
                            <div class="col-md-12 card shadow px-5">
                                <div class="card-header">
                                    <div class="d-sm-flex align-items-center justify-content-between">
                                        <h1 class="h3 mb-0 text-gray-800">{{ __('Product to be Consumed') }}</h1>
                                    </div>
                                </div>
                                <table id="consume_product_add" class="table table-bordered mt-4">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px !importent;"></th>
                                            <th>Product ID</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($consumeDetails as $consumeDetail)
                                    <tr>
                                        <input type="hidden" name="id[]" value="{{ $consumeDetail->id }}">
                                        <td class="text-center my-0"> <a class="btn btn-sm btn-danger py-0" onclick="return confirm('Are you sure you want to delete ?')" href="{{route('productDetailsDestroy.file_destroy', $consumeDetail->id) }}"> Delete</a></td>
                                        <td>{{ $consumeDetail->product->id }}</td>
                                        <td>{{ $consumeDetail->product->product_name }}</td>
                                        <td><input type="number" class="" style="width:80px" name="quantity[]" value="{{ $consumeDetail->quantity }}"></td>
                                        <td>{{ $consumeDetail->unit->unit_name }}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Wastage Products Content -->
                    <div class="col-md-6">
                        <div class="row ms-0 ms-md-2">
                            <div class="col-md-12 card shadow px-5">
                                <div class="card-header">
                                    <div class="d-sm-flex align-items-center justify-content-between">
                                        <h1 class="h3 mb-0 text-gray-800">{{ __('By Product / Wastage Product:') }}</h1>
                                    </div>
                                </div>
                                <table id="wastage_product_add" class="table table-bordered mt-4">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px !importent;"></th>
                                            <th>Product ID</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($wastageDetails as $wastageDetail)
                                    <tr>
                                        <input type="hidden" name="id[]" value="{{ $wastageDetail->id }}">
                                        <td class="text-center my-0"> <a class="btn btn-sm btn-danger py-0" onclick="return confirm('Are you sure you want to delete ?')" href="{{route('productDetailsDestroy.file_destroy', $wastageDetail->id) }}"> Delete</a></td>
                                        <td>{{ $wastageDetail->product->id }}</td>
                                        <td>{{ $wastageDetail->product->product_name }}</td>
                                        <td><input type="number" class="" style="width:80px" name="quantity[]" value="{{ $wastageDetail->quantity }}"></td>
                                        <td>{{ $wastageDetail->unit->unit_name }}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end px-5 mt-10">
                    <a class="btn btn-sm btn-success px-10 mt-5 me-5" href="{{ route('billofmaterials.index') }}">Back</a>
                    <button type="submit" id="submitAllProceed" class="btn btn-sm btn-primary px-10 mt-5">{{ __('All Update') }}</button>
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
    //========================consume_product_add====================
    $('#changeConsumeProduct').on('change', function() {
        var productID = $(this).val();
        var url = '{{route("productDetails",':productID')}}';
        url = url.replace(':productID', productID);
        if (productID) {
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
    $('#changeWastageProduct').on('change', function() {
        var productID = $(this).val();
        var url = '{{route("productDetails",':productID')}}';
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