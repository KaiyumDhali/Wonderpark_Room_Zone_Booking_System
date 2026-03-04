<!--begin::Chart Widget 35-->
<div class="card card-flush h-md-100">
	<!--begin::Header-->
	<div class="card-header pt-5 mb-6">
		<!--begin::Title-->
		<h3 class="card-title align-items-start flex-column">
			<!--begin::Statistics-->
			<div class="d-flex align-items-center mb-2">
				<!--begin::Currency-->
				<span class="fs-3 fw-semibold text-gray-400 align-self-start me-1">৳</span>
				<!--end::Currency-->
				<!--begin::Value-->
				<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">{{$todayTotalSum}}</span>
				<!--end::Value-->
				<!--begin::Label-->
				{{-- <span class="badge badge-light-success fs-base">{!! getIcon('arrow-up', 'fs-5 text-success ms-n1') !!} 9.2%</span> --}}
				<!--end::Label-->
			</div>
			<!--end::Statistics-->
			<!--begin::Description-->
			<span class="fs-6 fw-semibold text-gray-400">Today Order Amount</span>
			<!--end::Description-->
		</h3>
		<!--end::Title-->
		<!--begin::Toolbar-->
		<div class="card-toolbar">
			<a href="{{ route('order.index') }}" class="btn btn-sm btn-light-primary">View All History</a>
		</div>
		<!--end::Toolbar-->
	</div>
	<!--end::Header-->
	<!--begin::Body-->
	<div class="card-body py-0 px-0">
		<!--begin::Nav-->
		{{-- <ul class="nav d-flex justify-content-between mb-3 mx-9">
			<!--begin::Item-->
			<li class="nav-item mb-3">
				<!--begin::Link-->
				<a class="nav-link btn btn-flex flex-center btn-active-danger btn-color-gray-600 btn-active-color-white rounded-2 w-45px h-35px active" data-bs-toggle="tab" id="kt_charts_widget_35_tab_1" href="#kt_charts_widget_35_tab_content_1">Today Order</a>
				<!--end::Link-->
			</li>
			<!--end::Item-->
		</ul> --}}
		<!--end::Nav-->
		<!--begin::Tab Content-->
		<div class="tab-content mt-n6">
			<!--begin::Tap pane-->
			<div class="tab-pane fade active show" id="kt_charts_widget_35_tab_content_1">
				<!--begin::Chart-->
				<div id="kt_charts_widget_35_chart_1" data-kt-chart-color="primary" class="min-h-auto h-200px ps-3 pe-6"></div>
				<!--end::Chart-->
				<!--begin::Table container-->
				<div class="table-responsive mx-9 mt-n6">
					<!--begin::Table-->
					<table class="table align-middle gs-0 gy-4">
						<!--begin::Table head-->
						<thead>
							<tr>
								<th class="min-w-100px"></th>
								{{-- <th class="min-w-100px"></th> --}}
								<th class="min-w-100px "></th>
								<th class="text-end min-w-50px"></th>
							</tr>
						</thead>
						<!--end::Table head-->
						<!--begin::Table body-->
						<tbody>
							@foreach ($orderListCurrentDateLimit as $productOrder)
							<tr>
								<td>
									<a class="badge badge-light-primary"> Customer: {{ $productOrder->customer->account_name }}</a>
								</td>
								<td>Order No: {{ $productOrder->order_no }}</td>
								<td class="pe-0 text-end">
									<a class="badge badge-light-primary">Amount: {{ $productOrder->orderdetail_sum_stock_out_total_amount }}</a>
								</td>
							</tr>
							@endforeach
							{{-- <tr>
								<td>
									<a href="#" class="text-gray-600 fw-bold fs-6">3:10 PM</a>
								</td>
								<td class="pe-0 text-end">
									<span class="text-gray-800 fw-bold fs-6 me-1">$3,207.03</span>
								</td>
								<td class="pe-0 text-end">
									<span class="fw-bold fs-6 text-success">+576.24</span>
								</td>
							</tr>
							<tr>
								<td>
									<a href="#" class="text-gray-600 fw-bold fs-6">3:55 PM</a>
								</td>
								<td class="pe-0 text-end">
									<span class="text-gray-800 fw-bold fs-6 me-1">$3,274.94</span>
								</td>
								<td class="pe-0 text-end">
									<span class="fw-bold fs-6 text-success">+124.03</span>
								</td>
							</tr> --}}
						</tbody>
						<!--end::Table body-->
					</table>
					<!--end::Table-->
				</div>
				<!--end::Table container-->
			</div>
			<!--end::Tap pane-->
			<!--begin::Tap pane-->
			{{-- <div class="tab-pane fade" id="kt_charts_widget_35_tab_content_2">
				<!--begin::Chart-->
				<div id="kt_charts_widget_35_chart_2" data-kt-chart-color="primary" class="min-h-auto h-200px ps-3 pe-6"></div>
				<!--end::Chart-->
				<!--begin::Table container-->
				<div class="table-responsive mx-9 mt-n6">
					<!--begin::Table-->
					<table class="table align-middle gs-0 gy-4">
						<!--begin::Table head-->
						<thead>
							<tr>
								<th class="min-w-100px"></th>
								<th class="min-w-100px text-end pe-0"></th>
								<th class="text-end min-w-50px"></th>
							</tr>
						</thead>
						<!--end::Table head-->
						<!--begin::Table body-->
						<tbody>
							<tr>
								<td>
									<a href="#" class="text-gray-600 fw-bold fs-6">4:30 PM</a>
								</td>
								<td class="pe-0 text-end">
									<span class="text-gray-800 fw-bold fs-6 me-1">$2,345.45</span>
								</td>
								<td class="pe-0 text-end">
									<span class="fw-bold fs-6 text-success">+134.02</span>
								</td>
							</tr>
							<tr>
								<td>
									<a href="#" class="text-gray-600 fw-bold fs-6">11:35 AM</a>
								</td>
								<td class="pe-0 text-end">
									<span class="text-gray-800 fw-bold fs-6 me-1">$756.26</span>
								</td>
								<td class="pe-0 text-end">
									<span class="fw-bold fs-6 text-primary">-124.03</span>
								</td>
							</tr>
							<tr>
								<td>
									<a href="#" class="text-gray-600 fw-bold fs-6">3:30 PM</a>
								</td>
								<td class="pe-0 text-end">
									<span class="text-gray-800 fw-bold fs-6 me-1">$1,756.26</span>
								</td>
								<td class="pe-0 text-end">
									<span class="fw-bold fs-6 text-danger">+144.04</span>
								</td>
							</tr>
						</tbody>
						<!--end::Table body-->
					</table>
					<!--end::Table-->
				</div>
				<!--end::Table container-->
			</div> --}}
			<!--end::Tap pane-->
		</div>
		<!--end::Tab Content-->
	</div>
	<!--end::Body-->
</div>
<!--end::Chart Widget 35-->
