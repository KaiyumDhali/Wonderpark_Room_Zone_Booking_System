<x-default-layout>
    <style>
    .card .card-header {
        min-height: 40px;
    }

    .table> :not(caption)>*>* {
        padding: 0.3rem !important;
    }
    </style>
    <div class="container-fluid">
        <!-- Page Heading -->
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form id="" method="POST" action="{{ route('finalproduction_report_search') }}" enctype="multipart/form-data">
            @csrf
            <div class="row p-5">
                <!-- Content Row -->
                <div class="col-12 p-5 ">
                    <div class="row">
                        <div class="col-md-12 card shadow">
                            <div class="card-header py-0">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __('Requisition Report') }}</h1>
                                </div>
                                {{-- <label class="badge text-warning" id="jsdataerror"></label> --}}
                            </div>
                            <div class="card-body py-3">
                                <div class="col-md-12 pb-3">
                                    <div class="row">
                                        <div class="form-group col-3">
                                            <label for="order">{{ __('Start Date') }}</label>
                                            <input id="start_date" type="date"
                                                   value="{{ date('Y-m-d', strtotime($startDate)) }}"
                                                   class="form-control form-control-sm form-control-solid" id=""
                                                   name="start_date" />
                                        </div>
                                        <div class="form-group col-3">
                                            <label for="order">{{ __('End Date') }}</label>
                                            <input id="end_date" type="date"
                                                   value="{{ date('Y-m-d', strtotime($endDate)) }}"
                                                   class="form-control form-control-sm form-control-solid" id=""
                                                   name="end_date" />
                                        </div>
                                        <div class="form-group col-3">
                                            <label>{{ __(' Invoice No') }}</label>
                                            <select class="form-select form-select-sm" data-control="select2"
                                                id="invoice_list" name="invoice_list" required>
                                                <option selected disabled>Select Invoice</option>
                                                @foreach($invoices as $invoice)
                                                <option value="{{ $invoice[0]->invoice_no }}">
                                                    {{ $invoice[0]->invoice_no}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-3">
                                            <button type="submit" id="searchBtn"
                                                    class="btn btn-sm btn-primary px-5 mt-5">{{ __('Search') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>


    <div class="row p-5 pt-0">
        <div class="col-md-12 px-5">
    {{-- @php
$invoice_no=$productionIn[0]->invoice_no
//        echo '<pre>';
//        print_r($invoice_no);
//        echo '</pre>';
//        die();
    @endphp --}}

            {{-- <a href="{{ route('finalproduction_report_search_pdf',$productionIn[0]->invoice_no) }}" class="btn btn-sm btn-primary px-5 my-3" style="" target="_blank">{{ __('PDF Download') }}</a> --}}
        </div>
    </div>



    @if($productionIn)
    <div class="row p-5 pt-0">

        <!-- Produceable Products Content -->
        <div class="col-md-12 px-5">
            <div class="row px-5">
                <div class="col-md-12 card shadow">
                    <!-- <div class="card-header">
                        <div class="d-sm-flex align-items-center justify-content-between">
                            <h1 class="h3 mb-0 text-gray-800">{{ __('Final Production Pannel') }}</h1>
                        </div>
                        <label class="badge text-warning" id="jsdataerror"></label>
                    </div> -->

                    <div class="card-body py-3">
                        <div class="row">
                            <div class="col-md-6 px-0 mx-0">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h1 class="h3 mb-0 text-gray-800">{{ __(' Product to be Produced ') }}</h1>
                                </div>
                                <table class="table table-striped table-bordered mt-4">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Product ID</th>
                                            <th>Product Name</th>
                                            <th>Product Unit</th>
                                            <th>Quantity</th>
                                            <th>Per Unit Price (BDT)</th>
                                            <th>Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        @foreach($productionIn as $stock)

                                        <tr>
                                            <td class="text-center">{{$stock->product_id}}</td>
                                            <td class="text-left">{{$stock->product->product_name}}</td>
                                            <td class="text-center">{{$stock->product->unit->unit_name}}</td>
                                            <td class="text-center">{{$stock->stock_in_quantity}}</td>
                                            <td class="text-end">{{$stock->product->purchase_price}}</td>
                                            <td class="text-end">
                                                {{ number_format($stock->product->purchase_price*$stock->stock_in_quantity, 2)}}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6 py-5 px-0 mx-0" style="text-align: right">

                                <a href="{{ route('finalproduction_report_search_pdf',$productionIn[0]->invoice_no) }}" class="btn btn-sm btn-primary px-5 my-3" style="" target="_blank">{{ __('PDF Download') }}</a>

                                <h4 class="mb-0"> Requisition No (#{{$productionIn[0]->invoice_no}}) </h4>
                                <br>
                                <p>Date : {{$productionIn[0]->stock_date}}</p>
                                <!-- <br>Prepared By : admin -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Consumable Products Content -->
        <div class="col-md-12 p-5">
            <div class="row px-5">
                <div class="col-md-12 card shadow px-5">
                    <div class="d-sm-flex align-items-center justify-content-between pt-3">
                        <h1 class="h3 mb-0 text-gray-800">{{ __(' Requisition items ') }}</h1>
                    </div>
                    <table class="table table-striped table-bordered mt-4">
                        <thead>
                            <tr class="text-center">
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Product Unit</th>
                                <th>Per Unit Price (BDT)</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @php
                            $consumeOut_total = 0;
                            @endphp

                            @foreach($consumeOut as $stock)
                            <tr>
                                <td class="text-center">{{$stock->product_id}}</td>
                                <td class="text-left">{{$stock->product->product_name}}</td>
                                <td class="text-center">{{$stock->total_quantity}}</td>
                                <td class="text-center">{{$stock->product->unit->unit_name}}</td>
                                <td class="text-end">{{$stock->product->purchase_price}}</td>
                                <td class="text-end">
                                    {{--{{ number_format($consumeOut_sum_total=$stock->product->purchase_price*$stock->stock_out_quantity, 2)}}--}}
                                    {{number_format($consumeOut_sum_total=$stock->total_price, 2)}}
                                </td>
                            </tr>
                            @php
                            $consumeOut_total += $consumeOut_sum_total;
                            @endphp
                            @endforeach

                        </tbody>
                        <tfooter>
                            <tr>
                                <th colspan="5" class="fs-5 text-dark text-end">Total Requisition Price</th>
                                <th class="text-end"> {{ number_format($consumeOut_total, 2) }}</th>
                            </tr>
                        </tfooter>
                    </table>
                </div>
            </div>
        </div>
        <!-- Wastage Products Content -->
        {{-- <div class="col-md-6 p-5">
            <div class="row px-5">
                <div class="col-md-12 card shadow px-5">
                    <div class="d-sm-flex align-items-center justify-content-between pt-3">
                        <h1 class="h3 mb-0 text-gray-800">{{ __('By Product / Wastage Product') }}</h1>
                    </div>
                    <table class="table table-striped table-bordered text-center mt-4">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Product Unit</th>
                                <th>Quantity</th>
                                <th>Per Unit Price</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @php
                            $total = 0;
                            @endphp

                            @foreach($byProductIn as $stock)
                            <tr>
                                <td class="">{{$stock->product_id}}</td>
                                <td class="text-start">{{$stock->product->product_name}}</td>
                                <td class="">{{$stock->product->unit->unit_name}}</td>
                                <td class="">{{$stock->stock_in_quantity}}</td>
                                <td class="text-end">{{$stock->product->purchase_price}}</td>
                                <td class="text-end">
                                    {{ number_format($sum_total=$stock->product->purchase_price*$stock->stock_in_quantity, 2)}}
                                </td>
                            </tr>
                            @php
                            $total += $sum_total;
                            @endphp
                            @endforeach
                            <!-- <p>Total: {{ number_format($total, 2) }}</p> -->
                        </tbody>
                        <tfooter>
                            <tr>
                                <th colspan="5" class="fs-5 text-dark text-end">Total Wastage Price</th>
                                <th class="text-end"> {{ number_format($total, 2) }}</th>
                            </tr>
                        </tfooter>
                    </table>
                </div>
            </div>
        </div> --}}
    </div>
    @endisset
    
    
</x-default-layout>

<script type="text/javascript">
$("#searchBtn").prop('disabled', true);
$('#invoice_list').on('change', function() {
    $("#searchBtn").prop('disabled', false);
});

$('#start_date').on('change', function() {
    $("#invoice_list").empty();

    var startDate = $(this).val();
    var endDate = $('#end_date').val();

    const start_date = new Date(startDate);
    const end_date = new Date(endDate);

    // Check if the start date is after the end date
    if (start_date > end_date) {
        alert('The start date cannot be after the end date.');
        $('#start_date').val(endDate);
        startDate = $('#start_date').val();
    }

    // console.log(startDate);
    // console.log(endDate);
    var url = '{{route("invoice_date_search",["startDate" => ":startDate", "endDate" => ":endDate"])}}';
    // console.log(url);
    url = url.replace(':startDate', startDate);
    url = url.replace(':endDate', endDate);

    if (endDate) {
        $.ajax({
            url: url,
            type: "GET",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(data) {
                $('#invoice_list').append(
                    `<option selected disabled>Select Invoice</option>`
                );
                console.log(data);
                if (data) {
                    $('#jsdataerror').text('');
                    $.each(data, function(key, value) {
                        $('#invoice_list').append(
                            `<option value="` + value[0].invoice_no + `">` + value[0]
                            .invoice_no +
                            `</option>`
                        );
                    });
                } else {
                    $('#jsdataerror').text('Not Found');
                }
            }
        });
    } else {
        $('#jsdataerror').text('Not Found');
    }
});

$('#end_date').on('change', function() {
    $("#invoice_list").empty();

    var endDate = $(this).val();
    var startDate = $('#start_date').val();

    const start_date = new Date(startDate);
    const end_date = new Date(endDate);

    // Check if the start date is after the end date
    if (start_date > end_date) {
        alert('The end date cannot be before the start date.');
        $('#end_date').val(startDate);
        endDate = $('#start_date').val();
    }

    // console.log(startDate);
    // console.log(endDate);
    var url = '{{route("invoice_date_search",["startDate" => ":startDate", "endDate" => ":endDate"])}}';
    // console.log(url);
    url = url.replace(':startDate', startDate);
    url = url.replace(':endDate', endDate);

    if (endDate) {
        $.ajax({
            url: url,
            type: "GET",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(data) {
                $('#invoice_list').append(
                    `<option selected disabled>Select Invoice</option>`
                );
                console.log(data);
                if (data) {
                    $('#jsdataerror').text('');
                    $.each(data, function(key, value) {
                        $('#invoice_list').append(
                            `<option value="` + value[0].invoice_no + `">` + value[0]
                            .invoice_no +
                            `</option>`
                        );
                    });
                } else {
                    $('#jsdataerror').text('Not Found');
                }
            }
        });
    } else {
        $('#jsdataerror').text('Not Found');
    }
});

// $('#end_date').on('change', function() {
//     var endDate = $(this).val();
//     var startDate = $('#start_date').val();

//     if(endDate) {
//         $.ajax({
//             url: 'invoice_date_search/' + startDate + endDate,
//             type: "GET",
//             data: {
//                 "_token": "{{ csrf_token() }}"
//             },
//             dataType: "json",
//             success: function(data) {

//                 //data= null;
//                 if (data) {
//                     $('#jsdataerror').text('');
//                     $.each(data, function(key, value) {
//                         var supplier_name = value.supplier_name;
//                         var supplier_mobile = value.supplier_mobile;
//                         var due = value.is_previous_due;
//                         // $('#due_amount').val(due);

//                         $("#supplierAdd").empty();
//                         $('#supplierAdd').append(

//                             `<tr>
//                                 <th style="width:40%">Supplier Name:</th>
//                                 <td>` + supplier_name + `</td>
//                             </tr>
//                             <tr>
//                                 <th>Mobile Number:</th>
//                                 <td>` + supplier_mobile + `</td>
//                             </tr>
//                             <tr>
//                                 <th>Due Amount:</th>
//                                 <td>` + due + `<input type="hidden" name="table_supplier_due" value=`+ due +` ></td>
//                             </tr>`

//                         );
//                     });
//                 } else {
//                     $('#jsdataerror').text('Not Found');
//                 }
//             }
//         });
//     } else {
//         $('#jsdataerror').text('Not Found');
//     }
// });
</script>