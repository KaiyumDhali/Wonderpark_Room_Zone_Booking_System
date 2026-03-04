<x-default-layout>
    <style>
    .card .card-header {
        min-height: 40px;
    }

    .table> :not(caption)>*>* {
        padding: 0.3rem !important;
    }
    </style>
   
    @if($stocks)
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
                                <div class="card card-flush flex-row-fluid overflow-hidden pt-3" style="text-align: left">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Customer Details </h2>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body py-0 pt-2">
                                        <b>
                                            Customer ID : {{$stocks[0]->customer_finance_account->id}}
                                            <br>Name : {{$stocks[0]->customer_finance_account->account_name}}
                                            <br>Address : {{$stocks[0]->customer_finance_account->account_address}}
                                            <br>Mobile No : {{$stocks[0]->customer_finance_account->account_mobile}}
                                        </b>
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Payment address-->
                                <!--begin::Shipping address-->
                                <div class="card card-flush py-0 flex-row-fluid overflow-hidden"
                                     style="text-align: right">
                                    <!--begin::Card body-->
                                    <div class="card-body pt-15">
                                        {{-- <div class="form-group col-2">
                                        </div> --}}
                                        {{-- <a href="{{ route('sales_invoice_details_pdf',$stocks[0]->invoice_no) }}" class="btn btn-sm btn-primary px-5 my-3" target="_blank">{{ __('PDF Download') }}</a> --}}
                                        <h5> Invoice No (#{{$stocks[0]->invoice_no}}) </h5>
                                        <h5> Delivery Challan No (#{{$stocks[0]->delivery_challan_no}}) </h5>
                                        <h5>Invoice Date : {{$stocks[0]->stock_date}}</h5>
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
                                        <table class="table table-striped table-bordered align-middle table-row-dashed fs-6 gy-5 mb-0">
                                            <!--begin::Table head-->
                                            <thead>
                                                <tr class="text-start text-uppercase text-center">
                                                    <th class="min-w-50px">SL</th>
                                                    <th class="min-w-175px">Product ID</th>
                                                    <th class="min-w-175px">Product Name</th>
                                                    <th class="min-w-70px">Unit</th>
                                                    <th class="min-w-70px">Quantity</th>
                                                    <th class="min-w-100px">Purchase Price</th>
                                                    <th class="min-w-100px">Total Purchase</th>
                                                    <th class="min-w-100px">Sales Price</th>
                                                    <th class="min-w-100px">Discount</th>
                                                    <th class="min-w-100px">Total Sales</th>
                                                    <th class="min-w-100px">Profit</th>
                                                </tr>
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody class="fw-semibold text-gray-600">
                                                <!--begin::Products-->
                                            <span class="d-none">{{$initialTotal = 0,}}</span>
                                                @foreach($stocks as $stock)
                                                @php
                                                    $total_purchase_price = ($stock->stock_out_quantity * $stock->purchase_price);
                                                    $profit = $stock->stock_out_total_amount - $total_purchase_price;
                                                @endphp
                                            <tr class="text-center">
                                                <td class="">{{$loop->iteration}}</td>
                                                <td class="">{{$stock->product_id}}</td>
                                                <td class="text-start">{{$stock->product->product_name}}</td>
                                                <td class="text-center">{{$stock->product->unit->unit_name}}</td>
                                                <td class="">{{$stock->stock_out_quantity}}</td>
                                                <td class="text-end">
                                                        {{formatCurrency($stock->purchase_price)}}</td>
                                                <td class="text-end">
                                                        {{formatCurrency($total_purchase_price)}}</td>
                                                <td class="text-end">
                                                        {{formatCurrency($stock->stock_out_unit_price)}}</td>
                                                <td class="text-end">
                                                        {{ formatCurrency($stock->stock_out_discount) }}</td>
                                                <td class="text-end">
                                                        {{ formatCurrency($stock->stock_out_total_amount) }}
                                                </td>
                                                <td class="text-end">
                                                        {{ formatCurrency($productTotal = $profit) }}
                                                </td>
                                            <span
                                                class="d-none">{{ $initialTotal = $initialTotal + $productTotal, }}</span>
                                            </tr>
                                                @endforeach
                                            <!--end::Products-->
                                            <!--begin::Subtotal-->
                                            <tr class="">
                                                <td colspan="10" class="fs-5 text-dark text-end ">Net Profit</td>
                                                <td  class="fs-5 text-dark text-end">
                                                        {{ formatCurrency($initialTotal) }}</td>
                                            </tr>
                                            <!--end::Subtotal-->
                                            
                                            <!--end::Grand total-->
                                            </tbody>
                                            <!--end::Table head-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <div class="pt-5 mt-5" style="font-size: 16px">
                                        <p><b>In Word:</b> {{ numberToWord($initialTotal) }}</p>
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