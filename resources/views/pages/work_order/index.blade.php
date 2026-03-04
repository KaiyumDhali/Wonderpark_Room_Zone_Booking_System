<x-default-layout>
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Work Orders</h3>

            <a href="{{ route('work-orders.create') }}" class="btn btn-primary">
                + New Work Order
            </a>
        </div>

        <div class="card">
            <div class="card-body">

                <table class="table table-bordered table-striped align-middle">
                    <thead style="background-color: #148FB8; color:white">
                        <tr>
                            <th>#</th>
                            <th>Work Order No</th>
                            <th>Client</th>
                            <th>Work Item(s)</th>
                            <th>Total Amount</th>
                            <th>Terms</th>
                            <th>Advance %</th>
                            <th>Date</th>
                            <th width="280" class="text-center">Action</th>
                        </tr>
                        </thead>


                    <tbody>
                        @forelse($workOrders as $key => $order)
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td><strong>{{ $order->work_order_no }}</strong></td>

                            <td>{{ $order->client->name ?? 'N/A' }}</td>

                            {{-- Work Item Names --}}
                            <td>
                                @php $total = 0; @endphp

                                @if(!empty($order->work_items))
                                    <ul class="mb-0 ps-5">
                                        @foreach($order->work_items as $item)
                                            @php
                                                $price = $item['price'] ?? 0;
                                                $total += $price;
                                            @endphp
                                            <li >
                                                {{ $item['description'] ?? 'N/A' }}
                                                <span class="text-muted">
                                                    ({{ number_format($price, 2) }})
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>

                            {{-- Total Price --}}
                            <td>
                                <strong>{{ number_format($total, 2) }}</strong>
                            </td>

                            {{-- Terms --}}
                            <td>
                                @if(!empty($order->terms))
                                    <ul class="mb-0 ps-5">
                                        @foreach($order->terms as $term)
                                            <li>{{ $term }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>

                            <td>{{ number_format($order->advance_percent, 2) }} %</td>

                            <td>{{ $order->created_at->format('d M Y') }}</td>

                            {{-- Actions --}}
                            <td class="text-center">
                                <a href="{{ route('work-orders.show', $order->id) }}"
                                class="btn btn-sm btn-info text-white" title="View">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <a href="{{ route('work-orders.edit', $order->id) }}"
                                class="btn btn-sm btn-warning text-white" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <a href="{{ route('work-orders.pdf', $order->id) }}"
                                class="btn btn-sm btn-secondary" target="_blank" title="PDF">
                                    <i class="fa fs-1 fa-file-pdf"></i>
                                </a>

                                <form action="{{ route('work-orders.destroy', $order->id) }}"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                No work orders found
                            </td>
                        </tr>
                        @endforelse
                        </tbody>

                </table>

            </div>
        </div>

    </div>
</x-default-layout>
