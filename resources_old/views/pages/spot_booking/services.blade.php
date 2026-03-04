<x-default-layout>

    <div class="app-container mt-6">

        <h3 class="mb-4">Modify Additional Services</h3>

        <form action="{{ route('spot-bookings.services.save', $booking->id) }}" method="POST">
            @csrf

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th width="120">Price</th>
                        <th width="120">Quantity</th>
                        <th width="120">Total</th>
                    </tr>
                </thead>
                <tbody id="serviceTable">
                    @foreach ($services as $index => $service)
                        <tr>
                            <td>{{ $service['title'] }}</td>
                            <td>৳ <span class="unitPrice">{{ $service['price'] }}</span></td>
                            <td>
                                <input type="number" min="1" value="1" class="form-control quantity"
                                    data-index="{{ $index }}" data-price="{{ $service['price'] }}">
                            </td>
                            <td class="lineTotal">৳ {{ $service['price'] }}</td>
                        </tr>

                        {{-- Hidden inputs for backend --}}
                        <input type="hidden" name="services[{{ $index }}][service_id]"
                            value="{{ $service['service_id'] }}">
                        <input type="hidden" name="services[{{ $index }}][price]"
                            value="{{ $service['price'] }}">
                        <input type="hidden" name="services[{{ $index }}][quantity]" value="1"
                            class="qty-input">
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <h4>Total: ৳ <span id="grandTotal">{{ $total }}</span></h4>
                <button type="submit" class="btn btn-success">Confirm Booking</button>
            </div>

        </form>
    </div>

    <script>
        let baseTotal = {{ $total }};

        function calculateGrandTotal() {
            let total = baseTotal;

            document.querySelectorAll('.quantity').forEach((input, i) => {
                let price = parseFloat(input.dataset.price);
                let qty = parseInt(input.value || 1);

                // Update line total
                let row = input.closest('tr');
                row.querySelector('.lineTotal').innerText = '৳ ' + (price * qty);

                // Update hidden input
                document.querySelectorAll('.qty-input')[i].value = qty;

                total += price * (qty - 1); // subtract 1 because baseTotal already includes 1 unit each
            });

            document.getElementById('grandTotal').innerText = total;
        }

        // Event listeners for quantity inputs
        document.querySelectorAll('.quantity').forEach(input => {
            input.addEventListener('input', calculateGrandTotal);
        });

        // Initialize totals on page load
        window.addEventListener('DOMContentLoaded', calculateGrandTotal);
    </script>

</x-default-layout>
