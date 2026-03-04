<x-default-layout>
<div class="app-content flex-column-fluid">
    <div class="app-container">

        <form method="POST" action="{{ route('booking.store') }}">
        @csrf

        <div class="row">
            {{-- Left Column: Room Details --}}
            <div class="col-md-7">
                <div class="card mb-4 p-4 shadow-sm border">
                    <h5 class="mb-3">Room Details</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Room</th>
                                <th>Date</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(request()->get('rooms', []) as $roomId => $info)
                            <input type="hidden" name="rooms[{{ $roomId }}][id]" value="{{ $roomId }}">
                            <input type="hidden" name="rooms[{{ $roomId }}][price_per_night]" value="{{ $info['price'] }}" class="room-price">

                            <tr>
                                <td>Room {{ $roomId }}</td>
                                <td>
                                    <input type="date" name="rooms[{{ $roomId }}][date]" class="form-control form-control-sm" value="{{ $info['date'] }}">
                                </td>
                                <td>
                                    <input type="time" name="rooms[{{ $roomId }}][start]" class="form-control form-control-sm" value="{{ $info['start'] }}">
                                </td>
                                <td>
                                    <input type="time" name="rooms[{{ $roomId }}][end]" class="form-control form-control-sm" value="{{ $info['end'] }}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" value="{{ $info['price'] }}" readonly>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Right Column: Customer Info + Payment Summary --}}
            <div class="col-md-5">
                {{-- Customer Info --}}
                <div class="card mb-4 p-4 shadow-sm border">
                    <h5 class="mb-3">Customer Information</h5>
                    <div class="mb-3">
                        <label class="form-label">Customer Name</label>
                        <input type="text" name="customer_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Customer Address</label>
                        <input type="text" name="customer_address" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mobile</label>
                        <input type="text" name="customer_mobile" class="form-control form-control-sm" required>
                    </div>
                </div>

                {{-- Payment Summary --}}
                <div class="card mb-4 p-4 shadow-sm border">
                    <h5 class="mb-3">Payment Summary</h5>
                    <div class="mb-3">
                        <label>Net Total</label>
                        <input type="text" id="net_total" class="form-control form-control-sm" readonly value="0">
                    </div>
                    <div class="mb-3">
                        <label>Discount</label>
                        <input type="number" id="total_discount" name="total_discount" class="form-control form-control-sm" value="0" min="0">
                    </div>
                   <div class="mb-3">
    <label>After Discount</label>
    <input type="text" id="after_discount" name="after_discount" class="form-control form-control-sm" readonly value="0">
</div>

                    <div class="mb-3">
                        <label>Paid</label>
                        <input type="number" id="total_paid" name="total_paid" class="form-control form-control-sm" value="0" min="0">
                    </div>
                    <div class="mb-3">
                        <label>Due</label>
                        <input type="text" id="due_amount" class="form-control form-control-sm" readonly value="0">
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="text-end mb-5">
            <button type="submit" class="btn btn-primary btn-sm">Confirm Booking</button>
        </div>

        </form>
    </div>
</div>

{{-- JS for summary calculations --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    function calculateSummary() {
        let netTotal = 0;

        document.querySelectorAll('.room-price').forEach(el => {
            let val = parseFloat(el.value);
            if (!isNaN(val)) netTotal += val;
        });

        let discount = parseFloat(document.getElementById('total_discount').value) || 0;
        let paid = parseFloat(document.getElementById('total_paid').value) || 0;

        let afterDiscount = netTotal - discount;
        let due = afterDiscount - paid;

        document.getElementById('net_total').value = netTotal.toFixed(2);
        document.getElementById('after_discount').value = afterDiscount.toFixed(2);
        document.getElementById('due_amount').value = due.toFixed(2);
    }

    document.getElementById('total_discount').addEventListener('input', calculateSummary);
    document.getElementById('total_paid').addEventListener('input', calculateSummary);

    calculateSummary();
});
</script>


</x-default-layout>
