<!--begin::Table widget 14-->
<div class="card card-flush h-md-100">
    <!--begin::Header-->
    <div class="card-header pt-7">
        <!--begin::Title-->
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-800">Product Order List</span>
            {{-- <span class="text-gray-400 mt-1 fw-semibold fs-6">Updated 37 minutes ago</span> --}}
        </h3>
        <!--end::Title-->
        <!--begin::Toolbar-->
        <div class="card-toolbar">
            <a href="{{ route('order.index') }}" class="btn btn-sm btn-light-primary">View All History</a>
        </div>
        <!--end::Toolbar-->
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body pt-6">
        <!--begin::Table container-->
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-row-dashed align-middle gs-0 gy-3 my-0" id="product_order_report">
                <!--begin::Table head-->
                <thead>
                    <tr class="fs-7 fw-bold text-gray-400 border-bottom-0">
                        <th class="min-w-20px">ID</th>
                        <th class="min-w-140px">Customer Name</th>
                        <th class="min-w-70px">Order No</th>
                        <th class="min-w-20px">Order Date</th>
                        <th class="min-w-20px">Delivery Date</th>
                        {{-- <th class="min-w-20px">Amount</th> --}}
                        <th class="min-w-60px">Status</th>
                        <th class="min-w-60px">Done By</th>
                        <th class="text-end min-w-100px">VIEW</th>
                    </tr>
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody>
                    @foreach ($orderList as $productOrder)
                        <tr>
                            <td>
                                <a>{{ $productOrder->id }}</a>
                            </td>
                            <td>
                                <a class="badge badge-light-primary" style="font-size: 13px;">{{ $productOrder->account_name }}</a>
                            </td>
                            <td>{{ $productOrder->order_no }}</td>
                            <td>
                                <a>{{ $productOrder->order_date }}</a>
                            </td>
                            <td>
                                <a>{{ $productOrder->delivery_date }}</a>
                            </td>
                            {{-- <td>
                                <a class="badge badge-light-primary">{{ $productOrder->orderdetail_sum_stock_out_total_amount }}</a>
                            </td> --}}

                            <td>
                                <div class="badge badge-light-{{ $productOrder->status == 1 ? 'success' : 'danger' }}">
                                    {{ $productOrder->status == 1 ? 'Approved' : 'Pending' }}
                                </div>
                            </td>

                            <td>
                                <a class="badge badge-light-warning ">{{ $productOrder->done_by }}</a>
                            </td>

                            <td class="text-end">
								<a href="{{ route('order.show', $productOrder->id) }}"
									class="btn btn-sm btn-info hover-scale p-2" data-bs-toggle="tooltip"
									data-bs-placement="top" title="Show" target="_blank">
									View <i class="bi bi-eye fs-2 ms-1"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <!--end::Table body-->
            </table>
        </div>
        <!--end::Table-->
    </div>
    <!--end: Card Body-->
</div>
<!--end::Table widget 14-->
{{-- @push('scripts')
    <script type="text/javascript">
        var name = 'Perfume PLC';
        name += `\n Product Order Report`;
        var reportTittle = name;

        var orderDataTableOptions = {
            dom: '<"top"f>rt<"bottom"lip>', // Removed the 'B' for buttons replase fB only use f
            
            paging: true,
            ordering: false,
            searching: false,
            responsive: false,
            lengthMenu: [10, 25, 50, 100],
            pageLength: 10,
            // buttons: false, // Kept this to ensure no buttons are shown
            language: {
                lengthMenu: 'Show _MENU_ entries',
                search: 'Search:',
                paginate: {
                    first: 'First',
                    last: 'Last',
                    next: 'Next',
                    previous: 'Previous'
                }
            }
        };
        var table = $('#product_order_report').DataTable(orderDataTableOptions);

    </script>
@endpush --}}
