<x-default-layout>
    <div class="container mt-5">

        <h3 class="mb-4">Add Client Information</h3>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Add Client Form --}}
        <form action="{{ route('infos.store') }}" method="POST">
    @csrf
    <div class="d-flex">
        <div class="mb-3 col-md-3 me-3">
            <label class="form-label">Name</label>
            <input 
                type="text" 
                name="name" 
                class="form-control" 
                value="{{ old('name') }}"
                required>
        </div>
        <div class="mb-3 col-md-3 me-3">
            <label class="form-label">Designation</label>
            <input 
                type="text" 
                name="designation" 
                class="form-control" 
                value="{{ old('designation') }}">
        </div>
        <div class="mb-3 col-md-3 me-3">
            <label class="form-label">Company</label>
            <input 
                type="text" 
                name="company" 
                class="form-control" 
                value="{{ old('company') }}">
        </div>
        <div class="mb-3 col-md-3 pe-9">
            <label class="form-label">Mobile</label>
            <input 
                type="text" 
                name="mobile" 
                class="form-control"
                value="{{ old('mobile') }}"
                required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Address</label>
        <textarea 
            name="address" 
            class="form-control"
            rows="3">{{ old('address') }}</textarea>
    </div>

    {{-- Save button right aligned --}}
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">
            Save
        </button>
    </div>
</form>


        {{-- Existing Client List --}}
        <h4 class="mt-5 mb-3">Existing Clients</h4>

        @if($clients->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead style="background-color: #148FB8; color:white">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Company</th>
                            <th>Mobile</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $index => $client)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->designation ?? '-' }}</td>
                                <td>{{ $client->company ?? '-' }}</td>
                                <td>{{ $client->mobile }}</td>
                                <td>{{ $client->address ?? '-' }}</td>
                                <td>
                                    {{-- Edit Button --}}
                                    <button type="button" class="btn btn-sm btn-warning" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editClientModal{{ $client->id }}">
                                        Edit
                                    </button>

                                    {{-- Delete Button --}}
                                    <form action="{{ route('infos.destroy', $client->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this client?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Edit Client Modal --}}
                            <div class="modal fade" id="editClientModal{{ $client->id }}" tabindex="-1" aria-labelledby="editClientModalLabel{{ $client->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editClientModalLabel{{ $client->id }}">Edit Client</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('infos.update', $client->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Name</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $client->name }}" required>
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Designation</label>
                                                        <input type="text" name="designation" class="form-control" value="{{ $client->designation }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Company</label>
                                                        <input type="text" name="company" class="form-control" value="{{ $client->company }}">
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Mobile</label>
                                                        <input type="text" name="mobile" class="form-control" value="{{ $client->mobile }}" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Address</label>
                                                    <textarea name="address" class="form-control" rows="3">{{ $client->address }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">No clients added yet.</p>
        @endif

    </div>
</x-default-layout>
