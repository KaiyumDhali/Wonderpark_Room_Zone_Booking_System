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

                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">

                        <div class="card card-flush py-4">
                            <div class="row">
                                
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-2">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            @if ($productOrder->image != null)
                                            <img src="{{ Storage::url($productOrder->image) }}" height="60" width="80" alt="" />
                                            <a class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete ?')"
                                                href="{{route('product_order.image_destroy', $productOrder->id)}}"><i class="fa fa-trash"></i></a>
                                            <br>
                                            @endif
                                            <label class="form-label pt-5">Update Product Image</label>
                                            <input type="file" name="image" class="form-control form-control-sm mb-2"
                                                placeholder="Product Image" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-2">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class="required form-label">Order Number </label>
                                            <input type="text" name="order_number" class="form-control form-control-sm mb-2"
                                                placeholder="Order Number" value="{{$productOrder->order_number}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-2">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class="required form-label"> Product Name </label>
                                            <input type="text" name="product_name" class="form-control form-control-sm mb-2"
                                                placeholder="Product Name" value="{{$productOrder->product_name}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-2">
                                        <div class=" fv-row fv-plugins-icon-container">
                                            <label class=" form-label"> Manufacturer Article No</label>
                                            <input type="text" name="manufacturer_article_no" class="form-control form-control-sm mb-2"
                                                placeholder="Manufacturer Article No" value="{{$productOrder->manufacturer_article_no}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-2">
                                        <div class=" fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Buyer Article No</label>
                                            <input type="text" name="buyer_article_no" class="form-control form-control-sm mb-2"
                                                placeholder="Buyer Article No" value="{{$productOrder->buyer_article_no}}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-2">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label"> Last Size </label>
                                            <input type="text" name="last_size" class="form-control form-control-sm mb-2"
                                                placeholder="Last Size" value="{{$productOrder->last_size}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-2">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Size Grade</label>
                                            <input type="text" name="size_grade" class="form-control form-control-sm mb-2"
                                                placeholder="Size Grade" value="{{$productOrder->size_grade}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0 pb-2">
                                    
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-2">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Product Color</label>
                                            <select class="form-select form-select-sm" name="color_id " >
                                                <option selected disabled>Select Product Color</option>
                                                @foreach($allColors as $key => $value)
                                                <option value="{{ $key }}" {{ $productOrder->color_id==$key ? 'selected' : ''}}>{{ $value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-2">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Quantity</label>
                                            <input type="number" name="quantity" class="form-control form-control-sm mb-2"
                                                placeholder="Quantity" value="{{$productOrder->quantity}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-2">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Description</label>
                                            <input type="text" name="product_description" class="form-control form-control-sm mb-2"
                                                placeholder="Description" value="{{$productOrder->product_description}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-2">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class=" form-label">Remarks</label>
                                            <input type="text" name="product_remarks" class="form-control form-control-sm mb-2"
                                                placeholder="Remarks" value="{{$productOrder->product_remarks}}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="card-body pt-0 pb-2">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class="form-label">Status</label>
                                            <select class="form-select form-select-sm" name="status" >
                                                <option value="">--Select Status--</option>
                                                <option value="1" {{ $productOrder->status==1?'selected':'' }}>Active</option>
                                                <option value="0" {{ $productOrder->status==0?'selected':'' }}>Disable</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('product_order.index') }}"
                                id="kt_ecommerce_add_product_cancel" class="btn btn-sm btn-success me-5">Cancel</a>
                            <button type="submit" id="kt_ecommerce_add_category_submit" class="btn btn-sm btn-primary">
                                <span class="indicator-label">Order Update</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
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