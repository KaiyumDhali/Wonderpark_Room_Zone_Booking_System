<x-default-layout>
    <div class="container-fluid">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container  d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h3>Add Announcement</h3>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
            <form id="" method="POST" action="{{ route('announcement.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row px-10">
                    <!-- Produceable Products Content -->

                    <!-- Consumable Products Content -->
                    <div class="col-md-12">
                        <div class="row me-0 me-md-2">
                            <div class="card shadow my-0 mb-2">
                                <div class="card-body row py-1">

                                    <div class="col-md-3 pb-3">
                                        <div class="row">
                                            <div class="form-group col-md-4 pt-9">
                                                <h5 class="text-gray-800">{{ __('Branch:') }}</h5>
                                            </div>
                                            <div class="form-group col-md-8 pt-7">

                                                <select id="changeBranch" class="form-control form-control-sm" name="" required>
                                                    <option selected disabled>Select Branch</option>
                                                    @foreach($allEmpBranch as $key => $company)
                                                    <option value="{{ $key }}">{{ $company }}</option>
                                                    @endforeach
                                                </select>
                                                {{-- changeConsumeProduct
                                                consume_product_add --}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 pb-3">
                                        <div class="row">
                                            <div class="form-group col-md-3 pt-9">
                                                <h5 class="text-gray-800">{{ __('Department:') }}</h5>
                                            </div>
                                            <div class="form-group col-md-9 pt-7">
                                                <select id="changeDepartment" class="form-control form-control-sm" name="" required>
                                                    <option selected>Select Department</option>
                                                    {{-- @foreach($consumable_products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->product_name}}</option>
                                                    @endforeach --}}
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 pb-3">
                                        <div class="row">
                                            <div class="form-group col-md-3 pt-9">
                                                <h5 class="text-gray-800">{{ __('Designation:') }}</h5>
                                            </div>
                                            <div class="form-group col-md-9 pt-7">
                                                <select id="changeDesignation" class="form-control form-control-sm" name="" required>
                                                    <option value="0" selected>All Designation</option>
                                                    @foreach($allEmpDesignation as $key => $designation)
                                                    <option value="{{ $key }}">{{ $designation }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-1 pt-6">
                                        <div class="row">
                                            {{-- <div class="d-flex justify-content-end px-5"> --}}
                                            <div class="" style="padding-right: 0;">
                                                <button type="button" id="addAnnouncement" class="btn btn-sm btn-primary px-10 float-end">{{ __('ADD') }}</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row p-10">
                    <div class="col-md-12">
                        <div class="row me-0 me-md-2">
                            <div class="col-md-12 card shadow px-5">
                                <table id="add_announcement" class="table table-bordered mt-4">
                                    <tr>
                                        <th style="width: 60px;"></th>
                                        {{-- <th>Sl. No.</th> --}}
                                        <th>Branch Name</th>
                                        <th>Department Name</th>
                                        <th>Designation Name</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row px-10">

                    <div class="col-md-12">
                        <div class="row me-0 me-md-2">
                            <div class="col-md-12 card shadow px-5">
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <div class="row">
                                            <div class="form-group col-md-2 pt-9">
                                                <h5 class="text-gray-800">{{ __('Title:') }}</h5>
                                            </div>
                                            <div class="form-group col-md-8 pt-7 ps-10 ms-5">
                                                <input type="text" name="title"
                                                       class="form-control form-control-sm mb-2"
                                                       placeholder="Title" value="" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4" col-12>
                                        <div class="row">
                                            <div class="form-group col-md-4 pt-9">
                                                <h5 class="text-gray-800">{{ __('Notice visible Date:') }}</h5>
                                            </div>
                                            <div class="form-group col-md-8 pt-7">
                                                <input type="date" name="start_date"
                                                       class="form-control form-control-sm mb-2"
                                                       placeholder="Employee Name" value="" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="row">
                                            <div class="form-group col-md-4 pt-9 text-end">
                                                <h5 class="text-gray-800">{{ __('Upload File:') }}</h5>
                                            </div>
                                            <div class="form-group col-md-8 pt-7">
                                                <input type="file" name="announcement_file"
                                                       class="form-control form-control-sm mb-2"
                                                       placeholder="Description" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row pb-5">
                                            <div class="form-group col-md-1 pt-9">
                                                <h5 class="text-gray-800">{{ __('Description:') }}</h5>
                                            </div>
                                            <div class="form-group col-md-11 pt-7">
                                                <textarea class="form-control form-control-md mb-2" id="" name="description" rows="5" cols="12"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end px-5">
                        <a class="btn btn-sm btn-success px-10 mt-5 me-5" href="{{ route('announcement.index') }}">Back</a>
                        <button type="submit" id="submitAllProceed" class="btn btn-sm btn-primary px-10 mt-5">{{ __('All Save') }}</button>
                    </div>

                </div>



            </form>
        </div>
        <!--end::Content wrapper-->
    </div>
</x-default-layout>

<script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>

<script type="text/javascript">
    $('#changeBranch').on('change', function () {
        var branchID = $(this).val();
        
        var url = '{{ route('allEmpDepartment', ':branchID') }}';
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

                    if(data.length > 0){
                        $('#changeDepartment').append(
                        `<option value="-1" selected disabled>Select Department</option><option value="0">All Department</option>`
                        );
                    }else{
                        $('#changeDepartment').append(
                        `<option value="-1" selected disabled>No Department Found</option>`
                        );
                        return;
                    }
                    
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


    $('#add_announcement').on('click', function(e) {
        if (e.target.matches('.delete')) {
            const { id } = e.target.dataset;
            const selector = `tr[data-id="${id}"]`;
            $(selector).remove();
        }
        
    });
            
    let id = 0;
    

    $('#addAnnouncement').on('click', function() {
        // $( "#myselect option:selected" ).text();
        // branch_name.text();
        // branch_id.val();
        // department_name.text();
        // department_id.val();
        // designation_name.text();
        // designation_id.val();
        let branch_name = $('#changeBranch option:selected');
        let department_name = $('#changeDepartment option:selected');
        let designation_name = $('#changeDesignation option:selected');

        
        if(branch_name.val() > 0){

            if (department_name.val() == -1) {
                alert("Please Select department");
                return;
                
            }
            
                // Check for duplicate consume_product_id
                let duplicate_found = false;
                $('#add_announcement tr').each(function() {
                    if($(this).find('input[name="branch_id[]"]').val() === branch_name.val() && $(this).find('input[name="department_id[]"]').val() == 0){
                       
                          //  alert('Already all department selected.');
                            duplicate_found = true;
                            return;
                    }
                    if($(this).find('input[name="branch_id[]"]').val() === branch_name.val() && $(this).find('input[name="department_id[]"]').val() == department_name.val() && 
                        $(this).find('input[name="designation_id[]"]').val() == 0){
                          //  alert('Already all department selected.');
                            duplicate_found = true;
                            return;
                        }

                    if ($(this).find('input[name="branch_id[]"]').val() === branch_name.val() && $(this).find('input[name="department_id[]"]').val() === department_name.val() && 
                        $(this).find('input[name="designation_id[]"]').val() === designation_name.val()
                    ) {
                        duplicate_found = true;
                        return; // break out of loop if duplicate found
                    }
                });


                if (duplicate_found) {
                    alert('Already has been added to the list.');
                } else {
                    
                    $('#add_announcement').append(
                    `<tr data-id="${id}">
                        <td class="text-center my-0"> <button data-id="${id}" class="delete btn btn-sm btn-danger py-0" >Delete</button></td>
                        <td>` + branch_name.text() + `<input type="hidden" required="required" name="branch_id[]" value=`+ branch_name.val() +` ></td>
                        <td>` + (department_name.val() == 0 ? 'All Department' : department_name.text()) + `<input type="hidden" required="required" name="department_id[]" value=`+ department_name.val() +` ></td>
                        <td>` + (designation_name.val() == 0 ? 'All Designation' : designation_name.text()) + `<input type="hidden" required="required" name="designation_id[]" value=`+ designation_name.val() +` ></td>
                    </tr>`
                    );

                    $('#add_announcement tr').each(function() {
                        if($(this).find('input[name="branch_id[]"]').val() === branch_name.val() && $(this).find('input[name="department_id[]"]').val() == 0){
                            $('#add_announcement tr').each(function() {
                                if($(this).find('input[name="branch_id[]"]').val() === branch_name.val() && $(this).find('input[name="department_id[]"]').val() > 0){
                                    $(this).remove();
                                }
                            });
                    }
                    });
                    

                    ++id;
                    // $('#changeProduct').val('');
                   // $('#quantity').val('');
                }
        }else{
            alert("Please Select Branch");
        }
        
    });

    
</script>
