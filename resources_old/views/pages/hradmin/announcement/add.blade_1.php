<x-default-layout>

    <!--begin::Content-->
    <div class="flex-lg-row-fluid ms-lg-15">
        <!--begin:::Tabs-->
        <!--        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                    begin:::Tab item
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_customer_overview">Personal Details</a>
                    </li>
                    end:::Tab item
                    begin:::Tab item
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_customer_general">Education</a>
                    </li>
                    end:::Tab item
                    begin:::Tab item
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_customer_advanced">Job History</a>
                    </li>
                    end:::Tab item
                </ul>-->
        <!--end:::Tabs-->

        <!--begin:::Tab content-->
        <div class="tab-content" id="myTabContent">
            <!--begin:::Tab pane-->
            <div class="tab-pane fade show active" id="kt_ecommerce_customer_overview" role="tabpanel">


                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">

                        <!--begin::Toolbar-->
                        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                            <!--begin::Toolbar container-->
                            <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack">
                                <!--begin::Page title-->
                                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                    <!--begin::Title-->
                                    <h1
                                        class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                                        Add New Announcement</h1>
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
                                <form id="kt_ecommerce_add_category_form"
                                      class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework"
                                      method="POST" action="{{ route('announcement.store') }}"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <!-- @method('PUT') -->

                                    <!--begin::Main column-->
                                    <div class="col-12 col-md-9 me-lg-10">
                                        <!--begin::General options-->
                                        <div class="card card-flush py-4">
                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class=" fv-row fv-plugins-icon-container">
                                                            <label class="required form-label"> Title </label>
                                                            <input type="text" name="title"
                                                                   class="form-control form-control-sm mb-2"
                                                                   placeholder="Title" value="">
                                                        </div>
                                                    </div>
                                                </div>



                                                <div class="col-12 col-md-6">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="required form-label">Select Company </label>
                                                            <input type="text"
                                                                   class="product_color_tagify form-control form-control-sm"
                                                                   name="color" id="company_tagify"
                                                                   placeholder="Select Company" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="required form-label">Employee
                                                                Department</label>
                                                            <input type="text"
                                                                   class="product_color_tagify form-control form-control-sm"
                                                                   name="" id="department_tagify"
                                                                   placeholder="Select Department" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="required form-label">Employee
                                                                Designation</label>
                                                            <input type="text"
                                                                   class="product_color_tagify form-control form-control-sm"
                                                                   name="designation_id" id="designation_tagify"
                                                                   placeholder="Select Designation" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!--                                                <div class="col-12 col-md-6">
                                                                                                    <div class="card-body pt-0 pb-2">
                                                                                                        <div class="product fv-row fv-plugins-icon-container">
                                                                                                            <label class="required form-label">Employee Designation</label>
                                                                                                            <select class="form-select form-select-sm" id="changeBranch" name="designation_id" required>
                                                                                                                <option selected disabled>Select Designation</option>
                                                                                                   
                                                                                                            </select>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>-->


                                                <!--                                                <div class="col-12 col-md-6">
                                                                                                    <div class="card-body pt-0 pb-2">
                                                                                                        <div class="product fv-row fv-plugins-icon-container">
                                                                                                            <label class="required form-label"> Employee Name </label>
                                                                                                            <input type="text" name="employee_name"
                                                                                                                   class="form-control form-control-sm mb-2"
                                                                                                                   placeholder="Employee Name" value="" required>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>-->

                                                <div class="col-12 col-md-6">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="required form-label">Employee Name</label>
                                                            <input type="text"
                                                                   class="product_color_tagify form-control form-control-sm"
                                                                   name="employee_name" id="employee_tagify"
                                                                   placeholder="Select Employee" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="required form-label">Start Date</label>
                                                            <input type="date" name="start_date"
                                                                   class="form-control form-control-sm mb-2"
                                                                   placeholder="Employee Name" value="" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="required form-label">End Date</label>
                                                            <input type="date" name="end_date"
                                                                   class="form-control form-control-sm mb-2"
                                                                   placeholder="End Date" value="" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="required form-label">Description</label>
                                                            <input type="text" name="description"
                                                                   class="form-control form-control-sm mb-2"
                                                                   placeholder="Description" value="" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="card-body pt-0 pb-2">
                                                        <div class="product fv-row fv-plugins-icon-container">
                                                            <label class="required form-label">status</label>
                                                            <select class="form-select form-select-sm" name="status"
                                                                    required>
                                                                <option value="">--Select Status--</option>
                                                                <option value="1" selected>Active</option>
                                                                <option value="0">Disable</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <!--end::General options-->

                                        <div class="d-flex justify-content-end my-5">
                                            <!--begin::Button-->
                                            <a href="{{ route('announcement.index') }}"
                                               id="kt_ecommerce_add_product_cancel"
                                               class="btn btn-sm btn-success me-5">Cancel</a>
                                            <!--end::Button-->
                                            <!--begin::Button-->
                                            <button type="submit" id="kt_ecommerce_add_category_submit"
                                                    class="btn btn-sm btn-primary">
                                                <span class="indicator-label">Save</span>
                                                <span class="indicator-progress">Please wait...
                                                    <span
                                                        class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                            </button>
                                            <!--end::Button-->
                                        </div>
                                    </div>
                                    <!--end::Main column-->

                                </form>
                            </div>
                            <!--end::Content container-->
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Content wrapper-->
                </div>
            </div>
            <!--end:::Tab pane-->

            <!--begin:::Tab pane-->
            <div class="tab-pane fade" id="kt_ecommerce_customer_general" role="tabpanel">
                <!--begin::Card-->
                <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card header-->
                    <div class="card-header border-0">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Academic Details</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 pb-5">
                        <!--begin::Form-->
                        <form action="{{ route('employee_education.store') }}"
                              class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework"
                              method="POST" enctype="multipart/form-data">

                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-3">
                                    <div class="card-body pt-0 pb-2">
                                        <div class=" fv-row fv-plugins-icon-container">
                                            <label class="required form-label"> Exam </label>
                                            <input type="text" name="exam"
                                                   class="form-control form-control-sm mb-2" placeholder="Exam">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="card-body pt-0 pb-2">
                                        <div class="product fv-row fv-plugins-icon-container">
                                            <label class="required form-label"> Institution </label>
                                            <input type="text" name="institution"
                                                   class="form-control form-control-sm mb-2" placeholder="Institution">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2">
                                    <div class="card-body pt-0 pb-2">
                                        <div class=" fv-row fv-plugins-icon-container">
                                            <label class="required form-label"> Passing Year </label>
                                            <input type="date" name="passingyear"
                                                   class="form-control form-control-sm mb-2" placeholder="Employee Code">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2">
                                    <div class="card-body pt-0 pb-2">
                                        <div class=" fv-row fv-plugins-icon-container">
                                            <label class="required form-label"> Result </label>
                                            <input type="text" name="result"
                                                   class="form-control form-control-sm mb-2" placeholder="Result">
                                        </div>
                                    </div>
                                </div>


                                <div class="d-flex justify-content-end">
                                    <!--begin::Button-->
                                    <button type="submit" id="kt_ecommerce_customer_profile_submit"
                                            class="btn btn-light-primary">
                                        <span class="indicator-label">Save</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                    <!--end::Button-->
                                </div>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->

            </div>
            <!--end:::Tab pane-->

        </div>
        <!--end:::Tab content-->








