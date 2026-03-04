<x-default-layout>
	<style>
        .table > :not(caption) > * > * {
            padding: 0.3rem !important;
        }
    </style>
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h3>Employee Performance Type</h3>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
		<div id="kt_app_content" class="app-content flex-column-fluid">
			<!--begin::Content container-->
			<div id="kt_app_content_container" class="app-container">
				<!--begin::Products-->
				<div class="card card-flush">
					<!--begin::Card header-->
					<div class="card-header align-items-center py-5 gap-2 gap-md-5">
						<!--begin::Card title-->
						<div class="card-title">
							<!--begin::Search-->
							<div class="d-flex align-items-center position-relative my-1">
								<!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
								<span class="svg-icon svg-icon-1 position-absolute ms-4">
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
										<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
									</svg>
								</span>
								<!--end::Svg Icon-->
								<input type="text" data-kt-ecommerce-order-filter="search" class="form-control form-control-solid w-250px ps-14 p-2" placeholder="Search" />
							</div>
							<!--end::Search-->
							<!--begin::Export buttons-->
							<div id="kt_ecommerce_report_customer_orders_export" class="d-none"></div>
							<!--end::Export buttons-->
						</div>
						<!--end::Card title-->
						<!--begin::Card toolbar-->
						<div class="card-toolbar flex-row-fluid justify-content-end gap-5">
							<!--begin::Daterangepicker-->
							{{-- <input class="form-control form-control-solid w-100 mw-250px" placeholder="Pick date range" id="kt_ecommerce_report_customer_orders_daterangepicker" /> --}}
							<!--end::Daterangepicker-->
							<!--begin::Filter-->
							<div class="w-150px">
								<select class="form-select p-2 form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Status" data-kt-ecommerce-order-filter="status">
									<option value="all"> Status</option>
									<option value="Active">Active</option>
									<option value="Disabled">Disabled</option>
								</select>
							</div>
							<!--end::Filter-->
							@can('create performance type')
                            <a href="{{ route('performance_type.create') }}" class="btn btn-primary btn-sm">Add Performance Type</a>
							@endcan
						</div>
						<!--end::Card toolbar-->
					</div>
					<!--end::Card header-->
					<!--begin::Card body-->
					<div class="card-body pt-0">
						<!--begin::Table-->
						<table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"  id="kt_ecommerce_report_customer_orders_table" >
							<!--begin::Table head-->
							<thead>
								<!--begin::Table row-->
								<tr class="text-start fs-7 text-uppercase gs-0">
									<th class="min-w-50px">Performance ID</th>
									<th class="min-w-50px">Performance Name</th>
                                    <th class="min-w-100px">Performance Status</th>
									@can('write performance type')
									<th class="text-end min-w-100px">Actions</th>
									@endcan
								</tr>
								<!--end::Table row-->
							</thead>
							<!--end::Table head-->
							<!--begin::Table body-->
							<tbody class="fw-semibold text-gray-700">
                                @foreach($performance_types as $performance_type)
								<tr>
									<td>
										<a>{{$performance_type->id}}</a>
									</td>
									<td>
										<a>{{$performance_type->performance_name}}</a>
									</td>
                                    <td>
										<div class="badge badge-light-{{$performance_type->status==1 ? 'success' : 'info'}}">{{ $performance_type->status==1?'Active':'Disabled' }}</div>
									</td>
									@can('write performance type')
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary py-1"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                            <span class="svg-icon svg-icon-5 m-0">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                        fill="currentColor"></path>
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </a>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                            data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="{{ route('performance_type.edit', $performance_type->id) }}"
                                                    class="menu-link px-3">Edit</a>
                                            </div>
                                        </div>
                                        <!--end::Menu-->
                                    </td>
									@endcan
								</tr>
                                @endforeach
							</tbody>
							<!--end::Table body-->
						</table>
						<!--end::Table-->
					</div>
					<!--end::Card body-->
				</div>
				<!--end::Products-->
			</div>
			<!--end::Content container-->
		</div>
		<!--end::Content-->

</x-default-layout>