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
                    <span class="card-label fw-bold text-gray-800">Employee Announcement</span>
                </h3>
            </div>
            <div id="tableDiv" class="card-body pt-0">
                <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0"
                       id="employee_announcement">
                    <thead>
                        <tr class="text-start fs-7 text-uppercase gs-0">
                            <th class="min-w-50px">ID</th>
                            <th class="min-w-100px">Title</th>
                            <th class="min-w-100px">Notice visible Start Date</th>
                            <th class="min-w-100px">Notice visible End Date</th>
                            <th class="min-w-100px">Description</th>
                            <th class="min-w-100px">Announcement</th>

                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-700" id="">

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

<!--begin::Modal - Create App-->
<div class="modal fade" id="kt_modal_edit_card" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-1000px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header">
                <!--begin::Modal title-->
                <h2 name="title"></h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                              transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                        <rect x="7.41422" y="6" width="16" height="2" rx="1"
                              transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <!--begin::Form-->


                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                    <h3><p>Date: &nbsp;From &nbsp;<span id="start_date"></span>&nbsp; to <span id="end_date"></span></p></h3>
                </div>

                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                    <!--begin::Label-->
                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                        <h3>
                            <span>Description</span>
                        </h3>
                    </label>
                    <!--end::Label-->
                    <p class="" style="text-align: justify;" name="description"></p>
                </div>

                <div class="col-md-12 mt-10">
                    <embed name="pd" src="" type="application/pdf"
                           width="100%" height="500px">
                </div>



                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
</div>
<!--end::Modal - Create App-->


@push('scripts')
<script>
var dataTableAnnouncement = {
    paging: true,
    ordering: false,
    searching: true,
    responsive: true,
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
var announcement = $('#employee_announcement').DataTable(dataTableAnnouncement);

function CallBack() {
    let url;
    var employeeID = {{ $employeeID }};
    console.log('employee_announcement:', employeeID);
    url = '{{ route('single_employee_Announcement', ['employeeID' => ':employeeID']) }}';
    url = url.replace(':employeeID', employeeID);
    $.ajax({
        url: url,
        type: "GET",
        data: {
            "_token": "{{ csrf_token() }}"
        },
        dataType: "json",
        success: function(data) {
           console.log('data: ', data);
            // Assuming 'announcement' is a DataTable instance
            announcement.clear().draw();

            if (data.length > 0) {
                $('#jsdataerror').text('');
                $.each(data, function(key, value) {
                    let d = data[key];
                    let downloadLink = `<button id="kt_modal_new_card" type="button" class="btn btn-sm btn-success p-2 pdf-button"
                                            value="${d.file_path}">
                                        View
                                    </button>`;
                    announcement.row.add([d.id, d.title, d.start_date, d.end_date, d.description, downloadLink
                    ]).draw();
                });
                    announcement.buttons().enable();
            } else {
                $('#jsdataerror').text('No records found');
                console.log('No records found');
            }
              announcement.column(4).visible( false );
        }

    });
}
    
CallBack();
    
$(document).on('click', '.pdf-button', function() {
     var data = $(this).val();
    let rowData = announcement.row($(this).closest('tr')).data();
// var data = $(this);
    data=data.replace("public","storage");
    
    $('#kt_modal_edit_card').find('[name="title"]').text(rowData[1]);  
    $('#kt_modal_edit_card').find('[id="start_date"]').text(rowData[2]);  
    $('#kt_modal_edit_card').find('[id="end_date"]').text(rowData[3]);  
    $('#kt_modal_edit_card').find('[name="description"]').text(rowData[4]);  
    $('#kt_modal_edit_card').find('[name="pd"]').attr("src", data);
    
    $("#kt_modal_edit_card").modal("show"); 
      
   // openPdf(data);
  // console.log('link: ', data);
});


    
    


</script>



@endpush