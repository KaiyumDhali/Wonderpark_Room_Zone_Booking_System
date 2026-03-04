<style>
    .dataTables_filter {
        float: right;
    }

    .dataTables_buttons {
        float: left;
    }
    .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
</style>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="">
        <!--begin::Products-->
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header pt-0">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">Employee Attendance</span>
                </h3>
            </div>
            <div id="tableDiv" class="card-body pt-0">
                
                <table class="table table-responsive table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                    id="all_employee_attendance">
                    <thead>
                        <tr class="text-start fs-7 text-uppercase gs-0">
                            <th class="min-w-50px"> ID</th>
                            <th class="min-w-100px"> Name</th>
                            {{-- <th class="min-w-100px"> Department</th>
                            <th class="min-w-100px"> Designation</th> --}}
                            <th class="min-w-50px"> Date</th>
                            <th class="min-w-100px">Day</th>
                            <th class="min-w-100px">Time In</th>
                            <th class="min-w-100px">Time Out</th>
                            <th class="min-w-100px">Time ( <span class="text-danger">Late</span> / <span class="text-success">Early</span> )</th>
                            <th class="min-w-100px">Work Time</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-700" id="attendance_list">

                    </tbody>
                </table>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Products-->
    </div>
    <!--end::Content container-->
</div>
<!--end::Content-->
@push('scripts')
    <script>
        var dataTableOptions = {
            paging: true,
            ordering: false,
            searching: true,
            responsive: false,
            lengthMenu: [10, 25, 50, 100],
            pageLength: 25,
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
        var table = $('#all_employee_attendance').DataTable(dataTableOptions);

        function CallBack(pdfdata) {
            let url;
            url = '{{ route('daily_all_attendance') }}';
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    table.clear().draw();

                    if (data.length > 0) {
                        $('#jsdataerror').text('');

                        console.log('data: ', data);
                        $.each(data, function(key, value) {
                            let d = data[key];

                            let inTimeFormatted = "";
                            if (/^\d{2}:\d{2}:\d{2}$/.test(d.InTime)) {
                                inTimeFormatted = formatTimeToAMPM(d.InTime);
                            } else {
                                inTimeFormatted = d.InTime;
                            }
                            // if (d.InTime === "Out Of Office" || d.InTime === "Punishment Absence" || d.InTime === "Leave") {
                            //     inTimeFormatted = d.InTime;
                            // } else {
                            //     inTimeFormatted = formatTimeToAMPM(d.InTime);
                            // }
                            let outTimeFormatted = formatTimeToAMPM(d.OutTime);

                            let lateTimeValue = (d.LateTime === null) ? null : parseTimeToMinutes(d.LateTime);
                            let lateTimeClass = (lateTimeValue !== null && lateTimeValue < 0) ? 'color: red;' : 'color: green;';
                            let inTimeClass = (inTimeFormatted !== null && inTimeFormatted === 'Absent') ? 'color: red;' : 'color: black;';
                            
                            table.row.add([d.ID, d.EmployeeName, d._date, d.Day, 
                                (inTimeFormatted !== null) ? `<span style="${inTimeClass}">${inTimeFormatted}</span>` : '',
                                outTimeFormatted,
                                (lateTimeValue !== null) ? `<span style="${lateTimeClass}">${d.LateTime}</span>` : '',
                                d.WorkTime
                            ]).draw();
                            
                            // Apply class to the LateTime column cells
                            if (lateTimeValue !== null) {
                                table.column(4).nodes().to$().addClass(lateTimeClass);
                            }
                        });
                        table.buttons().enable();

                        function parseTimeToMinutes(time) {
                            if (!time) return null;
                            const isNegative = time.startsWith('-');
                            const parts = isNegative ? time.slice(1).split(':') : time.split(':');
                            let hours = parseInt(parts[0], 10);
                            let minutes = parseInt(parts[1], 10);
                            let seconds = parseInt(parts[2], 10);

                            // Convert time to total minutes, considering the negative sign
                            let totalMinutes = hours * 60 + minutes + seconds / 60;
                            return isNegative ? -totalMinutes : totalMinutes;
                        }
                        function formatTimeToAMPM(time) {
                            let formattedTime = "";
                            if (time) {
                                let timeParts = time.split(":");
                                let hours = parseInt(timeParts[0]);
                                let minutes = timeParts[1];
                                let seconds = timeParts[2];
                                
                                let period = hours < 12 ? "AM" : "PM";
                                hours = (hours % 12) || 12; // Convert 0 to 12 for midnight
                                formattedTime = `${hours}:${minutes}:${seconds} ${period}`;
                            }
                            return formattedTime;
                        }

                    } else {
                        $('#jsdataerror').text('No records found');
                        console.log('No records found');
                    }

                }
            });
        }
        CallBack("list");
    </script>
@endpush
