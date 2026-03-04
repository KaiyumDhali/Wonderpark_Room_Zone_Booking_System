<x-default-layout>
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
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Order List</h1>
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


            <div class="card card-flush">
                <div class="card-body">
                    <div class="row d-flex">
                        <div class="col-md-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <input id="start_date" type="date" name="start_date"
                                    class="form-control form-control-sm ps-5 p-2" onchange="CallBack()">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <input id="end_date" type="date" name="end_date"
                                    class="form-control form-control-sm ps-5 p-2" onchange="CallBack()">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <select class="form-select form-select-sm" name="type" id="type"
                                    onchange="CallBack()">
                                    <option value="2" selected>All Status</option>
                                    <option value="0">Pending</option>
                                    <option value="1">Approved</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 ms-auto">
                            <div class="d-flex flex-row-reverse">
                                @can('create product')
                                    <a href="{{ route('order.create') }}" type="button" class="btn btn-sm btn-primary"
                                        target="_blank">
                                        Create Order
                                    </a>
                                @endcan

                                {{-- <a href="" id="downloadPdf" name="downloadPdf" type="button"
                                    class="btn btn-sm btn-light-primary" target="_blank">
                                    PDF Download
                                </a> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <!--begin::Card header-->
                <div class="card-body pt-0 table-responsive">
                    <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                        id="customer_orders_table">
                        <thead>
                            <tr class="text-start fs-7 text-uppercase gs-0">
                                <th class="min-w-50px"> ID</th>
                                <th class="min-w-100px"> Order No</th>
                                <th class="min-w-100px"> Order Date</th>
                                <th class="min-w-50px"> Delivery Date</th>
                                <th class="min-w-100px">Customer Name</th>
                                <th class="min-w-100px">Status</th>
                                <th class="min-w-50px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700">

                        </tbody>
                    </table>
                </div>
                <!--end::Card body-->
            </div>

        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</x-default-layout>


<script type="text/javascript">
    let start_date;
    let end_date;
    let type;

    // Get current date in the format YYYY-MM-DD
    function getCurrentDate() {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Set current date as the default value for the start_date and end_date inputs
    document.getElementById('start_date').value = getCurrentDate();
    document.getElementById('end_date').value = getCurrentDate();

    var name = 'Sonali paper & Board mills limited (SPBML)';
    // name += `\n Attendance Date :  ${startDate} - ${startDate}`;
    var reportTittle = name;

    var dataTableOptions = {
        dom: '<"top"fB>rt<"bottom"lip>',
        buttons: [{
            extend: 'collection',
            text: 'Export',
            className: 'btn btn-sm btn-light-primary',

            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    title: `${reportTittle}`,
                    className: 'btn btn-sm btn-light-primary',
                },
                {
                    extend: 'copy',
                    text: 'Copy',
                    title: `${reportTittle}`, // Set custom title for Copy
                    className: 'btn btn-sm btn-light-primary',
                },
            ]
        }],
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
    let table = $('#customer_orders_table').DataTable(dataTableOptions);

    function CallBack() {

        start_date = $('#start_date').val() || new Date().toISOString().split('T')[0];
        end_date = $('#end_date').val() || new Date().toISOString().split('T')[0];
        type = $('#type').val() || 0;

        let url =
            '{{ route('getOrderLists', ['s_date' => ':start_date', 'e_date' => ':end_date', 'type' => ':type']) }}';
        url = url.replace(':start_date', start_date).replace(':end_date', end_date).replace(':type', type);
        console.log(url);
        $.ajax({
            url: url,
            type: "GET",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(data) {

                console.log(data);

                table.clear().draw();

                if (data.length > 0) {
                    $.each(data, function(key, value) {
                        let d = data[key];

                        let status;

                        let action = `<td class="text-end">
                                        <a onClick=edit(this) value="${d.id}" class="btn btn-sm btn-success hover-scale p-2 text-end" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                            <i class="bi bi-check2-square fs-2 ms-1"></i>
                                        </a>
                                        <a onClick=view(this) value="${d.id}" class="btn btn-sm btn-info hover-scale p-2 text-end" data-bs-toggle="tooltip" data-bs-placement="top" title="Show">
                                            <i class="bi bi-eye fs-2 ms-1"></i>
                                        </a>
                                    </td>`
                        if (d._status === 0) {
                            status = '<span style="color: red">Pending</span>';
                        } else if (d._status === 1) {
                            status = '<span style="color: green">Approved</span>';
                        } else if (d._status === null) {
                            status = '';
                        } else {
                            status = d._status; // For other cases
                        }

                        table.row.add([d.id, d.order_no, d.order_date, d
                            .delivery_date, d.account_name, status, action
                        ]).draw();

                    });
                    table.buttons().enable();
                } else {
                    console.log('No records found');
                }
            }
        });
    }

    $(document).ready(function() {
        CallBack();
    });


    function edit(element) {
        var id = $(element).attr('value'); // Getting the value attribute
        let url = '{{ route('orderEdit', ['id' => ':id']) }}';
        url = url.replace(':id', id);
        window.open(url, '_blank');
    }

    function view(element) {
        var id = $(element).attr('value'); // Getting the value attribute
        let url = '{{ route('orderView', ['id' => ':id']) }}';
        url = url.replace(':id', id);
        window.open(url, '_blank');
    }
</script>
