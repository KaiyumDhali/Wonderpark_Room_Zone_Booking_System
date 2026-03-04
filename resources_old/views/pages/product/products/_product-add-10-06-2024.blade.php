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
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Add Product</h1>
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
                          action="{{ route('products.store') }}" enctype="multipart/form-data">
                    @csrf
                        <!-- @method('PUT') -->
                        <!--begin::Aside column-->
                        <div class="col-12 col-md-3 me-lg-10">
                            <!--begin::Status-->
                            <div class="card card-flush py-4">
                            {{-- <div class="card-header">
                                <div class="card-title">
                                    <h2>Select Info</h2>
                                </div>
                                <div class="card-toolbar">
                                    <div class="rounded-circle bg-success w-15px h-15px"
                                        id="kt_ecommerce_add_category_status"></div>
                                </div>
                            </div> --}}
                                {{-- <div class="card-body pt-0 pb-2">
                                    <select class="form-select form-select-sm" name="supplier_id" >
                                        <option selected disabled>Select Suppliers</option>
                                    @foreach($allSuppliers as $key => $value)
                                        <option value="{{ $key }}">{{ $value}}</option>
                                    @endforeach
                                    </select>
                                </div> --}}
                                <div class="card-body pt-0 pb-2">
                                    <label class="required form-label">Product Type</label>
                                    <select class="form-select form-select-sm" name="type_id" required>
                                        <option value="">Select Type</option>
                                    @foreach($allProductTypes as $key => $value)
                                        <option value="{{ $key }}">{{ $value}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="card-body pt-0 pb-2">
                                    <label class="required form-label">Category</label>
                                    <select class="form-select form-select-sm" name="category_id" required>
                                        <option value="">Select Type</option>
                                    @foreach($allCategories as $key => $value)
                                        <option value="{{ $key }}">{{ $value}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="card-body pt-0 pb-2">
                                    <label class=" form-label">Sub Category</label>
                                    <select class="form-select form-select-sm" name="sub_category_id" >
                                        <option selected disabled>Select Category</option>
                                    @foreach($allSubCategories as $key => $value)
                                        <option value="{{ $key }}">{{ $value}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="card-body pt-0 pb-2">
                                    <label class=" form-label">Product Brand </label>
                                    <select class="form-select form-select-sm" name="brand_id" >
                                        <option selected disabled>Select Product Brand</option>
                                    @foreach($allBrands as $key => $value)
                                        <option value="{{ $key }}">{{ $value}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="card-body pt-0 pb-2">
                                    <label class=" form-label">Product Fragrance </label>
                                    <select class="form-select form-select-sm" name="fragrance_id" >
                                        <option selected disabled>Select Product Fragrance</option>
                                    @foreach($allProductFragrances as $key => $value)
                                        <option value="{{ $key }}">{{ $value}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="card-body pt-0 pb-2">
                                    <label class=" form-label">Product Color</label>
                                    <select class="form-select form-select-sm" name="color_id" >
                                        <option selected disabled>Select Product Color</option>
                                    @foreach($allColors as $key => $value)
                                        <option value="{{ $key }}">{{ $value}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="card-body pt-0 pb-2">
                                    <label class=" form-label">Product Size</label>
                                    <select class="form-select form-select-sm" name="size_id" >
                                        <option selected disabled>Select Product Size</option>
                                    @foreach($allSizes as $key => $value)
                                        <option value="{{ $key }}">{{ $value}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="card-body pt-0 pb-2">
                                    <label class="required form-label">Product Unit</label>
                                    <select class="form-select form-select-sm" name="unit_id" required>
                                        <option value="">Select Product Unit</option>
                                    @foreach($allUnits as $key => $value)
                                        <option value="{{ $key }}">{{ $value}}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="card card-flush my-5">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h3>Status</h3>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="rounded-circle bg-success w-15px h-15px"
                                             id="kt_ecommerce_add_category_status"></div>
                                    </div>
                                </div>
                                <div class="card-body pt-0 pb-5">
                                    <select class="form-select form-select-sm" name="status" >
                                        <option value="">--Select Status--</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>
                            </div> --}}
                        </div>
                        <!--end::Aside column-->
                        <!--begin::Main column-->
                        <div class="col-12 col-md-9 me-lg-10">
                            <!--begin::General options-->
                            <div class="card card-flush py-4">
                            {{-- <div class="card-header">
                                <div class="card-title">
                                    <h2>General</h2>
                                </div>
                            </div> --}}
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
                                                <input type="number" name="purchase_price" class="form-control form-control-sm mb-2"
                                                       placeholder="Purchase Price" value="">
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
                                                <input type="number" name="sales_price" class="form-control form-control-sm mb-2"
                                                       placeholder="Sales Price" value="">
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
                                                <select class="form-select form-select-sm" name="is_taxable" >
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
                                            <select class="form-select form-select-sm" name="status" required>
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
                                </div>
                            </div>
                            <!--end::General options-->

                            <div class="d-flex justify-content-end my-5">
                                <!--begin::Button-->
                                <a href="{{ route('products.index') }}"
                                   id="kt_ecommerce_add_product_cancel" class="btn btn-sm btn-primary me-5">Cancel</a>
                                <!--end::Button-->
                                <!--begin::Button-->
                                <button type="submit" id="kt_ecommerce_add_category_submit" class="btn btn-sm btn-success">
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
</x-default-layout>