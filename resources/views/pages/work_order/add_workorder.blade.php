<x-default-layout>
<div class="container">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-semibold">Create Work Order</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('work-orders.store') }}" method="POST">
                @csrf

                {{-- Client --}}
                <div class="col-md-12 d-flex">
                <div class="mb-4 col-md-6 me-5">
                    <label class="form-label fw-semibold">Client</label>
                    <select name="client_id" class="form-control" required>
                        <option value="">-- Select Client --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Work Order No --}}
                <div class="mb-4 col-md-3 me-5">
                    <label class="form-label fw-semibold">Work Order No</label>
                    <input type="text"
                           class="form-control bg-light"
                           name="work_order_no"
                           value="{{ $workOrderNo }}"
                           readonly>
                </div>
                 
                    <div class="mb-4 col-md-3 pe-10">
                        <label class="form-label fw-semibold">Delivery Date</label>
                        <input 
                            type="date"
                            name="delivery_date"
                            class="form-control"
                            value="{{ old('delivery_date') }}"
                            required>
                    </div>
                </div>

                <div class="col-md-12 d-flex">
                    <div class="mb-4 col-md-4 me-5">
                        <label class="form-label">Subject</label>
                        <input 
                            type="text" 
                            name="subject" 
                            class="form-control" 
                            value="{{ old('subject') }}"
                            required>
                    </div>
                    <div class="mb-4 col-md-4 me-5">
                        <label class="form-label">If Any Reference</label>
                        <input 
                            type="text" 
                            name="reference" 
                            class="form-control" 
                            value="{{ old('reference') }}"
                            required>
                    </div>
                        {{-- Advance --}}
                    <div class="mb-4 col-md-4 pe-10">
                        <label class="form-label fw-semibold">Advance (%)</label>
                        <input type="number"
                            class="form-control"
                            name="advance_percent"
                            value="50">
                    </div>



                </div>
                {{-- Work Items --}}
                {{-- Work Items --}}
<div class="mb-4">
    <label class="form-label fw-semibold">Work Description, Quantity & Price</label>

    <div class="table-responsive">
        <table class="table table-sm align-middle" id="itemsTable">
            <thead class="table-light">
                <tr>
                    <th>Description</th>
                    <th width="420">পরিসংখ্যা</th>
                    <th width="150">Price</th>
                    <th width="60"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="text"
                               name="work_items[0][description]"
                               class="form-control"
                               required>
                    </td>
                    <td>
                        <input type="text"
                               name="work_items[0][quantity]"
                               class="form-control"
                               required>
                    </td>
                    <td>
                        <input type="number"
                               name="work_items[0][price]"
                               class="form-control"
                               min="0"
                               required>
                    </td>
                    <td class="text-center">
                        <button type="button"
                                class="btn btn-sm bg-danger text-white btn-outline-danger removeRow">
                            ×
                        </button>
                    </td>
                </tr>
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
                        <div class="input-group mb-2">
                            <input type="text"
                                   name="terms[]"
                                   class="form-control"
                                   required>
                            <button type="button"
                                    class="btn btn-outline-danger removeTerm text-white bg-danger">
                                ×
                            </button>
                        </div>
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
                        Save
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

let itemIndex = 1; // Work Items start from 1 (0 is already in table)

// Add Work Item
document.getElementById('addItem').addEventListener('click', function () {
    const row = `
    <tr>
        <td>
            <input type="text"
                   name="work_items[${itemIndex}][description]"
                   class="form-control"
                   required>
        </td>
        <td>
            <input type="text"
                   name="work_items[${itemIndex}][quantity]"
                   class="form-control"
                   required>
        </td>
        <td>
            <input type="number"
                   name="work_items[${itemIndex}][price]"
                   class="form-control"
                   min="0"
                   required>
        </td>
        <td class="text-center">
            <button type="button"
                    class="btn btn-sm btn-outline-danger bg-danger text-white removeRow">
                ×
            </button>
        </td>
    </tr>`;
    document.querySelector('#itemsTable tbody').insertAdjacentHTML('beforeend', row);
    itemIndex++;
});

// Add Term
document.getElementById('addTerm').addEventListener('click', function () {
    const termRow = `
    <div class="input-group mb-2">
        <input type="text"
               name="terms[]"
               class="form-control"
               required>
        <button type="button"
                class="btn btn-outline-danger removeTerm text-white bg-danger">
            ×
        </button>
    </div>`;
    document.getElementById('termsWrapper').insertAdjacentHTML('beforeend', termRow);
});

// Remove Work Item or Term
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('removeRow')) {
        const rows = document.querySelectorAll('#itemsTable tbody tr');
        if (rows.length > 1) { // At least 1 row should remain
            e.target.closest('tr').remove();
        }
    }

    if (e.target.classList.contains('removeTerm')) {
        const terms = document.querySelectorAll('#termsWrapper .input-group');
        if (terms.length > 1) { // At least 1 term should remain
            e.target.closest('.input-group').remove();
        }
    }
});
</script>



</x-default-layout>
