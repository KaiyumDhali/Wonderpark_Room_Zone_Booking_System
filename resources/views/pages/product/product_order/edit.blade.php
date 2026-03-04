<x-default-layout>
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Update
                        Product Order</h1>
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container">
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form id="kt_ecommerce_add_category_form"
                    class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                    action="{{ route('product_order.update', $productOrder->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12 col-md-12 me-lg-10">
                            <div class="card card-flush py-4">
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">{{ __('Select Customer') }}</label>
                                                <select class="form-select form-select-sm" data-control="select2" name="customer_id">
                                                    <option value="">Select Customer</option>
                                                    @foreach ($allCustomers as $key => $value)
                                                        <option value="{{ $key }}" {{ $productOrder->customer_id==$key ? 'selected' : ''}}>{{ $value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Order Category</label>
                                                <select class="form-select form-select-sm" data-control="select2" name="order_category">
                                                    <option value="">Select Order Category</option>
                                                    <option value="Men" {{ $productOrder->order_category=='Men'?'selected':'' }}>Men</option>
                                                    <option value="Women" {{ $productOrder->order_category=='Women'?'selected':'' }}>Women</option>
                                                    <option value="Children" {{ $productOrder->order_category=='Children'?'selected':'' }}>Children</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Order Type</label>
                                                <select class="form-select form-select-sm" data-control="select2" name="order_type">
                                                    <option value="">Select Order Type</option>
                                                    <option value="Sports" {{ $productOrder->order_type=='Sports'?'selected':'' }}>Sports</option>
                                                    <option value="Shoes" {{ $productOrder->order_type=='Shoes'?'selected':'' }}>Shoes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class="required form-label">Order Number </label>
                                                <input type="text" name="order_number"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Order Number" value="{{$productOrder->order_number}}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="card-body pt-0 pb-2">
                                            <div class=" fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> Manufacturer Article No</label>
                                                <input type="text" name="manufacturer_article_no"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Manufacturer Article No" value="{{$productOrder->manufacturer_article_no}}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="card-body pt-0 pb-2">
                                            <div class=" fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Customer Article No</label>
                                                <input type="text" name="customer_article_no"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Customer Article No" value="{{$productOrder->customer_article_no}}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> Last No </label>
                                                <input type="text" name="last_no"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Last Size" value="{{$productOrder->last_no}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Description</label>
                                                <input type="text" name="description"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Description" value="{{$productOrder->description}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Remarks</label>
                                                <input type="text" name="remarks"
                                                    class="form-control form-control-sm mb-2" placeholder="Remarks"
                                                    value="{{$productOrder->remarks}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class="form-label">Order Date</label>
                                                <input type="date" name="order_date"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Order Date" value="{{date('Y-m-d', strtotime($productOrder->order_date)) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class="form-label">Delivery Date</label>
                                                <input type="date" name="delivery_date"
                                                    class="form-control form-control-sm mb-2"
                                                    placeholder="Order Date" value="{{ date('Y-m-d', strtotime($productOrder->delivery_date)) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class="form-label">Status</label>
                                                <select class="form-select form-select-sm" data-control="select2" name="status" >
                                                    <option value="">--Select Status--</option>
                                                    <option value="1" {{ $productOrder->status==1?'selected':'' }}>Active</option>
                                                    <option value="0" {{ $productOrder->status==0?'selected':'' }}>Disable</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12 col-md-12 me-lg-10 py-5 my-5">
                            <div class="card card-flush py-5">
                                <!--begin::Repeater-->
                                <div id="kt_docs_repeater_nested">
                                    
                                    <!--begin::Form group-->
                                    <div class="form-group">
                                        <div data-repeater-list="kt_docs_repeater_nested_outer">
                                            @foreach ($productOrderDetails as $productOrderDetail)
                                            <div data-repeater-item>
                                                <div class="form-group row mb-5">
                                                    
                                                    <div class="col-md-2">
                                                        <div class="product fv-row fv-plugins-icon-container mx-5">
                                                            
                                                            <label class="form-label fw-semibold fs-6 mb-2">Update Image</label>
                                                            <input type="file" class="product_image_input form-control form-control-sm" name="image" placeholder="Update Image" required>
                                                            <div class="image_filename"></div> <!-- Placeholder for displaying the filename -->
                                                            <br>
                                                            <img src="{{ Storage::url($productOrderDetail->image) }}"
                                                            height="80" width="100" alt="" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card-body pt-0 pb-2">
                                                            <div class="product fv-row fv-plugins-icon-container">
                                                                <label class="form-label required fw-semibold fs-6 mb-2">Color</label>
                                                                <input type="text" class="product_color_tagify form-control form-control-sm" name="color" value="{{ $productOrderDetail->color }}" id="product_color_tagify" placeholder="Select Color" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        @foreach ($productOrderDetailsChains as $productOrderDetailsChain)

                                                        <div class="inner-repeater">
                                                            <div data-repeater-list="kt_docs_repeater_nested_inner"
                                                                class="mb-5">
                                                                <div data-repeater-item>
                                                                    <div class="row">
                                                                        <div class="col-md-2">
                                                                            <label class=" form-label">Size</label>
                                                                            <div class="input-group pb-3">
                                                                                <select
                                                                                    class="form-select form-select-sm"
                                                                                    name="size_id[]">
                                                                                    <option selected
                                                                                        value="">Select Sizes
                                                                                    </option>
                                                                                    @foreach ($allSizes as $key => $value)
                                                                                        <option value="{{ $key }}" {{ $productOrderDetailsChain->size_id==$key ? 'selected' : ''}}>
                                                                                            {{ $value }}
                                                                                        </option>

                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label class="form-label">Qty</label>
                                                                            <div class="input-group pb-3">
                                                                                <input type="number"
                                                                                    name="quantity[]" id="quantity"
                                                                                    class="quantity form-control form-control-sm mb-2"
                                                                                    placeholder="Quantity"
                                                                                    value="{{ $productOrderDetailsChain->quantity }}" min="0">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label class="form-label">Unit
                                                                                Price</label>
                                                                            <div class="input-group pb-3">
                                                                                <input type="number"
                                                                                    name="unit_price[]"
                                                                                    id="unit_price"
                                                                                    class="unit_price form-control form-control-sm mb-2"
                                                                                    placeholder="Unit Price"
                                                                                    min="0"
                                                                                    value="{{ $productOrderDetailsChain->unit_price }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label class="form-label">Total
                                                                                Price</label>
                                                                            <div class="input-group pb-3">
                                                                                <input type="text"
                                                                                    name="total_price[]"
                                                                                    id="total_price"
                                                                                    class="total_price form-control form-control-sm mb-2"
                                                                                    placeholder="Total Price"
                                                                                    value="{{ $productOrderDetailsChain->total_price }}" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <label class=" form-label"></label>
                                                                            <div class="input-group pb-3">
                                                                                <button
                                                                                    class="border border-secondary btn btn-sm btn-icon btn-flex btn-light-danger mt-2"
                                                                                    data-repeater-delete
                                                                                    type="button">
                                                                                    <i
                                                                                        class="ki-duotone ki-trash fs-5"><span
                                                                                            class="path1"></span><span
                                                                                            class="path2"></span><span
                                                                                            class="path3"></span><span
                                                                                            class="path4"></span><span
                                                                                            class="path5"></span></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- <button class="btn btn-sm btn-flex btn-light-primary"
                                                                data-repeater-create type="button">
                                                                <i class="ki-duotone ki-plus fs-5"></i>
                                                                Add More
                                                            </button> --}}
                                                        </div>
                                                        @endforeach


                                                    </div>
                                                    <div class="col-md-1 px-5">
                                                        <a href="javascript:;" data-repeater-delete
                                                            class="btn btn-sm btn-flex btn-light-danger mt-3 mt-md-9 ">
                                                            <i class="ki-duotone ki-trash fs-5"><span
                                                                    class="path1"></span><span
                                                                    class="path2"></span><span
                                                                    class="path3"></span><span
                                                                    class="path4"></span><span
                                                                    class="path5"></span></i>
                                                             Row
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            
                                        </div>
                                        <!--begin::Form group-->
                                    <div class="form-group px-5">
                                        <a href="javascript:;" data-repeater-create
                                            class="btn btn-flex btn-light-primary">
                                            <i class="ki-duotone ki-plus fs-3"></i>
                                            Add Row
                                        </a>
                                    </div>
                                    <!--end::Form group-->
                                    </div>
                                    <!--end::Form group-->
                                    

                                    
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-12 me-lg-10">
                            <div class="d-flex justify-content-end my-5">
                                <a href="{{ route('product_order.index') }}" id="kt_ecommerce_add_product_cancel"
                                    class="btn btn-sm btn-success me-5">Cancel</a>
                                <button type="submit" id="kt_ecommerce_add_category_submit"
                                    class="btn btn-sm btn-primary">
                                    <span class="indicator-label">Order Create</span>
                                    <span class="indicator-progress">Please wait...
                                        <span
                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!--end::Main column-->
                </form>
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->

</div>
</x-default-layout>

<script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>

<script type="text/javascript">

    $('#kt_docs_repeater_nested').repeater({
        repeaters: [{
            selector: '.inner-repeater',
            show: function () {
                $(this).slideDown();
                initTagify(this);
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        }],

        show: function () {
            $(this).slideDown();
            initTagify(this);
        },

        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
    });

    // Function to calculate the total price
    function calculateTotalPrice(input) {
        var $parentRow = $(input).closest('.row');
        var quantity = parseFloat($parentRow.find('.quantity').val()) || 0; // Find quantity input within the same row
        var unit_price = parseFloat($parentRow.find('.unit_price').val()) || 0; // Find unit_price input within the same row

        var totalAmount = quantity * unit_price;

        $parentRow.find('.total_price').val(totalAmount.toFixed(2)); // Find total_price input within the same row
    }

    // Attach input event listeners to the document and delegate to dynamically created elements
    $(document).on('input', '.quantity', function() {
        calculateTotalPrice(this);
    });

    $(document).on('input', '.unit_price', function() {
        calculateTotalPrice(this);
    });

    function initTagify(context) {
        // Initialize Tagify for the color inputs within the context
        $('.product_color_tagify', context).each(function () {
            var $tagifyInput = $(this);
            $.ajax({
                url: '{{ route("allColor") }}',
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    // Initialize Tagify with the received data as the whitelist
                    var tags = new Tagify($tagifyInput[0], {
                        whitelist: data,
                        maxTags: 6,
                        dropdown: {
                            maxItems: 20,
                            classname: "tagify__inline__suggestions",
                            enabled: 0,
                            closeOnSelect: false
                        }
                    });
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    }
    initTagify(document);


    // Function to handle image file uploads
    function handleImageUpload(input) {
        var $parentRow = $(input).closest('.row');
        var file = input.files[0]; // Get the selected file

        if (file) {
            // You can add further validation here (e.g., file type, size)

            // Assuming you want to display the file name for reference
            $parentRow.find('.image_filename').text(file.name);
        }
    }

    // Attach input event listeners to the document and delegate to dynamically created elements
    $(document).on('change', '.product_image_input', function () {
        handleImageUpload(this);
    });


</script>