</x-default-layout>

<script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>

<script type="text/javascript">
    $('#changeBranch').on('change', function() {
        var branchID = $(this).val();
        var url = '{{ route('branchDepartment', ':branchID') }}';
        url = url.replace(':branchID', branchID);

        console.log(url);
        if (branchID) {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    $('#changeDepartment').empty();

                    $('#changeDepartment').append(
                        `<option selected disabled>Select Department</option>`
                    );
                    if (data) {
                        $.each(data, function(key, value) {
                            $('#changeDepartment').append(
                                `<option value="` + value.id + `">` + value
                                .department_name +
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

    //      function onSelect(){
    //      var branchName= $('#company_tagify');
    //      if(true){
    //      branchName.value=='All Branch'
    //          branchName.value="";
    //          branchName.value='All Branch'
    //          }
    //      
    //      }  

    function initTagify(context) {
        // Initialize Tagify for the color inputs within the context
        $('#company_tagify', context).each(

            function() {
                var $tagifyInput = $(this);
                $.ajax({
                    url: '{{ route('allEmpBranch') }}',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        // Initialize Tagify with the received data as the whitelist
                        var tags = new Tagify($tagifyInput[0], {

                            whitelist: data,
                            maxTags: 6,
                            dropdown: {
                                maxItems: 20,
                                classname: "tagify__inline__suggestions",
                                enabled: 0,
                                closeOnSelect: false
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            });



        $('#designation_tagify', context).each(function() {
            var $tagifyInput = $(this);
            $.ajax({
                url: '{{ route('allEmpDesignation') }}',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Initialize Tagify with the received data as the whitelist
                    var tags = new Tagify($tagifyInput[0], {
                        whitelist: data,
                        maxTags: 6,
                        dropdown: {
                            maxItems: 20,
                            classname: "tagify__inline__suggestions",
                            enabled: 0,
                            closeOnSelect: false
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        });

        $('#employee_tagify', context).each(function() {
            var $tagifyInput = $(this);
            $.ajax({
                url: '{{ route('allEmployee') }}',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Initialize Tagify with the received data as the whitelist
                    var tags = new Tagify($tagifyInput[0], {
                        whitelist: data,
                        maxTags: 6,
                        dropdown: {
                            maxItems: 20,
                            classname: "tagify__inline__suggestions",
                            enabled: 0,
                            closeOnSelect: false
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    }
    initTagify(document);


    //    function initTagify2(context) {
    //            
    //        $('#department_tagify', context).each(function () {
    //            var $tagifyInput = $(this);
    //            $.ajax({
    //                url: '{{-- route("allEmpDepartment") --}}',
    //                method: 'GET',
    //                dataType: 'json',
    //                success: function (data) {
    //                     Initialize Tagify with the received data as the whitelist
    //                    var tags = new Tagify($tagifyInput[0], {
    //                        whitelist: data,
    //                        maxTags: 6,
    //                        dropdown: {
    //                            maxItems: 20,
    //                            classname: "tagify__inline__suggestions",
    //                            enabled: 0,
    //                            closeOnSelect: false
    //                        }
    //                    });
    //                },
    //                error: function (xhr, status, error) {
    //                    console.error('Error fetching data:', error);
    //                }
    //            });
    //        });
    //
    //    }

    //initTagify2(document);

    //    $('#department_tagify', context).each(
    //    

    function hhh() {
        var $tagifyInput = $('#department_tagify');
        // var tagifyInstance = $tagifyInput[0].tagify;

        // Destroy the existing Tagify instance if it exists

        //  tagifyInstance.destroy();
        //var tags;
        var branchName = $('#company_tagify').val();
        var url = '{{ route('allEmpDepartment', ':branchName') }}';
        var branchArray = JSON.parse(branchName);
        //console.log(branchArray);
        // var newew = Object.values(branchName);
        // const searchString = "SEML";
        var filteredData = branchArray.map(item => item.value).toString();
        console.log(filteredData);
        url = url.replace(':branchName', filteredData);
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function(data) {

                //$tagifyInput[0].tagify.destroy();
                // console.log(branchName);
                //                     Initialize Tagify with the received data as the whitelist
                var tags = new Tagify($tagifyInput[0], {
                    whitelist: data,
                    maxTags: 6,
                    dropdown: {
                        maxItems: 20,
                        classname: "tagify__inline__suggestions",
                        enabled: 0,
                        closeOnSelect: false
                    }
                });

            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    };

    //        );

    //  hhh ();

    $('#company_tagify').on('change', hhh);
    
    
    

    
</script>
