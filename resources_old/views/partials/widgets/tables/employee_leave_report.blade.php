<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <!--begin::Col-->
    <div class="col-xl-12">
        <div class="card card-flush h-md-100">
            <!--begin::Header-->
            <div class="card-header pt-0">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">Leave Report</span>
                </h3>
                <!--end::Title-->
                <!--begin::Toolbar-->
                {{-- <div class="card-toolbar">
                    <a class="btn btn-sm btn-light">History</a>
                </div> --}}
                <!--end::Toolbar-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-0">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-6 gy-0 mb-0"
                        id="employee_leave_report">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start  fs-6 text-uppercase gs-0">
                                <th class="min-w-30px sorting">
                                    Application</th>
                                <th class="min-w-10px sorting">
                                    Start Date</th>
                                <th class="min-w-10px sorting">
                                    End Date</th>
                                <th class="min-w-20px sorting">
                                    Type</th>
                                <th class="min-w-20px sorting">
                                    Total Leave</th>
                                <th class="min-w-20px sorting">
                                    Alternative
                                </th>
                                <th class="min-w-20px sorting">
                                    Reason</th>
                                <th class="min-w-20px sorting">
                                    Remarks</th>
                                <th class="min-w-20px sorting">
                                    Dept Status</th>
                                <th class="min-w-20px sorting">
                                    HR Status</th>
                                <th class="min-w-20px sorting">
                                    Mang Status</th>
                                <th class="min-w-20px sorting">
                                    Final Status</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700">

                        </tbody>
                    </table>
                </div>
            </div>
            <!--end: Card Body-->
        </div>
    </div>
    <!--end::Col-->
</div>
@push('scripts')
    <script>
        var dataTableOptionsLeaveReport = {
            paging: true,
            ordering: false,
            searching: true,
            responsive: true,
            lengthMenu: [10, 25, 50, 100],
            pageLength: 10,
            language: {
                lengthMenu: 'Show _MENU_ entries',
                search: 'Search:',
                paginate: {
                    first: 'First',
                    last: 'Last',
                    next: 'Next',
                    previous: 'Previous'
                }
            }
        };
        var leaveReport = $('#employee_leave_report').DataTable(dataTableOptionsLeaveReport);

        function LeaveEntryDetails() {
            let url;
            var employeeID = {{ $employeeID }};

            url = '{{ route('employeeLeaveEntryDetails', ['id' => ':employeeID']) }}';
            url = url.replace(':employeeID', employeeID);

            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    // leaveReport.clear().draw();

                    if (data.length > 0) {
                        $('#jsdataerror').text('');

                        $.each(data, function(key, value) {

                            var final_status = value.final_status == 0 ? "Pending" : "Approved";
                            var management_status = value.management_status == 0 ? "Pending" :
                                "Approved";
                            var hr_status = value.hr_status == 0 ? "Pending" : "Approved";
                            var department_status = value.department_status == 0 ? "Pending" :
                                "Approved";
                            var alternative_employee = value.alternative_employee_id.employee_name;

                            let d = data[key];
                            leaveReport.row.add([d.leave_application_date, d.leave_start_date, d
                                .leave_end_date, d.leave_type, d.total_days, alternative_employee,
                                d.reason_for_leave, d.remarks, department_status, hr_status,
                                management_status, final_status
                            ]).draw();
                        });
                        leaveReport.buttons().enable();

                    } else {
                        $('#jsdataerror').text('No records found');
                        console.log('No records found');
                    }
                }
            });
        }
        LeaveEntryDetails();
    </script>
@endpush
