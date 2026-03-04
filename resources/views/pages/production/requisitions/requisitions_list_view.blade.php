<x-default-layout>
    <style>
        .card .card-header {
            min-height: 40px;
        }

        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }
    </style>

    <style>
        .dataTables_filter {
            float: right;
        }

        .dataTables_buttons {
            float: left;
        }

        .bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
    </style>

    <div class="container-fluid">
        <!-- Page Heading -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    @if ($productionIn)
        <div class="row p-5 pt-0">
            <!-- Produceable Products Content -->
            <div class="col-md-12 px-5">
                <div class="row px-5">
                    <div class="col-md-12 card shadow">
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
                                                <th>Unit</th>
                                                <th>Quantity</th>
                                                <th>Unit Price (BDT)</th>
                                                <th>Total Price (BDT)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600">
                                            @foreach ($productionIn as $stock)
                                                <tr>
                                                    <td class="text-center">{{ $stock->product_id }}</td>
                                                    <td class="text-left">{{ $stock->product->product_name }}</td>
                                                    <td class="text-center">{{ $stock->product->unit->unit_name }}</td>
                                                    <td class="text-center">{{ $stock->stock_in_quantity }}</td>
                                                    <td class="text-end">{{ $stock->product->purchase_price }}</td>
                                                    <td class="text-end">
                                                        {{ number_format($stock->product->purchase_price * $stock->stock_in_quantity, 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6 px-0 mx-0" style="text-align: right">

                                    <a href="{{ route('requisition_invoice_report_pdf', $productionIn[0]->invoice_no) }}"
                                        class="btn btn-sm btn-primary px-5 my-3" style=""
                                        target="_blank">{{ __('PDF Download') }}</a>

                                    <h4 class="mb-2"> Requisition No (#{{ $productionIn[0]->invoice_no }}) </h4>
                                    <p class="mb-0">
                                        Requisition Date : {{ $productionIn[0]->stock_date }}
                                        <br>
                                        Requisition By : {{ $productionIn[0]->done_by }}
                                    </p>
                                    @php
                                        $status = $productionIn[0]->status;
                                        $approvedBy = $status == 0 ? 'Pending' : ($status == 1 ? 'Production Manager' : ($status == 2 ? 'Production Manager and CEO' : ($status == 3 ? 'Production Manager, CEO, and Store Incharge' : '')));
                                    @endphp
                                    @if ($status >= 0 && $status <= 3)
                                        <p class="mt-0 mb-2">Approved By : {{ $approvedBy }}</p>
                                    @endif

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
                                    <th>Material ID</th>
                                    <th>Material Name</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                    <th>Unit Cost (BDT)</th>
                                    <th>Total Cost (BDT)</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @php
                                    $consumeOut_total = 0;
                                @endphp

                                @foreach ($consumeOut as $stock)
                                    <tr>
                                        <td class="text-center">{{ $stock->product_id }}</td>
                                        <td class="text-left">{{ $stock->product->product_name }}</td>
                                        <td class="text-center">{{ $stock->product->unit->unit_name }}</td>
                                        <td class="text-center">{{ $stock->total_quantity }}</td>
                                        <td class="text-end">{{ $stock->product->purchase_price }}</td>
                                        <td class="text-end">
                                            {{-- {{ number_format($consumeOut_sum_total=$stock->product->purchase_price*$stock->stock_out_quantity, 2)}} --}}
                                            {{ number_format($consumeOut_sum_total = $stock->total_price, 2) }}
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

            <div class="d-flex justify-content-end">
                @php
                    $status = $productionIn[0]->status;
                    $statusValueApproved = $status + 1;
                @endphp
                <a class="btn btn-sm btn-primary px-5 mt-5 me-5" href="{{ route('production.requisition_list') }}">Back Requisition List</a>
                @if ($approvedIndex == $statusValueApproved)
                    <a class="btn btn-sm btn-success px-5 mt-5 me-5 approve-btn" id="requisitionApproved" data-status-approved-url="{{ route('requisition_invoice_report_status_approved', [$invoiceNo, $statusValueApproved]) }}" href="#">Approved Requisition</a>
                @endif
            </div>
        </div>
    @endisset


</x-default-layout>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        // Add event listener for approve button with SweetAlert2
        document.getElementById('requisitionApproved').addEventListener('click', function (e) {
            e.preventDefault();
            let url = this.getAttribute('data-status-approved-url');

            // Show SweetAlert2 confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to approve?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the approval via AJAX
                    $.ajax({
                        url: url,
                        type: "GET",
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            // Show success message
                            Swal.fire({
                                title: 'Approved!',
                                text: 'The requisition has been successfully approved.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Reload the page
                                window.location.reload();
                            });
                        },
                        error: function (xhr, status, error) {
                            console.log(xhr.responseText);
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred while approving the requisition.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    });
</script>


