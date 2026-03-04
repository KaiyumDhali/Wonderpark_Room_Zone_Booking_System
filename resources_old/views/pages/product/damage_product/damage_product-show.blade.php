<x-default-layout>
    <style>
        .card .card-header {
            min-height: 40px;
        }

        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }
    </style>

    @if ($damage_products)
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="">
                <!--begin::Order details page-->
                <div class="d-flex flex-column gap-5 gap-lg-5">
                    <!--begin::Tab content-->
                    <div class="tab-content">
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
                            <!--begin::Orders-->
                            <div class="d-flex flex-column gap-5 gap-lg-5">
                                <div class="d-flex flex-column flex-xl-row gap-5 gap-lg-5">
                                    <!--begin::Payment address-->
                                    <div class="card card-flush flex-row-fluid overflow-hidden pt-3"
                                        style="text-align: left">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Supplier Details </h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body py-0 pt-2">
                                            <b>
                                                Supplier ID : {{ $damage_products[0]->supplier->id }}
                                                <br>Supplier Name : {{ $damage_products[0]->supplier->account_name }}
                                                <br>Address :
                                                {{ $damage_products[0]->supplier->account_address }}
                                                <br>Mobile No :
                                                {{ $damage_products[0]->supplier->account_mobile }}
                                            </b>
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Payment address-->
                                    <!--begin::Shipping address-->
                                    <div class="card card-flush py-0 flex-row-fluid overflow-hidden"
                                        style="text-align: right">
                                        <!--begin::Card body-->
                                        <div class="card-body pt-5">
                                            {{-- <div class="form-group col-2">
                                        </div> --}}

                                            <a href="{{ route('damage_invoice_details_pdf', $damage_products[0]->damage_no) }}"
                                                class="btn btn-sm btn-primary px-5 my-3"
                                                target="_blank">{{ __('PDF Download') }}</a>

                                                <h5> Invoice No (#{{ $damage_products[0]->damage_no }}) </h5>
                                                <h5> Damage Date: {{ $damage_products[0]->damage_date }} </h5>
                                                <h5> Damage Reason: {{ $damage_products[0]->damage_reason }} </h5>
                                                <h5> Warehouse: {{ $damage_products[0]->warehouse->name }} </h5>
                                                
                                            <!-- <br>Prepared By : admin  -->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Shipping address-->
                                </div>

                                <!--begin::Product List-->
                                <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                                    <div class="card-body py-0">
                                        <div class="table-responsive">
                                            <!--begin::Table-->
                                            <table
                                                class="table table-striped table-bordered align-middle table-row-dashed fs-6 gy-5 mb-0">
                                                <!--begin::Table head-->
                                                <thead>
                                                    <tr class="text-start text-uppercase text-center">
                                                        <th class="min-w-50px">SL</th>
                                                        <th class="min-w-175px">Product ID</th>
                                                        <th class="min-w-175px">Product Name</th>
                                                        <th class="min-w-70px">Unit</th>
                                                        <th class="min-w-70px">Quantity</th>
                                                        <th class="min-w-100px">Unit Price</th>
                                                        {{-- <th class="min-w-100px">Discount</th> --}}
                                                        <th class="min-w-100px">Total (BDT)</th>
                                                    </tr>
                                                </thead>
                                                <!--end::Table head-->
                                                <!--begin::Table body-->

                                                <tbody class="fw-semibold text-gray-600">

                                                    <!--begin::Products-->
                                                    <span class="d-none">{{ $initialTotal = 0 }}</span>
                                                    @foreach ($damage_products as $damage_product)
                                                        <tr class="text-center">
                                                            <td class="">{{ $loop->iteration }}</td>
                                                            <td class="">{{ $damage_product->product_id }}</td>
                                                            <td class="text-start">{{ $damage_product->product->product_name }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $damage_product->product->unit->unit_name }}</td>
                                                            <td class="">{{ $damage_product->damage_quantity }}</td>
                                                            <td class="text-end">
                                                                {{ formatCurrency($damage_product->purchasePrice) }}</td>
                                                            {{-- <td class="text-end">
                                                                {{ formatCurrency($damage_product->stock_out_discount) }}</td> --}}
                                                            <td class="text-end">
                                                                {{ formatCurrency($productTotal = $damage_product->purchasePrice*$damage_product->damage_quantity) }}
                                                            </td>
                                                            <span class="d-none">{{ $initialTotal = $initialTotal + $productTotal }}</span>
                                                        </tr>
                                                    @endforeach
                                                    <!--end::Products-->

                                                    <!--begin::Subtotal-->

                                                    <tr class="">
                                                        <td colspan="6" class="fs-5 text-dark text-end ">Total
                                                        </td>
                                                        <td colspan="6" class="fs-5 text-dark text-end">
                                                            {{ formatCurrency($initialTotal) }}</td>
                                                    </tr>
                                                    
                                                    <!--end::Grand total-->
                                                </tbody>
                                                <!--end::Table head-->
                                                
                                            </table>
                                            <!--end::Table-->

                                        </div>








                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Product List-->




                            </div>
                            <!--end::Orders-->
                        </div>
                        <!--end::Tab pane-->
                    </div>
                    <!--end::Tab content-->
                </div>
                <!--end::Order details page-->
            </div>
            <!--end::Content container-->
        </div>
    @endisset
</x-default-layout>
