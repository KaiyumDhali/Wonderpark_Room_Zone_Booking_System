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
                <h3>Blog Details</h3>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('blog.index') }}" class="btn btn-sm btn-light-primary">
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
                        <!-- Blog Info -->
                        <div class="col-md-7">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle">
                                    <tbody>
                                        <tr>
                                            <th class="w-25 text-gray-700">Title:</th>
                                            <td>{{ $blog->title }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-gray-700">Type:</th>
                                            <td>{{ $blog->type ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-gray-700">Description:</th>
                                            <td>{!! $blog->description !!}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-gray-700">Status:</th>
                                            <td>
                                                <span class="badge badge-light-{{ $blog->status == 1 ? 'success' : 'secondary' }}">
                                                    {{ $blog->status == 1 ? 'Active' : 'Disable' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Blog Image -->
                        <div class="col-md-5 text-center">
                            @if ($blog->image)
                                <h5 class="pb-3">Blog Image:</h5>
                                <img src="{{ asset(Storage::url($blog->image)) }}" class="img-fluid rounded shadow"
                                    alt="Blog Image" style="max-height: 250px; object-fit: cover;" />
                            @else
                                <p class="text-muted">No image available</p>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end mt-5">
                        <a href="{{ route('blog.edit', $blog->id) }}" class="btn btn-sm btn-success me-3">
                            <i class="bi bi-pencil-square fs-6"></i> Edit
                        </a>
                        <form action="{{ route('blog.destroy', $blog->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this blog?')">
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
