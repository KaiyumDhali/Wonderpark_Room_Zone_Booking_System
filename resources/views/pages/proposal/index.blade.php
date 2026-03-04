<x-default-layout>
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0">Proposals</h2>
        <a href="{{ route('proposals.create') }}" class="btn btn-primary">Create Proposal</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr class="fw-bold fs-4">
                            <th>Proposal No</th>
                            <th>Title</th>
                            <th>Client</th>
                            <!-- <th>Status</th> -->
                            <th class="text-end">Total</th>
                            <th class="text-center">Date</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proposals as $p)
                            <tr>
                                <td>
                                    <strong>{{ $p->proposal_number ?? ('#'.$p->id) }}</strong>
                                </td>
                                <td>{{ $p->proposal_title ?? '-' }}</td>
                                <td>{{ $p->customer?->customer_name ?? 'N/A' }}</td>

                                <!-- <td>
                                    <span class="badge bg-secondary">{{ $p->status }}</span>
                                </td> -->
                                <td class="text-end">৳{{ number_format($p->total ?? 0, 2) }}</td>
                                <td class="text-center">{{ optional($p->created_at)->format('d M Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('proposals.show', $p->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="{{ route('proposals.pdf', $p->id) }}" class="btn btn-sm btn-outline-dark">PDF</a>
                                    <a href="{{ route('proposals.edit', $p) }}" class="btn btn-sm btn-outline-warning">Edit</a>

                                    <form action="{{ route('proposals.destroy', $p) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this proposal?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>

                                </td>

                                
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No proposals found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $proposals->links() }}
            </div>
        </div>
    </div>
</div>
</x-default-layout>
