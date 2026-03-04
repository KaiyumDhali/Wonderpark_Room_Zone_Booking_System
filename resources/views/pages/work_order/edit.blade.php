<x-default-layout>
<div class="container">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-semibold">Edit Work Order</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('work-orders.update', $workOrder->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Client + Work Order No + Delivery Date --}}
                <div class="col-md-12 d-flex">

                    <div class="mb-4 col-md-6 me-5">
                        <label class="form-label fw-semibold">Client</label>
                        <select name="client_id" class="form-control" required>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                    {{ $workOrder->client_id == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4 col-md-3 me-5">
                        <label class="form-label fw-semibold">Work Order No</label>
                        <input type="text"
                               class="form-control bg-light"
                               value="{{ $workOrder->work_order_no }}"
                               readonly>
                    </div>

<div class="mb-4 col-md-3 pe-10">
    <label class="form-label fw-semibold">Delivery Date</label>
    <input type="date"
           name="delivery_date"
           class="form-control"
           value="{{ \Carbon\Carbon::parse($workOrder->delivery_date)->format('Y-m-d') }}"
           required>
</div>

                </div>

                {{-- Subject + Reference + Advance --}}
                <div class="col-md-12 d-flex">

                    <div class="mb-4 col-md-4 me-5">
                        <label class="form-label">Subject</label>
                        <input type="text"
                               name="subject"
                               class="form-control"
                               value="{{ $workOrder->subject }}"
                               required>
                    </div>

                    <div class="mb-4 col-md-4 me-5 ">
                        <label class="form-label">Reference (optional)</label>
                        <input type="text"
                               name="reference"
                               class="form-control"
                               value="{{ $workOrder->reference }}">
                    </div>

                    <div class="mb-4 col-md-4 pe-10">
                        <label class="form-label fw-semibold">Advance (%)</label>
                        <input type="number"
                               name="advance_percent"
                               class="form-control"
                               value="{{ $workOrder->advance_percent }}">
                    </div>
                </div>

                {{-- Work Items --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Work Description, Quantity & Price</label>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Description</th>
                                    <th width="300">পরিমাণ</th>
                                    <th width="150">Price</th>
                                    <th width="60"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workOrder->work_items as $index => $item)
                                <tr>
                                    <td>
                                        <input type="text"
                                               name="work_items[{{ $index }}][description]"
                                               class="form-control"
                                               value="{{ $item['description'] }}"
                                               required>
                                    </td>
                                    <td>
                                        <input type="text"
                                               name="work_items[{{ $index }}][quantity]"
                                               class="form-control"
                                               value="{{ $item['quantity'] }}"
                                               required>
                                    </td>
                                    <td>
                                        <input type="number"
                                               name="work_items[{{ $index }}][price]"
                                               class="form-control"
                                               value="{{ $item['price'] }}"
                                               required>
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                                class="btn btn-sm bg-danger text-white removeRow">×</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <button type="button"
                            class="btn btn-sm btn-outline-secondary bg-secondary"
                            id="addItem">
                        Add row
                    </button>
                </div>

                {{-- Terms --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Terms & Conditions</label>

                    <div id="termsWrapper">
                        @foreach($workOrder->terms as $term)
                        <div class="input-group mb-2">
                            <input type="text"
                                   name="terms[]"
                                   class="form-control"
                                   value="{{ $term }}"
                                   required>
                            <button type="button"
                                    class="btn btn-outline-danger bg-danger text-white removeTerm">×</button>
                        </div>
                        @endforeach
                    </div>

                    <button type="button"
                            class="btn btn-sm btn-outline-secondary bg-secondary"
                            id="addTerm">
                        Add term
                    </button>
                </div>

                {{-- Submit --}}
                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        Update
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
let itemIndex = {{ count($workOrder->work_items) }};

document.getElementById('addItem').addEventListener('click', function () {
    const row = `
    <tr>
        <td><input type="text" name="work_items[${itemIndex}][description]" class="form-control" required></td>
        <td><input type="text" name="work_items[${itemIndex}][quantity]" class="form-control" required></td>
        <td><input type="number" name="work_items[${itemIndex}][price]" class="form-control" required></td>
        <td class="text-center">
            <button type="button" class="btn btn-sm bg-danger text-white removeRow">×</button>
        </td>
    </tr>`;
    document.querySelector('#itemsTable tbody').insertAdjacentHTML('beforeend', row);
    itemIndex++;
});

document.getElementById('addTerm').addEventListener('click', function () {
    document.getElementById('termsWrapper').insertAdjacentHTML('beforeend', `
        <div class="input-group mb-2">
            <input type="text" name="terms[]" class="form-control" required>
            <button type="button" class="btn btn-outline-danger bg-danger text-white removeTerm">×</button>
        </div>
    `);
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('removeRow')) {
        e.target.closest('tr').remove();
    }
    if (e.target.classList.contains('removeTerm')) {
        e.target.closest('.input-group').remove();
    }
});
</script>
</x-default-layout>
