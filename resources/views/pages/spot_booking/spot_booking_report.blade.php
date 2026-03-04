<x-default-layout>
<div class="app-container">

    <h3>Spot-wise Booking Report</h3>

    <form method="GET" class="mb-4">
    <div class="row g-2 align-items-end">
        
        <!-- Spot Select Dropdown -->
        <div class="col-md-3">
            <label class="form-label">Select Spot</label>
            <select name="spot_id" class="form-select">
                <option value="">All Spots</option>
                @foreach($spots as $spot)
                    <option value="{{ $spot->id }}" {{ ($spotId == $spot->id) ? 'selected' : '' }}>
                        {{ $spot->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Customer Select Dropdown -->
        <div class="col-md-3">
            <label class="form-label">Select Customer</label>
            <select name="customer_id" class="form-select">
                <option value="">All Customers</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->customer_name }}" {{ ($customerId == $customer->customer_name) ? 'selected' : '' }}>
                        {{ $customer->customer_name }} ({{ $customer->customer_mobile }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="form-control">
        </div>

        <div class="col-md-2 ">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
        </div>

        <div class="col-md-2 text-end">
            <button class="btn btn-primary">Filter</button>
        </div>
    </div>
</form>


    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Spot</th>
                <th>Booking Date</th>
                <th>Invoice</th>
                <th>Customer</th>
                <th>Mobile</th>
                <th>Total Persons</th>
                <th>Spot Total (৳)</th>
                <th>Package Total (৳)</th>
                <th>Service Total (৳)</th>
                <th>Spot Discount %</th>
                <th>Grand Total (৳)</th>
                <th>Paid Amount (৳)</th>
                <th>Due (৳)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($report as $r)
                <tr>
                    <td>{{ $spots->firstWhere('id', $r->spot_id)->title ?? $r->spot_id }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->booking_date)->format('d M Y') }}</td>
                    <td>{{ $r->invoice_number }}</td>
                    <td>{{ $r->customer_name }}</td>
                    <td>{{ $r->customer_mobile }}</td>
                    <td>{{ $r->total_persons }}</td>
                    <td class="text-end">{{ number_format($r->spot_total, 2) }}</td>
                    <td class="text-end">{{ number_format($r->package_total, 2) }}</td>
                    <td class="text-end">{{ number_format($r->service_total, 2) }}</td>
                    <td class="text-center">{{ $r->spot_discount_percent }}</td>
                    <td class="text-end">{{ number_format($r->grand_total, 2) }}</td>
                    <td class="text-end">{{ number_format($r->paid_amount, 2) }}</td>
                    <td class="text-end">{{ number_format($r->grand_total - $r->paid_amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</x-default-layout>
