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
                                            <h2>Supplier Details </h2>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body py-0 pt-2">Supplier ID : {{$stocks[0]->supplier_finance_account->id}}
                                        <br>Name : {{$stocks[0]->supplier_finance_account->account_name}}
                                        <br>Address : {{$stocks[0]->supplier_finance_account->account_address}}
                                        <br>Mobile No : {{$stocks[0]->supplier_finance_account->account_mobile}}
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Payment address-->
                                <!--begin::Shipping address-->
                                <div class="card card-flush py-0 flex-row-fluid overflow-hidden"
                                     style="text-align: right">
                                    <!--begin::Card body-->
                                    <div class="card-body pt-5">
                                        <div class="form-group col-2">
                                        </div>
                                        <a href="{{ route('purchase_invoice_return_details_Pdf',$stocks[0]->invoice_no) }}" class="btn btn-sm btn-primary px-5 my-3" target="_blank">{{ __('PDF Download') }}</a>
                                        <h2 class="mb-0">Return Invoice No (#{{$stocks[0]->invoice_no}}) </h2>
                                        <br>Return Invoice Date : {{$stocks[0]->stock_date}}
                                        <!-- <br>Prepared By : admin  -->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Shipping address-->
                            </div>
                            <!--begin::Product List-->
                            <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                                <!--begin::Card header-->
                                <!-- <div class="card-header pb-4">
                                    <div class="card-title">
                                        <h2> Invoice #{{$stocks[0]->invoice_no}}</h2>
                                    </div>
                                </div> -->
                                <!--end::Card header-->
                                <!--begin::Card body-->
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
                                                    <th class="min-w-100px">Unit Price</th>
                                                    <th class="min-w-100px">Discount</th>
                                                    <th class="min-w-100px">Total (BDT)</th>
                                                </tr>
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody class="fw-semibold text-gray-600">
                                                <!--begin::Products-->
                                            <span class="d-none">{{$initialTotal = 0, $initialDiscount = 0}}</span>
                                                @foreach($stocks as $stock)
                                            <tr class="text-center">
                                                <td class="">{{$loop->iteration}}</td>
                                                <td class="">{{$stock->product_id}}</td>
                                                <td class="text-start">{{$stock->product->product_name}}</td>
                                                <td class="text-center">{{$stock->product->unit->unit_name}}</td>
                                                <td class="">{{$stock->stock_out_quantity}}</td>
                                                <td class="text-end">
                                                        {{formatCurrency($stock->stock_out_unit_price)}}</td>
                                                <td class="text-end">
                                                        {{ formatCurrency($stock->stock_out_discount) }}</td>
                                                <td class="text-end">
                                                        {{ formatCurrency($productTotal =($stock->stock_out_total_amount)) }}
                                                </td>
                                            <span
                                                class="d-none">{{ $initialTotal = $initialTotal + $productTotal, $initialDiscount = $initialDiscount + $stock->stock_out_discount }}</span>
                                            </tr>
                                                @endforeach
                                            <!--end::Products-->
                                            <!--begin::Subtotal-->
                                            <tr class="">
                                                <td colspan="7" class="fs-5 text-dark text-end ">Return Amount</td>
                                                <td colspan="7" class="fs-5 text-dark text-end">
                                                        {{ formatCurrency($initialTotal) }}</td>
                                            </tr>
                                            <!--end::Subtotal-->
                                            <!--begin::VAT-->
                                            {{-- <tr>
                                                <td colspan="7" class="fs-5 text-dark text-end">INV Discount</td>
                                                <td colspan="7" class="fs-5 text-dark text-end">0.00</td>
                                            </tr>
                                            <!--end::VAT-->
                                            <!--begin::Grand total-->
                                            <tr>
                                                <td colspan="7" class="fs-4 text-dark text-end">Net Return Amount</td>
                                                <td colspan="7" class="text-dark fs-4 fw-bolder text-end">
                                                        {{ formatCurrency($initialTotal)}}</td>
                                            </tr> --}}
                                            {{-- <tr>
                                                <td colspan="7" class="fs-4 text-dark text-end">Paid Amount</td>
                                                <td colspan="7" class="text-dark fs-4 fw-bolder text-end">
                                                        {{ formatCurrency($supplierPayment) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="7" class="fs-4 text-dark text-end">Due Amount</td>
                                                <td colspan="7" class="text-dark fs-4 fw-bolder text-end">
                                                        {{ formatCurrency($initialTotal - $supplierPayment) }}
                                                </td>
                                            </tr> --}}
                                            <!--end::Grand total-->
                                            </tbody>
                                            <!--end::Table head-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <div class="pt-5 mt-5" style="font-size: 16px">
                                        <p><b>In Word:</b> {{ numberToWord($initialTotal) }}</p>
                                        @if ($stock->remarks)
                                            <p><b>Remarks:</b> {{ $stock->remarks }}</p>
                                        @endif
                                        {{-- @if ($paymentNarration)
                                            <p><b>Narration:</b> {{ $paymentNarration }}</p>
                                        @else
                                            @if ($stock->remarks)
                                                <p><b>Remarks:</b> {{ $stock->remarks }}</p>
                                            @endif
                                        @endif --}}
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