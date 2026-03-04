<x-default-layout>
    <style>
    .table> :not(caption)>*>* {
        padding: 0.3rem !important;
    }
    </style>

    <div class="col-xl-12 py-10">
        <div class="card card-flush h-lg-100" id="kt_contacts_main">
            <div class="card-header pt-7" id="kt_chat_contacts_header">
                <div class="card-title">
                    <!--begin::Svg Icon | path: icons/duotune/communication/com005.svg-->
                    <span class="svg-icon svg-icon-1 me-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z"
                                fill="currentColor"></path>
                            <path opacity="0.3"
                                d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z"
                                fill="currentColor"></path>
                        </svg>
                    </span>
                    <h3>Employee Performance Add</h3>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-5">
                <form id="leaveForm" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                    action="{{ route('employee_performance.store') }}" enctype="multipart/form-data">
                    @csrf
                    <!-- <div class="row row-cols-1 row-cols-sm-4 rol-cols-md-1 row-cols-lg-4"> -->
                    <div class="row">
                        <div class="col-md-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Select Employee Branch</span>
                                </label>
                                <select id="changeBranch" name="branch_id" class="form-select form-select-sm" required>
                                    <option selected disabled>Select Employee Branch</option>
                                    @foreach($allEmpBranch as $key => $value)
                                    <option value="{{ $key }}">{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Select Employee</span>
                                </label>
                                <select id="selectEmployee" name="employee_id" class="form-select form-select-sm"
                                    required>
                                    <option selected disabled>Select Employee</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="required">Date</span>
                                </label>
                                <input type="date" class="form-control form-control-sm" name="performance_date"
                                    required>
                            </div>
                        </div>

                        <!-- <div class="col-md-2">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold form-label mt-0">
                                    <span class="">Remarks</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" name="remarks"
                                    id="leave_remarks">
                            </div>
                        </div> -->

                    </div>

                    <div class="row d-none" id="performanceList">

                    </div>
                    
                    <div class="fv-row mb-7 mt-8 d-none" id="save">
                        <!--begin::Button-->
                        <a href="{{ route('employee_performance.index') }}"
                                    id="kt_ecommerce_add_product_cancel" class="btn btn-sm btn-success me-5">Cancel</a>
                        <!--end::Button-->
                        <button type="submit" data-kt-contacts-type="submit" class="btn btn-sm btn-primary">
                            <span class="indicator-label">Save</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-default-layout>

<script type="text/javascript">
{
    const intRx = /\d/,
        integerChange = (event) => {
            console.log(event.key, )
            if (
                (event.key.length > 1) ||
                event.ctrlKey ||
                ((event.key === "-") && (!event.currentTarget.value.length)) ||
                intRx.test(event.key)
            ) return;
            event.preventDefault();
        };

    for (let input of document.querySelectorAll(
            'input[type="number"][step="1"]'
        )) input.addEventListener("keydown", integerChange);
}

$('#changeBranch').on('change', function() {
    $('#selectEmployee').empty();
    $('#performanceList').addClass('d-none');

    var branchID = $(this).val();
    var url = '{{route("employeePerformanceBranchDetails",':branchID')}}';
    url = url.replace(':branchID', branchID);

    if (branchID) {
        $.ajax({
            url: url,
            type: "GET",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(data) {
                $('#selectEmployee').append(
                    `<option selected disabled>Select Employee</option>`
                );
                if (data) {
                    // console.log("Hello world!:", data); 
                    $.each(data, function(key, value) {
                        $('#selectEmployee').append(
                            `<option value="` + value.id + `">` + value.employee_name +
                            `</option>`
                        );
                    });
                } else {
                    $('#jsdataerror').text('Not Found');
                }
            }
        });
    } else {
        $('#jsdataerror').text('Not Found');
    }
});


$('#selectEmployee').on('change', function() {

    var employeeID = $(this).val();
    var url = '{{route("employeePerformancType",':employeeID')}}';
    url = url.replace(':employeeID', employeeID);

    if (employeeID) {
        $.ajax({
            url: url,
            type: "GET",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(data) {

                if (data) {
                    $('#save').removeClass('d-none');
                    $('#performanceList').removeClass('d-none');
                    $('#performanceList').empty();
                    console.log('data', data);
                
                    $.each(data, function(key, value) {
                        $('#performanceList').append(
                            `<tr class="row">
                                <td><input type="hidden" name="employee_performance_id[]" value="${value.id}"></td>
                                <td class="col-12 col-md-2"><input type="text" name="" value="${value.performance_name}" class="form-control form-control-sm form-control-solid" readonly></td>
                                <td class="col-12 col-md-2">
                                    <div class="rating">
                                        <label class="btn btn-light fw-bold btn-sm rating-label me-3" for="${value.id}_kt_rating_input_0">
                                            Reset
                                        </label>
                                        <input class="rating-input" name="employee_performance_rate[][${value.id}]" value="0" type="radio" id="${value.id}_kt_rating_input_0"/>
                                        
                                        <label class="rating-label" for="${value.id}_kt_rating_input_1">
                                            <i class="bi bi-star fs-1"></i>
                                        </label>
                                        <input class="rating-input" name="employee_performance_rate[][${value.id}]" value="1" type="radio" id="${value.id}_kt_rating_input_1"/>
                                        
                                        <label class="rating-label" for="${value.id}_kt_rating_input_2">
                                            <i class="bi bi-star fs-1"></i>
                                        </label>
                                        <input class="rating-input" name="employee_performance_rate[][${value.id}]" value="2" type="radio" id="${value.id}_kt_rating_input_2"/>
                                        
                                        <label class="rating-label" for="${value.id}_kt_rating_input_3">
                                            <i class="bi bi-star fs-1"></i>
                                        </label>
                                        <input class="rating-input" name="employee_performance_rate[][${value.id}]" value="3" type="radio" id="${value.id}_kt_rating_input_3"/>
                                        
                                        <label class="rating-label" for="${value.id}_kt_rating_input_4">
                                            <i class="bi bi-star fs-1"></i>
                                        </label>
                                        <input class="rating-input" name="employee_performance_rate[][${value.id}]" value="4" type="radio" id="${value.id}_kt_rating_input_4"/>
                                        
                                        <label class="rating-label" for="${value.id}_kt_rating_input_5">
                                            <i class="bi bi-star fs-1"></i>
                                        </label>
                                        <input class="rating-input" name="employee_performance_rate[][${value.id}]" value="5" type="radio" id="${value.id}_kt_rating_input_5"/>
                                    </div>
                                </td>
                                <td class="col-12 col-md-2"><input type="text" name="employee_remarks[]" placeholder="Employee Remark" class="form-control form-control-sm"></td>
                            </tr>`
                        );
                    });
                } else {
                    $('#jsdataerror').text('Not Found');
                }
            }
        });
    } else {
        $('#jsdataerror').text('Not Found');
    }
});


// $("#performanceList").on('click', function(){
//    // var values = $(this).closest('input[type="text"]').find('input[name="show-rating"]').val();
//     $(this).find('input:radio').each(function() {
//         $(this).val('checked');
//     });
//     // var asdfsadg = $(this).closest('input.show-rating').val();
//     // console.log(asdfsadg);
// });

</script>