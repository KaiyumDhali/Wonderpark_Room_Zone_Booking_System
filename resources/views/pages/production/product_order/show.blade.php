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
                                <h1 class="h3 mb-0 text-gray-800">{{ __('Product Order Details') }}</h1>
                            </div>
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                @can('create product')
                                    <a href="{{ route('product_order.index') }}"
                                        class="btn btn-primary btn-sm shadow-sm">{{ __('Go Back') }}</a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-md-12 pb-5">
                                    <div class="card-header align-items-center py-3 px-0 gap-2 gap-md-5">
                                        <div class="card-title">
                                            <h2 class="h3 mb-0 text-gray-800">Customer Name: {{ $productOrder->customer_name }}</h2>
                                        </div>
                                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                            <h5 class="text-end">
                                                Order Date: {{ date('d F Y', strtotime($productOrder->order_date)) }}
                                                <br>
                                                Delivery Order: {{ date('d F Y', strtotime($productOrder->delivery_date)) }}
                                            </h5>
                                        </div>
                                    </div>
                                    {{-- <table
                                        class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                                        <thead>
                                            <tr class="text-start fs-7 text-uppercase gs-0">
                                                <th class="">Customer Name</th>
                                                <th class="">Order Date</th>
                                                <th class="">Delivery Order</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-700">
                                            <td class="">{{ $productOrder->customer_name }}</td>
                                            <td class="">{{ $productOrder->order_date }}</td>
                                            <td class="">{{ $productOrder->delivery_date }}</td>
                                        </tbody>
                                    </table> --}}
                                </div>
                                <div class="col-md-12 py-5" >
                                    <table
                                        class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                                        <thead>
                                            <!--begin::Table row-->
                                            <tr class="text-start fs-7 text-uppercase gs-0">
                                                <th class="">Order No</th>
                                                <th class="">Manufacturer Article No</th>
                                                <th class="">Customer Article No</th>
                                                <th class="">Order Category</th>
                                                <th class="">Order Type</th>
                                                <th class="">Last No</th>
                                                {{-- <th class="">Description</th>
                                                <th class="">remarks</th> --}}
                                            </tr>
                                            <!--end::Table row-->
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody class="fw-semibold text-gray-700">
                                            <td class="">{{ $productOrder->order_number }}</td>
                                            <td class="">{{ $productOrder->manufacturer_article_no }}</td>
                                            <td class="">{{ $productOrder->customer_article_no }}</td>
                                            <td class="">{{ $productOrder->order_category }}</td>
                                            <td class="">{{ $productOrder->order_type }}</td>
                                            <td class="">{{ $productOrder->last_no }}</td>
                                            {{-- <td class="">{{ $productOrder->description }}</td>
                                            <td class="">{{ $productOrder->remarks }}</td> --}}
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                </div>

                                @foreach ($productOrderDetails as $productOrderDetail)
                                    <div class="col-md-4 py-5" style="background-color: aliceblue">
                                        <table class="table table-hover table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th scope="row" class="w-25"><strong>Image</strong></th>
                                                    <td><img src="{{ Storage::url($productOrderDetail->image) }}"
                                                            height="80" width="100" alt="" /></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="w-25"><strong>Color</strong></th>
                                                    <td>{{ $productOrderDetail->color }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-8 py-5" style="background-color: aliceblue">

                                        <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                                            <thead>
                                                <tr class="text-start fs-7 text-uppercase gs-0">
                                                    <th class="">size_id</th>
                                                    <th class="">quantity</th>
                                                    <th class="">unit_price</th>
                                                    <th class="">total_price</th>
                                                </tr>
                                            </thead>
                                            @foreach ($productOrderDetailsChains as $productOrderDetailsChain)
                                            <tbody class="fw-semibold text-gray-700">
                                                <td class="">{{ $productOrderDetailsChain->size_name }}</td>
                                                <td class="">{{ $productOrderDetailsChain->quantity }}</td>
                                                <td class="">{{ $productOrderDetailsChain->unit_price }}</td>
                                                <td class="">{{ $productOrderDetailsChain->total_price }}</td>
                                            </tbody>
                                            @endforeach

                                        </table>
                                    </div>

                                    <hr>
                                @endforeach

                                <div class="col-md-12 pb-5">
                                    <table
                                        class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0">
                                        <thead>
                                            <!--begin::Table row-->
                                            <tr class="text-start fs-7 text-uppercase gs-0">
                                                <th class="">Description</th>
                                                <th class="">remarks</th>
                                            </tr>
                                            <!--end::Table row-->
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody class="fw-semibold text-gray-700">
                                            <td class="">{{ $productOrder->description }}</td>
                                            <td class="">{{ $productOrder->remarks }}</td>
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
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
