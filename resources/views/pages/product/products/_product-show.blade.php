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
                        Product Details </h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    {{-- <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="../../demo1/dist/index.html" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">eCommerce</li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Catalog</li>
                        <!--end::Item-->
                    </ul> --}}
                    <!--end::Breadcrumb-->
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
               <!--begin::Main column-->
               <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <!-- Content Row -->
                <div class="card shadow">
                    {{-- <div class="card-header">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">{{ __('Product Details')}}</h1>
                            <a href="{{ route('products.index') }}"
                                class="btn btn-primary btn-sm shadow-sm">{{ __('Go Back') }}</a>
                        </div>
                    </div> --}}
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-hover table-bordered">
                                    <tbody>
                                        {{-- <tr>
                                            <th scope="row" class="w-50"><strong>Supplier Name:</strong></th>
                                            <td class="w-50">{{ $product->supplier_name }}</td>
                                        </tr> --}}
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Type:</strong></th>
                                            <td class="w-50">{{ $product->type_name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Category:</strong></th>
                                            <td class="w-50">{{ $product->category_name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Sub Category:</strong></th>
                                            <td class="w-50">{{ $product->sub_category_name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Brand Name:</strong></th>
                                            <td class="w-50">{{ $product->brand_name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Fragrance Name:</strong></th>
                                            <td class="w-50">{{ $product->fragrance_name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Color:</strong></th>
                                            <td class="w-50">{{ $product->color_name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Size:</strong></th>
                                            <td class="w-50">{{ $product->size_name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Product Code:</strong></th>
                                            <td class="w-50">{{ $product->product_code }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>HS Code (Raw material):</strong></th>
                                            <td class="w-50">{{ $product->hs_code }}</td>
                                        </tr>
                                        {{-- <tr>
                                            <th scope="row" class="w-50"><strong>Article No:</strong></th>
                                            <td class="w-50">{{ $product->article_no }}</td>
                                        </tr> --}}
                                        {{-- <tr>
                                            <th scope="row" class="w-50"><strong>Product Thickness:</strong></th>
                                            <td class="w-50">{{ $product->product_thickness }}</td>
                                        </tr> --}}
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Pack Size:</strong></th>
                                            <td class="w-50">{{ $product->pack_size }}</td>
                                        </tr>
                                        {{-- <tr>
                                            <th scope="row" class="w-50"><strong>Product Remarks:</strong></th>
                                            <td class="w-50">{{ $product->product_remarks }}</td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-hover table-bordered">
                                    <tbody>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Product Name:</strong></th>
                                            <td class="w-50">{{ $product->product_name }}</td>
                                        </tr>
                                        <!-- <tr>
                                            <th scope="row" class="w-50"><strong>Product Slug:</strong></th>
                                            <td class="w-50">{{ $product->product_slug }}</td>
                                        </tr> -->
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Country of Origin:</strong></th>
                                            <td class="w-50">{{ $product->country_Of_origin }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Purchase Price:</strong></th>
                                            <td class="w-50">{{ $product->purchase_price }}</td>
                                        </tr>
                                        {{-- <tr>
                                            <th scope="row" class="w-50"><strong>Profit Percent:</strong></th>
                                            <td class="w-50">{{ $product->profit_percent }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Wholesale Price:</strong></th>
                                            <td class="w-50">{{ $product->wholesale_price }}</td>
                                        </tr> --}}
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Sales Price:</strong></th>
                                            <td class="w-50">{{ $product->sales_price }}</td>
                                        </tr>
                                        {{-- <tr>
                                            <th scope="row" class="w-50"><strong>Credit Sales Price:</strong></th>
                                            <td class="w-50">{{ $product->credit_sales_price }}</td>
                                        </tr> --}}
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Minimum Alert Quantity:</strong></th>
                                            <td class="w-50">{{ $product->minimum_alert_quantity }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Is Taxable:</strong></th>
                                            <td class="w-50">{{ $product->is_taxable==1?'Yes':'No' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Tax Rate:</strong></th>
                                            <td class="w-50">{{ $product->tax_rate }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Product Description:</strong></th>
                                            <td class="w-50">{{ $product->product_description }}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th scope="row"><strong>Serviceable:</strong> </th>
                                            <td>{{ $product->is_serviceable==1?'Yes':'No' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><strong>Status:</strong> </th>
                                            <td>{{ $product->status==1?'Active':'Disable' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>

                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <!--begin::Button-->
                    <a class="btn btn-sm btn-primary" href="{{ route('products.index') }}"
                        id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">Cancel</a>
                    <!--end::Button-->
                </div>
                <!-- Content Row -->
            </div>
            <!--end::Main column-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->

</div>
</x-default-layout>