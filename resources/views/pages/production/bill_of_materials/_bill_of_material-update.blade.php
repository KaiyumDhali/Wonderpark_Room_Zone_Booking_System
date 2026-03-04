<x-default-layout>
    {{-- <div class="col-xl-12 px-5">
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
    </div> --}}
    <div class="container-fluid">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <div class="row p-10">
                <!--  Add Requisition Items: Products Content -->
                <div class="col-md-6">
                    <div class="row me-0 me-md-2">
                        <div class="col-md-12 card shadow">
                            <div class="card-body py-3 px-3">
                                <div class="col-md-12 pb-3">
                                    <form id="" method="POST" action="{{ route('billofmaterials.store') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <h5 class="mb-0 text-gray-800 pt-7">{{ __('Add Requisition Items:') }}
                                                </h5>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>{{ __('Material Product') }}</label>
                                                <input type="hidden"
                                                    class="form-control form-control-sm form-control-solid"
                                                    name="production_id" value="{{ $production->id }}" readonly />
                                                <input type="hidden"
                                                    class="form-control form-control-sm form-control-solid"
                                                    name="type" value="1" readonly />
                                                <select class="form-select form-select-sm" data-control="select2"
                                                id="changeConsumeProduct"  name="product_id" required>
                                                    <option selected disabled>Select Material Product</option>
                                                    @foreach ($consumable_products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->product_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="order">{{ __('Unit') }}</label>
                                                <input type="text"
                                                    class="form-control form-control-sm form-control-solid"
                                                    id="consumeProductUnit" name="" readonly />
                                                <input type="hidden"
                                                    class="form-control form-control-sm form-control-solid"
                                                    name="product_unit" id="consumeProductUnitId" readonly />
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="order">{{ __('Quantity') }}</label>
                                                {{-- <input type="number" class="form-control form-control-sm" name="quantity" step="1" pattern="^[-\d]\d*$"> --}}
                                                <input type="text" class="form-control form-control-sm"
                                                    name="quantity"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    required>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <button type="submit"
                                                    class="btn btn-sm btn-success px-5 mt-6">{{ __('Add New') }}</button>
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
                            <div class="card-body py-3 px-3">
                                <div class="col-md-12 pb-3">
                                    <form id="" method="POST" action="{{ route('billofmaterials.store') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <h5 class="mb-0 text-gray-800 pt-7">
                                                    {{ __('By Product / Wastage Product:') }}</h5>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>{{ __('Material Product') }}</label>
                                                <input type="hidden"
                                                    class="form-control form-control-sm form-control-solid"
                                                    name="production_id" value="{{ $production->id }}" readonly />
                                                <input type="hidden"
                                                    class="form-control form-control-sm form-control-solid"
                                                    name="type" value="2" readonly />
                                                <select class="form-select form-select-sm" data-control="select2"
                                                id="changeWastageProduct" name="product_id" required>
                                                    <option selected disabled>Select Material Product</option>
                                                    @foreach ($wastage_products as $product)
                                                        <option value="{{ $product->id }}">
                                                            {{ $product->product_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="order">{{ __('Unit') }}</label>
                                                <input type="text"
                                                    class="form-control form-control-sm form-control-solid"
                                                    id="wastageProductUnit" name="" readonly />
                                                <input type="hidden"
                                                    class="form-control form-control-sm form-control-solid"
                                                    name="product_unit" id="wastageProductUnitId" readonly />
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="order">{{ __('Quantity') }}</label>
                                                {{-- <input type="number" class="form-control form-control-sm" name="quantity" step="1" pattern="^[-\d]\d*$"> --}}
                                                <input type="text" class="form-control form-control-sm"
                                                    name="quantity"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    required>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <button type="submit"
                                                    class="btn btn-sm btn-success px-5 mt-6">{{ __('Add New') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  All Requisition Items: form Content -->
            <form id="billofMaterialsUpdate" method="POST" action="{{ route('billofmaterials.update', $production->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" class="form-control form-control-sm form-control-solid" name="production_id"
                    value="{{ $production->id }}" readonly />
                <div class="row px-10">
                    <!-- Consume Requisition Items: form Content -->
                    <div class="col-md-6">
                        <div class="row me-0 me-md-2">
                            <div class="col-md-12 card shadow px-5">
                                <div class="card-header px-0">
                                    <div class="d-sm-flex align-items-center justify-content-between px-0">
                                        <h1 class="h3 mb-0 text-gray-800 px-0">{{ __('Requisition Items List:') }}
                                        </h1>
                                    </div>
                                </div>
                                <table id="consume_product_add" class="table table-bordered mt-4">
                                    <thead>
                                        <tr class="text-center">
                                            {{-- <th style="width:60px">SL</th> --}}
                                            <th style="width:90px">Material ID</th>
                                            <th>Material Name</th>
                                            <th style="width:70px">Unit</th>
                                            <th style="width:90px">Quantity</th>
                                            <th style="width:70px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($consumeDetails as $consumeDetail)
                                            <tr>
                                                <input type="hidden" name="id[]"
                                                    value="{{ $consumeDetail->id }}">
                                                {{-- <td class="text-center">{{ $consumeDetail->product->id }}</td> --}}
                                                <td class="text-center">{{ $consumeDetail->product->id }}</td>
                                                <td>{{ $consumeDetail->product->product_name }}</td>
                                                <td class="text-center">{{ $consumeDetail->unit->unit_name }}</td>
                                                <td>
                                                    {{-- <input type="number" class="text-end" style="width:80px" name="quantity[]" value="{{ $consumeDetail->quantity }}"> --}}
                                                    <input type="text" class="form-control py-0 my-0 text-end"
                                                        style="width:80px" name="quantity[]" step="1"
                                                        min="0"
                                                        oninput="this.value = this.value.replace(/\D/g, '');"
                                                        value="{{ $consumeDetail->quantity }}">
                                                </td>
                                                <td class="text-center my-0">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger py-0 px-3 delete-consume-detail"
                                                        data-id="{{ $consumeDetail->id }}">
                                                        <span style="font-size: 16px">X</span>
                                                    </button>
                                                </td>
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
                                <div class="card-header px-0">
                                    <div class="d-sm-flex align-items-center justify-content-between px-0">
                                        <h1 class="h3 mb-0 text-gray-800">{{ __('By Product / Wastage Product:') }}
                                        </h1>
                                    </div>
                                </div>
                                <table id="wastage_product_add" class="table table-bordered mt-4">
                                    <thead>
                                        <tr class="text-center">
                                            {{-- <th style="width:60px">SL</th> --}}
                                            <th style="width:90px">Material ID</th>
                                            <th>Material Name</th>
                                            <th style="width:70px">Unit</th>
                                            <th style="width:90px">Quantity</th>
                                            <th style="width:70px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($wastageDetails as $wastageDetail)
                                            <tr>
                                                <input type="hidden" name="id[]"
                                                    value="{{ $wastageDetail->id }}">
                                                {{-- <td class="text-center">{{ $wastageDetail->product->id }}</td> --}}
                                                <td class="text-center">{{ $wastageDetail->product->id }}</td>
                                                <td class="text-center">{{ $wastageDetail->product->product_name }}
                                                </td>
                                                <td class="text-center">{{ $wastageDetail->unit->unit_name }}</td>
                                                <td>
                                                    {{-- <input type="number" class="" style="width:80px" name="quantity[]" value="{{ $wastageDetail->quantity }}"> --}}
                                                    <input type="text" class="form-control py-0 my-0 text-end"
                                                        style="width:80px" name="quantity[]" step="1"
                                                        min="0"
                                                        oninput="this.value = this.value.replace(/\D/g, '');"
                                                        value="{{ $wastageDetail->quantity }}">
                                                </td>
                                                <td class="text-center my-0">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger py-0 px-3 delete-wastage-detail"
                                                        data-id="{{ $wastageDetail->id }}">
                                                        <span style="font-size: 16px">X</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end px-5 mt-10">
                    <a class="btn btn-sm btn-success px-10 mt-5 me-5"
                        href="{{ route('billofmaterials.index') }}">Back</a>
                    <button type="submit" id="submitAllUpdate"
                        class="btn btn-sm btn-primary px-10 mt-5">{{ __('All Update') }}</button>
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
                    ((event.key === "-") && (!event.currentTarget.value.length)) ||
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
        var url = '{{ route('billOfMatrialProductDetails', ':id') }}';
        url = url.replace(':id', productID);
        console.log('url',url);
        if (productID) {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    console.log(data);
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

    //================ SweetAlert2 Confirmation  =============== 
    document.addEventListener('DOMContentLoaded', function() {
        // Attach event listener to all delete buttons
        document.querySelectorAll('.delete-consume-detail').forEach(button => {
            button.addEventListener('click', function() {
                const consumeDetailId = this.dataset.id;
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this record!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to delete route when confirmed
                        window.location.href =
                            `{{ route('productDetailsDestroy.file_destroy', ['id' => '_id_']) }}`
                            .replace('_id_', consumeDetailId);

                    }
                });
            });
        });
        document.querySelectorAll('.delete-wastage-detail').forEach(button => {
            button.addEventListener('click', function() {
                const wastageDetailId = this.dataset.id;
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this record!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to delete route when confirmed
                        window.location.href =
                            `{{ route('productDetailsDestroy.file_destroy', ['id' => '_id_']) }}`
                            .replace('_id_', wastageDetailId);

                    }
                });
            });
        });
    });
    // Confarm message after form on click ========= 
    document.getElementById("submitAllUpdate").addEventListener('click', function (event) { ConfarmMsg("billofMaterialsUpdate", "Are You sure.. Want to Update?", "update", event) });
    // Show message after form submission ========= 
    var msg = {!! json_encode(session('message')) !!};
    var type = {!! json_encode(session('alert-type')) !!};
    document.addEventListener('DOMContentLoaded', AlertCall(msg, type));
</script>
