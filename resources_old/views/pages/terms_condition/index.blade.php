<x-default-layout>

    {{-- Alerts --}}
    <div class="col-xl-12 px-5">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <!-- Toolbar -->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center">
                <h3 class="fw-bold">Terms & Conditions</h3>
                <span class="text-muted fs-7">
                    Manage spot and additional service terms
                </span>
            </div>

            <a href="{{ route('terms-conditions.create') }}"
               class="btn btn-sm btn-success">
                Add New
            </a>
        </div>
    </div>

    <!-- Content -->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container">

            <div class="card card-flush">
                <div class="card-body py-5">

                    <table class="table table-row-dashed table-hover align-middle fs-6">
                        <thead>
                            <tr class="text-gray-600 fw-bold fs-7 text-uppercase">
                                <th>#</th>
                                <th>Type</th>
                                <th>Related</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th class="text-center">Order</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($terms as $index => $term)
                                <tr>
                                    <td>
                                        {{ $terms->firstItem() + $index }}
                                    </td>

                                    {{-- Type --}}
                                    <td>
                                        @switch($term->term_type)
                                            @case('spot')
                                                <span class="badge bg-primary">Spot</span>
                                                @break
                                            @case('service')
                                                <span class="badge bg-info">Service</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">Common</span>
                                        @endswitch
                                    </td>

                                    {{-- Related Spot / Service --}}
                                    <td>
                                        @if ($term->term_type === 'spot')
                                            {{ $term->spot?->title ?? '-' }}
                                        @elseif ($term->term_type === 'service')
                                            {{ $term->service?->title ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- Title --}}
                                    <td class="fw-semibold">
                                        {{ $term->term_title ?? '-' }}
                                    </td>

                                    {{-- Description --}}
                                    <td style="max-width: 400px;">
                                        {{ Str::limit(strip_tags($term->term_description), 80) }}
                                    </td>

                                    {{-- Sort Order --}}
                                    <td class="text-center">
                                        {{ $term->sort_order }}
                                    </td>

                                    {{-- Status --}}
                                    <td class="text-center">
                                        @if ($term->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Disabled</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-end">
                                        <a href="{{ route('terms-conditions.show', $term->id) }}"
                                           class="btn btn-sm btn-light-info">
                                            View
                                        </a>

                                        <a href="{{ route('terms-conditions.edit', $term->id) }}"
                                           class="btn btn-sm btn-light-primary">
                                            Edit
                                        </a>

                                        {{--
                                        <form action="{{ route('terms-conditions.destroy', $term->id) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-light-danger"
                                                    onclick="return confirm('Delete this term?')">
                                                Delete
                                            </button>
                                        </form>
                                        --}}

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-6">
                                        No terms & conditions found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $terms->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>

</x-default-layout>
