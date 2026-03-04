<x-default-layout>
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        {{-- <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Product Order Details </h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
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
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar container-->
        </div> --}}
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container">
                <!-- Content Row -->
                <div class="card shadow">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <h1 class="h3 mb-0 text-gray-800">{{ __('Product Order Details')}}</h1>
                        </div>
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            @can('create product')
                                <a href="{{ route('product_order.index') }}"
                                    class="btn btn-primary btn-sm shadow-sm">{{ __('Go Back') }}</a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-hover table-bordered">
                                    <tbody>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Order Number:</strong></th>
                                            <td class="w-50">{{ $productOrder->order_number }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Product Name:</strong></th>
                                            <td class="w-50">{{ $productOrder->product_name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong> Manufacturer Article No:</strong></th>
                                            <td class="w-50">{{ $productOrder->manufacturer_article_no }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Manufacturer Article No:</strong></th>
                                            <td class="w-50">{{ $productOrder->manufacturer_article_no }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Buyer Article No:</strong></th>
                                            <td class="w-50">{{ $productOrder->buyer_article_no }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Last Size:</strong></th>
                                            <td class="w-50">{{ $productOrder->last_size }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Size Grade:</strong></th>
                                            <td class="w-50">{{ $productOrder->size_grade }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Product Color:</strong></th>
                                            <td class="w-50">{{ $productOrder->color_name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Quantity:</strong></th>
                                            <td class="w-50">{{ $productOrder->quantity }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Description:</strong></th>
                                            <td class="w-50">{{ $productOrder->product_description }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="w-50"><strong>Remarks:</strong></th>
                                            <td class="w-50">{{ $productOrder->product_remarks }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><strong>Status:</strong> </th>
                                            <td>{{ $productOrder->status==1?'Active':'Disable' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Product Order Image:</h5>
                                <img src="{{ Storage::url($productOrder->image) }}" height="250"
                                                width="300" alt="" />
                                
                            </div>
                            
                        </div>

                    </div>
                </div>
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