<x-default-layout>

    <div class="col-xl-12 px-5">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h3>Team Member Details</h3>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('team.index') }}" class="btn btn-sm btn-light-primary">
                    <i class="bi bi-arrow-left fs-5"></i> Go Back
                </a>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container">
            <div class="card card-flush">
                <div class="card-body py-5">

                    <div class="row">
                        <!-- Team Info -->
                        <div class="col-md-7">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle">
                                    <tbody>
                                        <tr>
                                            <th class="w-25 text-gray-700">Name:</th>
                                            <td>{{ $team->name }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-gray-700">Designation:</th>
                                            <td>{{ $team->designation }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-gray-700">Start Date:</th>
                                            <td>{{ $team->start_date }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-gray-700">Facebook:</th>
                                            <td><a href="{{ $team->fb_url }}" target="_blank">{{ $team->fb_url ?? 'N/A' }}</a></td>
                                        </tr>
                                        <tr>
                                            <th class="text-gray-700">Twitter:</th>
                                            <td><a href="{{ $team->twitter_url }}" target="_blank">{{ $team->twitter_url ?? 'N/A' }}</a></td>
                                        </tr>
                                        <tr>
                                            <th class="text-gray-700">LinkedIn:</th>
                                            <td><a href="{{ $team->linkdin_url }}" target="_blank">{{ $team->linkdin_url ?? 'N/A' }}</a></td>
                                        </tr>
                                        <tr>
                                            <th class="text-gray-700">Instagram:</th>
                                            <td><a href="{{ $team->instagram_url }}" target="_blank">{{ $team->instagram_url ?? 'N/A' }}</a></td>
                                        </tr>
                                        <tr>
                                            <th class="text-gray-700">Order:</th>
                                            <td>{{ $team->order }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-gray-700">Status:</th>
                                            <td>
                                                <span class="badge badge-light-{{ $team->status == 1 ? 'success' : 'secondary' }}">
                                                    {{ $team->status == 1 ? 'Active' : 'Disable' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-gray-700">Description:</th>
                                            <td>{!! $team->description !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Profile Image -->
                        <div class="col-md-5 text-center">
                            @if ($team->image)
                                <h5 class="pb-3">Profile Image:</h5>
                                <img src="{{ asset(Storage::url($team->image)) }}" class="img-fluid rounded shadow"
                                    alt="Profile Image" style="max-height: 250px; object-fit: cover;" />
                            @else
                                <p class="text-muted">No image available</p>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end mt-5">
                        <a href="{{ route('team.edit', $team->id) }}" class="btn btn-sm btn-success me-3">
                            <i class="bi bi-pencil-square fs-6"></i> Edit
                        </a>
                        <form action="{{ route('team.destroy', $team->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this team member?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash fs-6"></i> Delete
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--end::Content-->

</x-default-layout>
