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
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                            Add Product</h1>
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
                    <form id="productAddForm"
                        class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework"
                        method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- @method('PUT') -->
                        <!--begin::Aside column-->
                        <div class="col-12 col-md-3 me-lg-10">
                            <!--begin::Status-->
                            <div class="card card-flush py-4">
                                <!-- <div class="card-body pt-0 pb-2 d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <label class="required form-label">Product Type</label>
                                        <select class="form-select form-select-sm" data-control="select2" name="type_id" required>
                                            <option value="">Select Type</option>
                                        @foreach($allProductTypes as $key => $value)
                                            <option value="{{ $key }}">{{ $value}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary ms-2 mt-8"
                                        data-bs-toggle="modal" data-bs-target="#add_type">
                                        Add
                                    </a>
                                </div> -->

                                <input type="hidden" name="type_id" value="1" data-control="select2">


                                <div class="card-body pt-0 pb-2 d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <label class="required form-label">Category</label>
                                        <select class="form-select form-select-sm" data-control="select2" name="category_id" required>
                                            <option value="">Select Type</option>
                                        @foreach($allCategories as $key => $value)
                                            <option value="{{ $key }}">{{ $value}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary ms-2 mt-8"
                                        data-bs-toggle="modal" data-bs-target="#add_category">
                                        Add
                                    </a>
                                </div>

                                <div class="card-body pt-0 pb-2 d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <label class=" form-label">Sub Category</label>
                                        <select class="form-select form-select-sm" data-control="select2" name="sub_category_id" >
                                            <option selected disabled>Select Category</option>
                                        @foreach($allSubCategories as $key => $value)
                                            <option value="{{ $key }}">{{ $value}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary ms-2 mt-8"
                                        data-bs-toggle="modal" data-bs-target="#add_sub_category">
                                        Add
                                    </a>
                                </div>


                                <div class="card-body pt-0 pb-2 d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <label class=" form-label">Product Brand </label>
                                        <select class="form-select form-select-sm" data-control="select2" name="brand_id" >
                                            <option selected disabled>Select Product Brand</option>
                                        @foreach($allBrands as $key => $value)
                                            <option value="{{ $key }}">{{ $value}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary ms-2 mt-8"
                                        data-bs-toggle="modal" data-bs-target="#add_brand">
                                        Add
                                    </a>
                                </div>

                                <div class="card-body pt-0 pb-2 d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <label class=" form-label">Product Fragrance </label>
                                        <select class="form-select form-select-sm" data-control="select2" name="fragrance_id" >
                                            <option selected disabled>Select Product Fragrance</option>
                                        @foreach($allProductFragrances as $key => $value)
                                            <option value="{{ $key }}">{{ $value}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary ms-2 mt-8"
                                        data-bs-toggle="modal" data-bs-target="#add_fragrance">
                                        Add
                                    </a>
                                </div>

                                <div class="card-body pt-0 pb-2 d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <label class=" form-label">Product Color</label>
                                        <select class="form-select form-select-sm" data-control="select2" name="color_id" >
                                            <option selected disabled>Select Product Color</option>
                                        @foreach($allColors as $key => $value)
                                            <option value="{{ $key }}">{{ $value}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary ms-2 mt-8"
                                        data-bs-toggle="modal" data-bs-target="#add_color">
                                        Add
                                    </a>
                                </div>

                                <div class="card-body pt-0 pb-2 d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <label class=" form-label">Product Size</label>
                                        <select class="form-select form-select-sm" data-control="select2" name="size_id" >
                                            <option selected disabled>Select Product Size</option>
                                        @foreach($allSizes as $key => $value)
                                            <option value="{{ $key }}">{{ $value}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary ms-2 mt-8"
                                        data-bs-toggle="modal" data-bs-target="#add_size">
                                        Add
                                    </a>
                                </div>


                                <div class="card-body pt-0 pb-2 d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <label class="required form-label">Product Unit</label>
                                        <select class="form-select form-select-sm" data-control="select2" name="unit_id" required>
                                            <option value="">Select Product Unit</option>
                                        @foreach($allUnits as $key => $value)
                                            <option value="{{ $key }}">{{ $value}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary ms-2 mt-8"
                                        data-bs-toggle="modal" data-bs-target="#add_unit">
                                        Add
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!--end::Aside column-->
                        <!--begin::Main column-->
                        <div class="col-12 col-md-9 me-lg-10">
                            <!--begin::General options-->
                            <div class="card card-flush py-4">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class=" fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> Product Code </label>
                                                <input type="text" name="product_code" class="form-control form-control-sm mb-2"
                                                    placeholder="Product Code" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class=" fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> HS Code (Raw material)</label>
                                                <input type="text" name="hs_code" class="form-control form-control-sm mb-2"
                                                    placeholder="HS Code" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class="required form-label"> Product Name </label>
                                                <input type="text" name="product_name" class="form-control form-control-sm mb-2"
                                                    placeholder="Product Name" value="" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label"> Country of Origin </label>
                                                <input type="text" name="country_Of_origin" class="form-control form-control-sm mb-2"
                                                    placeholder="Country of Origin" value="">
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Product Thickness</label>
                                                <input type="text" name="product_thickness" class="form-control form-control-sm mb-2"
                                                    placeholder="Product Thickness" value="">
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Pack Size</label>
                                                <input type="number" name="pack_size" class="form-control form-control-sm mb-2"
                                                    placeholder="Pack Size" value="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Minimum Alert Quantity</label>
                                                <input type="number" name="minimum_alert_quantity" class="form-control form-control-sm mb-2"
                                                    placeholder="Minimum Alert Quantity" value="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Purchase Price</label>
                                                {{-- <input type="number" name="purchase_price" class="form-control form-control-sm mb-2"
                                                    placeholder="Purchase Price" value=""> --}}
                                                <input type="text" class="form-control form-control-sm mb-2" id="purchase_price" name="purchase_price"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Profit Percent</label>
                                                <input type="number" name="profit_percent" class="form-control form-control-sm mb-2"
                                                    placeholder="Profit Percent" value="">
                                            </div>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Wholesale Price</label>
                                                <input type="number" name="wholesale_price" class="form-control form-control-sm mb-2"
                                                    placeholder="Wholesale Price" value="">
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Sales Price</label>
                                                {{-- <input type="number" name="sales_price" class="form-control form-control-sm mb-2"
                                                    placeholder="Sales Price" value=""> --}}
                                                <input type="text" class="form-control form-control-sm mb-2" id="sales_price" name="sales_price"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Credit Sales Price</label>
                                                <input type="number" name="credit_sales_price" class="form-control form-control-sm mb-2"
                                                    placeholder="Credit Sales Price" value="">
                                            </div>
                                        </div>
                                    </div> --}}
                                    
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Is Taxable</label>
                                                <select class="form-select form-select-sm" data-control="select2" name="is_taxable" >
                                                    <option value="">--Select Is Taxable--</option>
                                                    <option value="1" selected>Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Tax Rate</label>
                                                <input type="number" name="tax_rate" class="form-control form-control-sm mb-2"
                                                    placeholder="Tax Rate" value="">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Description</label>
                                                <input type="text" name="product_description" class="form-control form-control-sm mb-2"
                                                    placeholder="Description" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <label class="required form-label">Product Status</label>
                                            <select class="form-select form-select-sm" data-control="select2" name="status" required>
                                                <option value="">--Select Status--</option>
                                                <option value="1" selected>Active</option>
                                                <option value="0">Disable</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- <div class="col-12 col-md-6">
                                        <div class="card-body pt-0 pb-2">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class=" form-label">Remarks</label>
                                                <input type="text" name="product_remarks" class="form-control form-control-sm mb-2"
                                                    placeholder="Remarks" value="">
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-12 col-md-3">
                                        <div class="card-body py-3">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="is_purchaseable" />
                                                    <span class="form-check-label text-muted fs-6">is Purchaseable </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="card-body py-3">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="is_consumable" />
                                                    <span class="form-check-label text-muted fs-6">is Consumable </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="card-body py-3">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="is_produceable" />
                                                    <span class="form-check-label text-muted fs-6">is Produceable </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="card-body py-3">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="is_saleable" />
                                                    <span class="form-check-label text-muted fs-6">is Saleable </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="card-body py-3">
                                            <div class="product fv-row fv-plugins-icon-container">
                                                <label class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="is_serviceable" />
                                                    <span class="form-check-label text-muted fs-6">is Serviceable </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::General options-->

                            <div class="d-flex justify-content-end my-5">
                                <!--begin::Button-->
                                <a href="{{ route('products.index') }}" id="kt_ecommerce_add_product_cancel"
                                    class="btn btn-sm btn-primary me-5">Cancel</a>
                                <!--end::Button-->
                                <!--begin::Button-->
                                <button type="submit" id="kt_ecommerce_add_category_submit"
                                    class="btn btn-sm btn-success">
                                    <span class="indicator-label">Save</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Button-->
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

    <!--begin:: New Add Type Modal - Create App-->
    <div class="modal fade" id="add_fragrance" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add New Fragrance</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i style="font-size: 20px" class="fas fa-times"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                    <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                        action="{{ route('product_fragrance.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Fragrance Name</span>
                            </label>
                            <input type="text" class="form-control form-control-sm" name="fragrance_name" required>
                        </div>

                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span>Status</span>
                            </label>
                            <select class="form-select form-select-sm" data-control="select2" name="status">
                                <option value="">--Select Status--</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                        <div class="text-center pt-5">
                            <a href="{{ route('products.create') }}" id="new_card_cancel"
                                class="btn btn-sm btn-success me-5">Cancel</a>

                            <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
    </div>
    <!--end::Type Modal - Create App-->

    <!--begin:: New Add Type Modal - Create App-->
    <div class="modal fade" id="add_type" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add New Type</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i style="font-size: 20px" class="fas fa-times"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                    <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                        action="{{ route('product_type.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Type Name</span>
                            </label>
                            <input type="text" class="form-control form-control-sm" name="type_name" required>
                        </div>

                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span>Status</span>
                            </label>
                            <select class="form-select form-select-sm" data-control="select2" name="status">
                                <option value="">--Select Status--</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                        <div class="text-center pt-5">
                            <a href="{{ route('products.create') }}" id="new_card_cancel"
                                class="btn btn-sm btn-success me-5">Cancel</a>

                            <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
    </div>
    <!--end::Type Modal - Create App-->

    <!--begin:: New Add category Modal - Create App-->
    <div class="modal fade" id="add_category" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add Category</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i style="font-size: 20px" class="fas fa-times"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                    <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                        action="{{ route('categories_store.store') }}" enctype="multipart/form-data">
                        @csrf
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Category Name</span>
                            </label>
                            <input type="text" class="form-control form-control-sm" name="category_name" required>
                            <input type="text" class="form-control form-control-sm" name="sumite_type" value="1" hidden>
                        </div>

                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span>Status</span>
                            </label>
                            <select class="form-select form-select-sm" data-control="select2" name="status">
                                <option value="">--Select Status--</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                        <div class="text-center pt-5">
                            <a href="{{ route('products.create') }}" id="new_card_cancel"
                                class="btn btn-sm btn-success me-5">Cancel</a>

                            <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary new_card_submit">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
    </div>
    <!--end::category Modal - Create App-->

    <!--begin:: New Add Sub Category  Modal - Create App-->
    <div class="modal fade" id="add_sub_category" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add Sub Category</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i style="font-size: 20px" class="fas fa-times"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                    <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                        action="{{ route('sub_category.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span>Parent Category</span>
                            </label>
                            <select class="form-select form-select-sm" data-control="select2" name="category_id" required>
                                <option selected disabled>Select Parent Category</option>
                                @foreach ($allCategories as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Sub Category Name</span>
                            </label>
                            <input type="text" class="form-control form-control-sm" name="sub_category_name"
                                required>
                            <input type="text" class="form-control form-control-sm" name="sumite_type" value="1" hidden>
                        </div>


                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span>Status</span>
                            </label>
                            <select class="form-select form-select-sm" data-control="select2" name="status">
                                <option value="">--Select Status--</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                        <div class="text-center pt-5">
                            <a href="{{ route('products.create') }}" id="new_card_cancel"
                                class="btn btn-sm btn-success me-5">Cancel</a>

                            <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
    </div>
    <!--end::Sub Category Modal - Create App-->

    <!--begin:: New Add Brand Modal - Create App-->
    <div class="modal fade" id="add_brand" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add Brand</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i style="font-size: 20px" class="fas fa-times"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                    <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                        action="{{ route('brands.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Brand Name</span>
                            </label>
                            <input type="text" class="form-control form-control-sm" name="brand_name" required>
                            <input type="text" class="form-control form-control-sm" name="sumite_type" value="1" hidden>
                        </div>


                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span>Status</span>
                            </label>
                            <select class="form-select form-select-sm" data-control="select2" name="status">
                                <option value="">--Select Status--</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                        <div class="text-center pt-5">
                            <a href="{{ route('products.create') }}" id="new_card_cancel"
                                class="btn btn-sm btn-success me-5">Cancel</a>

                            <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
    </div>
    <!--end::Brand Modal - Create App-->

    <!--begin:: New Add Color Modal - Create App-->
    <div class="modal fade" id="add_color" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add Color</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i style="font-size: 20px" class="fas fa-times"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                    <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                        action="{{ route('colors.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Color Name</span>
                            </label>
                            <input type="text" class="form-control form-control-sm" name="color_name" required>
                            <input type="text" class="form-control form-control-sm" name="sumite_type" value="1" hidden>
                        </div>


                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span>Status</span>
                            </label>
                            <select class="form-select form-select-sm" data-control="select2" name="status">
                                <option value="">--Select Status--</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                        <div class="text-center pt-5">
                            <a href="{{ route('products.create') }}" id="new_card_cancel"
                                class="btn btn-sm btn-success me-5">Cancel</a>

                            <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
    </div>
    <!--end::Color Modal - Create App-->

    <!--begin:: New Add Size Modal - Create App-->
    <div class="modal fade" id="add_size" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add Size</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i style="font-size: 20px" class="fas fa-times"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                    <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                        action="{{ route('sizes.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Size Name</span>
                            </label>
                            <input type="text" class="form-control form-control-sm" name="size_name" required>
                            <input type="text" class="form-control form-control-sm" name="sumite_type" value="1" hidden>
                        </div>


                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span>Status</span>
                            </label>
                            <select class="form-select form-select-sm" data-control="select2" name="status">
                                <option value="">--Select Status--</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                        <div class="text-center pt-5">
                            <a href="{{ route('products.create') }}" id="new_card_cancel"
                                class="btn btn-sm btn-success me-5">Cancel</a>

                            <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
    </div>
    <!--end::Size Modal - Create App-->

    <!--begin:: New Add Unit Modal - Create App-->
    <div class="modal fade" id="add_unit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add Unit</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i style="font-size: 20px" class="fas fa-times"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-5">
                    <form id="new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                        action="{{ route('units.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Unit Name</span>
                            </label>
                            <input type="text" class="form-control form-control-sm" name="unit_name" required>
                            <input type="text" class="form-control form-control-sm" name="sumite_type" value="1" hidden>
                        </div>
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">Unit Value</span>
                            </label>
                            <input type="text" class="form-control form-control-sm" name="unit_value" required>
                        </div>


                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span>Status</span>
                            </label>
                            <select class="form-select form-select-sm" data-control="select2" name="status">
                                <option value="">--Select Status--</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                        <div class="text-center pt-5">
                            <a href="{{ route('products.create') }}" id="new_card_cancel"
                                class="btn btn-sm btn-success me-5">Cancel</a>

                            <button type="submit" id="new_card_submit" class="btn btn-sm btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                        <!--end::Actions-->
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
    document.getElementById("productAddForm").addEventListener("keydown", function(event) {
        // Check if the pressed key is Enter
        if (event.key === "Enter") {
            // Prevent default form submission
            event.preventDefault();
        }
    });
    // SweetAlert2 Confarm message after form on click ========= 
    // $('.new_card_submit').addEventListener('click', function(event) {
    //     ConfarmMsg("new_card_form", "Are You sure.. Want to Save?", "save", event)
    // });
    
    // document.getElementById("kt_modal_new_card_submit_store").addEventListener('click', function(event) {
    //     ConfarmMsg("kt_modal_new_card_form", "Are You sure.. Want to Save?", "save", event)
    // });
    // document.getElementById("kt_modal_new_card_submit").addEventListener('click', function(event) {
    //     ConfarmMsg("kt_modal_edit_card_form_product_type", "Are You sure.. Want to Update?", "Update", event)
    // });
    
</script>
