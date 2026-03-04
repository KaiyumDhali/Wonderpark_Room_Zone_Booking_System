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
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Update Product</h1>
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
                    action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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
                            {{-- <div class="card-body pt-0 pb-5">
                                <label class=" form-label">Suppliers</label>
                                <select class="form-select form-select-sm" data-control="select2" name="supplier_id" >
                                    <option selected disabled>Select Suppliers</option>
                                    @foreach($allSuppliers as $key => $value)
                                    <option value="{{ $key }}" {{ $product->supplier_id==$key ? 'selected' : ''}}>{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="card-body pt-0 pb-5">
                                <label class="required form-label">Product Type</label>
                                <select class="form-select form-select-sm" data-control="select2" name="type_id" required>
                                    <option selected value="">Select Parent Category</option>
                                    @foreach($allProductTypes as $key => $value)
                                    <option value="{{ $key }}" {{ $product->type_id==$key ? 'selected' : ''}}>{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="card-body pt-0 pb-5">
                                <label class="required form-label">Category</label>
                                <select class="form-select form-select-sm" data-control="select2" name="category_id" required>
                                    <option selected value="">Select Parent Category</option>
                                    @foreach($allCategories as $key => $value)
                                    <option value="{{ $key }}" {{ $product->category_id==$key ? 'selected' : ''}}>{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="card-body pt-0 pb-5">
                                <label class=" form-label">Sub Category</label>
                                <select class="form-select form-select-sm" data-control="select2" name="sub_category_id" >
                                    <option selected value="">Select Sub Category</option>
                                    @foreach($allSubCategories as $key => $value)
                                    <option value="{{ $key }}" {{ $product->sub_category_id==$key ? 'selected' : ''}}>{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="card-body pt-0 pb-5">
                                <label class=" form-label">Product Brand </label>
                                <select class="form-select form-select-sm" data-control="select2" name="brand_id" >
                                    <option selected value="">Select Product Brand</option>
                                    @foreach($allBrands as $key => $value)
                                    <option value="{{ $key }}" {{ $product->brand_id==$key ? 'selected' : ''}}>{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="card-body pt-0 pb-5">
                                <label class=" form-label">Product Fragrance </label>
                                <select class="form-select form-select-sm" data-control="select2" name="fragrance_id" >
                                    <option selected value="">Select Product Fragrance</option>
                                    @foreach($allProductFragrances as $key => $value)
                                    <option value="{{ $key }}" {{ $product->fragrance_id==$key ? 'selected' : ''}}>{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="card-body pt-0 pb-5">
                                <label class=" form-label">Product Color</label>
                                <select class="form-select form-select-sm" data-control="select2" name="color_id " >
                                    <option selected value="">Select Product Color</option>
                                    @foreach($allColors as $key => $value)
                                    <option value="{{ $key }}" {{ $product->color_id==$key ? 'selected' : ''}}>{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="card-body pt-0 pb-5">
                                <label class=" form-label">Product Size</label>
                                <select class="form-select form-select-sm" data-control="select2" name="size_id" >
                                    <option selected value="">Select Product Size</option>
                                    @foreach($allSizes as $key => $value)
                                    <option value="{{ $key }}" {{ $product->size_id==$key ? 'selected' : ''}}>{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="card-body pt-0 pb-5">
                                <label class="required form-label">Product Unit</label>
                                <select class="form-select form-select-sm" data-control="select2" name="unit_id" required>
                                    <option selected value="">Update Parent Category</option>
                                    @foreach($allUnits as $key => $value)
                                    <option value="{{ $key }}" {{ $product->unit_id==$key ? 'selected' : ''}}>
                                        {{ $value}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- <div class="card card-flush my-5">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Status</h2>
                                </div>
                                <div class="card-toolbar">
                                    <div class="rounded-circle bg-success w-15px h-15px"
                                        id="kt_ecommerce_add_category_status"></div>
                                </div>
                            </div>
                            <div class="card-body pt-0 pb-5">
                                <select class="form-select form-select-sm" data-control="select2" name="status" >
                                    <option value="">--Select Status--</option>
                                    <option value="1" {{ $product->status==1?'selected':'' }}>Active</option>
                                    <option value="0" {{ $product->status==0?'selected':'' }}>Disable</option>
                                </select>
                            </div>
                        </div> --}}
                    </div>
                    <!--end::Aside column-->
                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <!--begin::General options-->
                        <div class="card card-flush py-4">
                            {{-- <div class="card-header">
                                <div class="card-title">
                                    <h2>General</h2>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-5">
                                        <div class=" fv-row fv-plugins-icon-container">
                                            <label class=" form-label"> Product Code </label>
                                            <input type="text" name="product_code" class="form-control form-control-sm mb-2"
                                                placeholder="Product Code" value="{{$product->product_code}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-5">
                                        <div class=" fv-row fv-plugins-icon-container">
                                            <label class=" form-label"> HS Code (Raw material)</label>
                                            <input type="text" name="hs_code" class="form-control form-control-sm mb-2"
                                                placeholder="HS Code" value="{{$product->hs_code}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-5">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class="required form-label"> Product Name </label>
                                            <input type="text" name="product_name" class="form-control form-control-sm mb-2"
                                                placeholder="Product Name" value="{{$product->product_name}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-5">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label"> Country of Origin </label>
                                            <input type="text" name="country_Of_origin" class="form-control form-control-sm mb-2"
                                                placeholder="Country of Origin" value="{{$product->country_Of_origin}}">
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-5">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Product Thickness</label>
                                            <input type="text" name="product_thickness" class="form-control form-control-sm mb-2"
                                                placeholder="Product Thickness" value="{{$product->product_thickness}}">
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-5">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Pack Size</label>
                                            <input type="number" name="pack_size" class="form-control form-control-sm mb-2"
                                                placeholder="Pack Size" value="{{$product->pack_size}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-5">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Minimum Alert Quantity</label>
                                            <input type="number" name="minimum_alert_quantity" class="form-control form-control-sm mb-2"
                                                placeholder="Minimum Alert Quantity" value="{{$product->minimum_alert_quantity}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-5">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Purchase Price</label>
                                            {{-- <input type="number" name="purchase_price" class="form-control form-control-sm mb-2"
                                                placeholder="Purchase Price" value="{{$product->purchase_price}}"> --}}
                                            <input type="text" class="form-control form-control-sm mb-2" id="purchase_price" name="purchase_price"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" value="{{$product->purchase_price}}">
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-5">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Profit Percent</label>
                                            <input type="number" name="profit_percent" class="form-control form-control-sm mb-2"
                                                placeholder="Profit Percent" value="{{$product->profit_percent}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-5">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Wholesale Price</label>
                                            <input type="number" name="wholesale_price" class="form-control form-control-sm mb-2"
                                                placeholder="Wholesale Price" value="{{$product->wholesale_price}}">
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-5">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Sales Price</label>
                                            {{-- <input type="number" name="sales_price" class="form-control form-control-sm mb-2"
                                                placeholder="Sales Price" value="{{$product->sales_price}}"> --}}
                                            <input type="text" class="form-control form-control-sm mb-2" id="sales_price" name="sales_price"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" value="{{$product->sales_price}}">
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-5">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Credit Sales Price</label>
                                            <input type="number" name="credit_sales_price" class="form-control form-control-sm mb-2"
                                                placeholder="Credit Sales Price" value="{{$product->credit_sales_price}}">
                                        </div>
                                    </div>
                                </div> --}}
                                
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-5">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Is Taxable</label>
                                            <select class="form-select form-select-sm" data-control="select2" name="is_taxable" >
                                                <option value="">--Select Is Taxable--</option>
                                                <option value="1" {{ $product->is_taxable==1?'selected':'' }}>Yes</option>
                                                <option value="0" {{ $product->is_taxable==0?'selected':'' }}>No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-5">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Tax Rate</label>
                                            <input type="number" name="tax_rate" class="form-control form-control-sm mb-2"
                                                placeholder="Tax Rate" value="{{$product->tax_rate}}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-5">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Description</label>
                                            <input type="text" name="product_description" class="form-control form-control-sm mb-2"
                                                placeholder="Description" value="{{$product->product_description}}">
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-5">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Remarks</label>
                                            <input type="text" name="product_remarks" class="form-control form-control-sm mb-2"
                                                placeholder="Remarks" value="{{$product->product_remarks}}">
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-2">
                                        <label class="required form-label">Product Status</label>
                                        <select class="form-select form-select-sm" data-control="select2" name="status" >
                                            <option value="">--Select Status--</option>
                                            <option value="1" {{ $product->status==1?'selected':'' }}>Active</option>
                                            <option value="0" {{ $product->status==0?'selected':'' }}>Disable</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="card-body py-3">
                                        <div class="product fv-row fv-plugins-icon-container">
											<label class="form-check form-switch form-check-custom form-check-solid">
												<input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="is_purchaseable" {{ $product->is_purchaseable==1 ? 'checked' : '' }}/>
												<span class="form-check-label text-muted fs-6">is Purchaseable </span>
											</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="card-body py-3">
                                        <div class="product fv-row fv-plugins-icon-container">
											<label class="form-check form-switch form-check-custom form-check-solid">
												<input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="is_consumable" {{ $product->is_consumable==1 ? 'checked' : '' }}/>
												<span class="form-check-label text-muted fs-6">is Consumable </span>
											</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="card-body py-3">
                                        <div class="product fv-row fv-plugins-icon-container">
											<label class="form-check form-switch form-check-custom form-check-solid">
												<input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="is_produceable" {{ $product->is_produceable==1 ? 'checked' : '' }}/>
												<span class="form-check-label text-muted fs-6">is Produceable </span>
											</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="card-body py-3">
                                        <div class="product fv-row fv-plugins-icon-container">
											<label class="form-check form-switch form-check-custom form-check-solid">
												<input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="is_saleable" {{ $product->is_saleable==1 ? 'checked' : '' }}/>
												<span class="form-check-label text-muted fs-6">is Saleable </span>
											</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="card-body py-3">
                                        <div class="product fv-row fv-plugins-icon-container">
											<label class="form-check form-switch form-check-custom form-check-solid">
												<input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="is_serviceable" {{ $product->is_serviceable==1 ? 'checked' : '' }}/>
												<span class="form-check-label text-muted fs-6">is Serviceable </span>
											</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::General options-->

                        <div class="d-flex justify-content-end">
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