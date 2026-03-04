<!--begin::Chart widget 36-->
<div class="card card-flush overflow-hidden h-lg-100">
	<!--begin::Header-->
	<div class="card-header pt-5">
		<!--begin::Title-->
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-dark">All Employee Performance</span>
			<span class="text-gray-400 mt-1 fw-semibold fs-6">Today {{ now()->format('jS F Y') }}</span>
		</h3>
		<!--end::Title-->
		<!--begin::Toolbar-->
		{{-- <div class="card-toolbar">
			<!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
			<div data-kt-daterangepicker="true" data-kt-daterangepicker-opens="left" data-kt-daterangepicker-range="today" class="btn btn-sm btn-light d-flex align-items-center px-4">
			<!--begin::Display range-->
			<div class="text-gray-600 fw-bold">Loading date range...</div>
			<!--end::Display range-->
			{!! getIcon('calendar-8', 'fs-1 ms-2 me-0') !!}</div>
			<!--end::Daterangepicker-->
		</div> --}}
		<!--end::Toolbar-->
	</div>
	<!--end::Header-->
	<!--begin::Card body-->
	<div class="card-body d-flex align-items-end p-0">
		<!--begin::Chart-->
		<div id="kt_docs_google_chart_pie" class="min-h-auto w-100 ps-4 pe-6" style="height: 300px"></div>
		<!--end::Chart-->
	</div>
	<!--end::Card body-->
</div>
<!--end::Chart widget 36-->

<script src="//www.google.com/jsapi"></script>
@push('scripts')
	<script>
		function CallBack() {
            let url;
            url = '{{ route('all_employee_attendance_chart') }}';
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
					if (data.length > 0) {

						console.log("Chart_data : ", data);
                        // GOOGLE CHARTS INIT
						google.load('visualization', '1', {
							packages: ['corechart', 'bar', 'line']
						});

						google.setOnLoadCallback(function() {
							var chartData = google.visualization.arrayToDataTable(data);

							var options = {
								title: 'My Monthly Activities',
								colors: ['#2abe81', '#ff0000', '#6e4ff5', '#F16D3B', '#8601B0', '#115D2F', '#FFC700']
							};
							var chart = new google.visualization.PieChart(document.getElementById('kt_docs_google_chart_pie'));
							chart.draw(chartData, options);
						});

                    } else {
                        $('#jsdataerror').text('No records found');
                        console.log('No records found');
                    }

                    // //GOOGLE CHARTS INIT
					// google.load('visualization', '1', {
					// 	packages: ['corechart', 'bar', 'line'];
					// });

					// google.setOnLoadCallback(function() {
					// 	// Parse the JSON-encoded data passed from the controller
					// 	var chartData = chartData;
					// 	//consol.log("chartData:", chartData);
					// 	var data = google.visualization.arrayToDataTable(chartData);

					// 	var options = {
					// 		title: 'My Monthly Activities',
					// 		colors: ['#fe3995', '#6e4ff5', '#2abe81', '#c7d2e7', '#593ae1']
					// 	};

					// 	var chart = new google.visualization.PieChart(document.getElementById('kt_docs_google_chart_pie'));
					// 	chart.draw(data, options);
					// });
                }
            });
        }
        CallBack();
		
	</script>
@endpush