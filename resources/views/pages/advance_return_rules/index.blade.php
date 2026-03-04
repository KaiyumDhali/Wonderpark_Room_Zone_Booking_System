<x-default-layout>
    <div class="container">

        <h3 class="mb-4">Advance Return Rules</h3>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Error Message --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Add Rule Form --}}
       <div class="card mb-4">
    <div class="card-header py-5">
        <strong>Add New Rule</strong>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('advance-return-rules.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Max Day</label>
                    <input type="number"
                           name="max_day"
                           class="form-control"
                           value="{{ old('max_day') }}"
                           placeholder="e.g. 5"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Refund Percent (%)</label>
                    <input type="number"
                           step="0.01"
                           name="refund_percent"
                           class="form-control"
                           value="{{ old('refund_percent') }}"
                           placeholder="e.g. 30"
                           required>
                </div>
            </div>

            {{-- Align button to the right --}}
            <div class="mt-3 d-flex justify-content-end py-5">
                <button class="btn btn-primary">Save Rule</button>
            </div>
        </form>
    </div>
</div>


        {{-- Rules List --}}
        <div class="card">
            <div class="card-header">
                <strong>Rule List</strong>
            </div>

            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>SL</th>
                            <th>Max Day</th>
                            <th>Refund %</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rules as $ruleItem)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ruleItem->max_day }}</td>
                                <td>{{ $ruleItem->refund_percent }}%</td>
                                <td class="text-end">
                                    {{-- Edit Button (Triggers Modal) --}}
                                    <button class="btn btn-sm btn-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editRuleModal"
                                            data-id="{{ $ruleItem->id }}"
                                            data-max_day="{{ $ruleItem->max_day }}"
                                            data-refund_percent="{{ $ruleItem->refund_percent }}">
                                        Edit
                                    </button>

                                    {{-- Delete Button --}}
                                    <form action="{{ route('advance-return-rules.destroy', $ruleItem->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this rule?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No rules found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Edit Rule Modal --}}
        <div class="modal fade" id="editRuleModal" tabindex="-1" aria-labelledby="editRuleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" id="editRuleForm">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editRuleModalLabel">Edit Rule</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Max Day</label>
                                <input type="number" name="max_day" class="form-control" id="editMaxDay" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Refund Percent (%)</label>
                                <input type="number" step="0.01" name="refund_percent" class="form-control" id="editRefundPercent" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Rule</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    {{-- Modal Script --}}
    <script>
        const editRuleModal = document.getElementById('editRuleModal');
        editRuleModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const maxDay = button.getAttribute('data-max_day');
            const refundPercent = button.getAttribute('data-refund_percent');

            const form = document.getElementById('editRuleForm');
            form.action = `/advance-return-rules/${id}`;
            form.querySelector('#editMaxDay').value = maxDay;
            form.querySelector('#editRefundPercent').value = refundPercent;
        });
    </script>
</x-default-layout>
