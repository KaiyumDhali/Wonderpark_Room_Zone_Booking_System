<x-default-layout>
    <!--begin::Image input placeholder-->
    <style>
        .image-input-placeholder {
            background-image: url('{{ asset('assets/media/svg/avatars/blank.svg') }}');
        }

        [data-bs-theme="dark"] .image-input-placeholder {
            background-image: url('{{ asset('assets/media/svg/avatars/blank-dark.svg') }}');
        }
    </style>
    <!--end::Image input placeholder-->

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
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                            Add Product Order</h1>
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
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form id="kt_ecommerce_add_category_form"
                        class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework"
                        method="POST" action="{{ route('product_order.store') }}" enctype="multipart/form-data">
                        @csrf
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
                                                            <option value="{{ $key }}">{{ $value }}
                                                            </option>
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
                                                        <option selected disabled>Select Order Category</option>
                                                        <option value="Men">Men</option>
                                                        <option value="Women">Women</option>
                                                        <option value="Children">Children</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class=" form-label">Order Type</label>
                                                    <select class="form-select form-select-sm" data-control="select2" name="order_type">
                                                        <option selected disabled>Select Order Type</option>
                                                        <option value="Sports">Sports</option>
                                                        <option value="Shoes">Shoes</option>
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
                                                        placeholder="Order Number" value="" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class=" fv-row fv-plugins-icon-container">
                                                    <label class=" form-label"> Manufacturer Article No</label>
                                                    <input type="text" name="manufacturer_article_no"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Manufacturer Article No" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class=" fv-row fv-plugins-icon-container">
                                                    <label class=" form-label">Customer Article No</label>
                                                    <input type="text" name="customer_article_no"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Customer Article No" value="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class=" form-label"> Last No </label>
                                                    <input type="text" name="last_no"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Last Size" value="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class=" form-label">Description</label>
                                                    <input type="text" name="description"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Description" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class=" form-label">Remarks</label>
                                                    <input type="text" name="remarks"
                                                        class="form-control form-control-sm mb-2" placeholder="Remarks"
                                                        value="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">Order Date</label>
                                                    <input type="date" name="order_date"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Order Date" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">Delivery Date</label>
                                                    <input type="date" name="delivery_date"
                                                        class="form-control form-control-sm mb-2"
                                                        placeholder="Order Date" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="card-body pt-0 pb-2">
                                                <div class="product fv-row fv-plugins-icon-container">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-select form-select-sm" data-control="select2" name="status">
                                                        <option value="">--Select Status--</option>
                                                        <option value="1" selected>Active</option>
                                                        <option value="0">Disable</option>
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
                                                <div data-repeater-item>
                                                    <div class="form-group row mb-5">
                                                        
                                                        {{-- <div class="col-md-1">
                                                            <!--begin::Image input-->
                                                            <div class="image-input image-input-outline mx-5"
                                                                data-kt-image-input="true"
                                                                style="background-image: url('{{ asset('assets/media/svg/avatars/blank.svg') }}')">
                                                                <!--begin::Image preview wrapper-->
                                                                <div class="image-input-wrapper w-100px h-100px"
                                                                    style="background-image: url('{{ asset('assets/media/svg/avatars/blank-dark.svg') }}')">
                                                                </div>
                                                                <!--end::Image preview wrapper-->

                                                                <!--begin::Edit button-->
                                                                <label
                                                                    class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                                                    data-kt-image-input-action="change"
                                                                    data-bs-toggle="tooltip" data-bs-dismiss="click"
                                                                    title="Change Image">
                                                                    <i class="ki-duotone ki-pencil fs-6"><span
                                                                            class="path1"></span><span
                                                                            class="path2"></span></i>

                                                                    <!--begin::Inputs-->
                                                                    <input type="file" name="image" accept=".png, .jpg, .jpeg" />
                                                                    <input type="hidden" name="avatar_remove" />
                                                                    <!--end::Inputs-->
                                                                </label>
                                                                <!--end::Edit button-->

                                                                <!--begin::Cancel button-->
                                                                <span
                                                                    class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                                                    data-kt-image-input-action="cancel"
                                                                    data-bs-toggle="tooltip" data-bs-dismiss="click"
                                                                    title="Cancel Image">
                                                                    <i class="ki-outline ki-cross fs-3"></i>
                                                                </span>
                                                                <!--end::Cancel button-->

                                                                <!--begin::Remove button-->
                                                                <span
                                                                    class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                                                    data-kt-image-input-action="remove"
                                                                    data-bs-toggle="tooltip" data-bs-dismiss="click"
                                                                    title="Remove Image">
                                                                    <i class="ki-outline ki-cross fs-3"></i>
                                                                </span>
                                                                <!--end::Remove button-->
                                                            </div>
                                                            <!--end::Image input-->

                                                        </div> --}}
                                                        
                                                        <div class="col-md-2">
                                                            <div class="product fv-row fv-plugins-icon-container mx-5">
                                                                <label class="form-label required fw-semibold fs-6 mb-2">Image</label>
                                                                <input type="file" class="product_image_input form-control form-control-sm" name="image" placeholder="Upload Image" required>
                                                                <div class="image_filename"></div> <!-- Placeholder for displaying the filename -->
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-3">
                                                            <div class="card-body pt-0 pb-2">
                                                                <div class="product fv-row fv-plugins-icon-container">
                                                                    <label class="form-label required fw-semibold fs-6 mb-2">Color</label>
                                                                    <input type="text" class="product_color_tagify form-control form-control-sm" name="color" id="product_color_tagify" placeholder="Select Color" required>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
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
                                                                                            <option
                                                                                                value="{{ $key }}">
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
                                                                                        value="" min="0">
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
                                                                                        min="0">
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
                                                                                        value="" readonly>
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
                                                                <button class="btn btn-sm btn-flex btn-light-primary"
                                                                    data-repeater-create type="button">
                                                                    <i class="ki-duotone ki-plus fs-5"></i>
                                                                    Add More
                                                                </button>
                                                            </div>
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
                                            </div>
                                        </div>
                                        <!--end::Form group-->

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
