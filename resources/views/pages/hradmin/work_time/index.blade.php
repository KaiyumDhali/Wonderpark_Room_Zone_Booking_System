<x-default-layout>
    <style>
        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }
    </style>

    <div class="col-xl-12 px-5">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                    @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                    @endforeach
            </ul>
        </div>
        @endif
        @if (session('message'))
        <div class="alert alert-{{ session('alert-type') }}">
                {{ session('message') }}
        </div>
        @endif
    </div>

    <div class="row">
        {{-- Work Time Start --}}
        <div class="col-md-6 col-12 pb-15">
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="px-5">
                    <!--begin::Products-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h3>Manage Work Time</h3>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                @can('create timetable')
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_new_card">
                                        Add New
                                    </a>
                                @endcan
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Table-->
                            <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0" >
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start fs-7 text-uppercase gs-0">
                                        <th class="min-w-50px"> SL</th>
                                        <th class="min-w-50px"> Start Time</th>
                                        <th class="min-w-50px"> End Time</th>
                                        <th class="min-w-100px"> Done By</th>
                                        @can('write timetable')
                                            <th class="text-end min-w-100px">Action</th>
                                        @endcan
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-semibold text-gray-700">
                                    @foreach ($workTimes as $workTime)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $workTime->start_time }}
                                            </td>
                                            <td>
                                                {{ $workTime->end_time }}
                                            </td>
                                            <td>
                                                {{ $workTime->created_by }}
                                            </td>
                                            
                                            @can('write timetable')
                                                <td class="text-end">
                                                    <form method="POST" action="{{ route('work_time.destroy', $workTime->id) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="button" class="btn btn-sm btn-success p-2"
                                                            data-workTime="{{ json_encode($workTime) }}"
                                                            onclick="openEditModalWorkTimeId(this)">
                                                            <i class="fa fa-pencil text-white px-2"></i>
                                                        </button>
                                                        <button type="submit" class="btn btn-sm btn-danger p-2">
                                                            <i class="fa fa-trash text-white px-2"></i>
                                                        </button>
                                                    </form>
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

            <!--begin:: New Add Modal - Create App-->
            <div class="modal fade" id="kt_modal_new_card" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2>Add Work Time</h2>
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
                            <form id="kt_modal_new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                method="POST" action="{{ route('work_time.store') }}" enctype="multipart/form-data">
                                @csrf
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Start Time</span>
                                    </label>
                                    <!--end::Label-->
                                    <input type="time" class="form-control form-control-sm" name="start_time">
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">End Time</span>
                                    </label>
                                    <!--end::Label-->
                                    <input type="time" class="form-control form-control-sm" name="end_time">
                                </div>

                                <div class="text-center pt-15">
                                    <a href="{{ route('work_time.index') }}" id="kt_modal_new_card_cancel"
                                        class="btn btn-light me-5">Cancel</a>

                                    <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->

            <!--begin:: Edit Modal - Create App-->
            <div class="modal fade" id="kt_modal_edit_card" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2>Edit Work Time</h2>
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
                            <form id="kt_modal_edit_card_form_work_time" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                method="POST" action="{{ route('work_time.update', ':workTimeId') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Start Time</span>
                                    </label>
                                    <!--end::Label-->
                                    <input type="time" class="form-control form-control-sm" name="start_time" >
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">End Time</span>
                                    </label>
                                    <!--end::Label-->
                                    <input type="time" class="form-control form-control-sm" name="end_time" >
                                </div>

                                <div class="text-center pt-15">
                                    <a href="{{ route('work_time.index') }}" id="kt_modal_new_card_cancel"
                                        class="btn btn-light me-5">Cancel</a>

                                    <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>

                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
        </div>

        {{-- Break Time Start --}}
        <div class="col-md-6 col-12 pb-15">
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="px-5">
                    <!--begin::Products-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h3>Manage Break Time</h3>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                @can('create timetable')
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_new_card_break_time">
                                        Add New
                                    </a>
                                @endcan
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Table-->
                            <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0" >
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start fs-7 text-uppercase gs-0">
                                        <th class="min-w-50px"> SL</th>
                                        <th class="min-w-50px"> Start Time</th>
                                        <th class="min-w-50px"> End Time</th>
                                        <th class="min-w-100px"> Done By</th>
                                        @can('write timetable')
                                            <th class="text-end min-w-100px">Action</th>
                                        @endcan
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-semibold text-gray-700">
                                    @foreach ($breakTimes as $breakTime)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $breakTime->start_time }}
                                            </td>
                                            <td>
                                                {{ $breakTime->end_time }}
                                            </td>
                                            <td>
                                                {{ $breakTime->created_by }}
                                            </td>
                                            
                                            @can('write timetable')
                                                <td class="text-end">
                                                    <form method="POST" action="{{ route('break_time.destroy', $breakTime->id) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="button" class="btn btn-sm btn-success p-2"
                                                            data-breakTime="{{ json_encode($breakTime) }}"
                                                            onclick="openEditModalBreakTimeId(this)">
                                                            <i class="fa fa-pencil text-white px-2"></i>
                                                        </button>
                                                        <button type="submit" class="btn btn-sm btn-danger p-2">
                                                            <i class="fa fa-trash text-white px-2"></i>
                                                        </button>
                                                    </form>
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

            <!--begin:: New Add Modal - Create App-->
            <div class="modal fade" id="kt_modal_new_card_break_time" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2>Add Break Time</h2>
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
                            <form id="kt_modal_new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                method="POST" action="{{ route('break_time.store') }}" enctype="multipart/form-data">
                                @csrf
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Start Time</span>
                                    </label>
                                    <!--end::Label-->
                                    <input type="time" class="form-control form-control-sm" name="start_time">
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">End Time</span>
                                    </label>
                                    <!--end::Label-->
                                    <input type="time" class="form-control form-control-sm" name="end_time">
                                </div>

                                <div class="text-center pt-15">
                                    <a href="{{ route('work_time.index') }}" id="kt_modal_new_card_cancel"
                                        class="btn btn-light me-5">Cancel</a>

                                    <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->

            <!--begin:: Edit Modal - Create App-->
            <div class="modal fade" id="kt_modal_edit_card_break_time" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2>Edit Break Time</h2>
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
                            <form id="kt_modal_edit_card_form_break_time" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                method="POST" action="{{ route('break_time.update', ':breakTimeId') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Start Time</span>
                                    </label>
                                    <!--end::Label-->
                                    <input type="time" class="form-control form-control-sm" name="start_time" >
                                </div>
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">End Time</span>
                                    </label>
                                    <!--end::Label-->
                                    <input type="time" class="form-control form-control-sm" name="end_time" >
                                </div>

                                <div class="text-center pt-15">
                                    <a href="{{ route('work_time.index') }}" id="kt_modal_new_card_cancel"
                                        class="btn btn-light me-5">Cancel</a>

                                    <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>

                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
        </div>

        {{-- Weekend Days Start --}}
        <div class="col-md-6 col-12 pb-15">
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="px-5">
                    <!--begin::Products-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h3>Manage Weekend Days</h3>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                @can('create timetable')
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_new_card_weekend_day">
                                        Add New
                                    </a>
                                @endcan
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Table-->
                            <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0" >
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start fs-7 text-uppercase gs-0">
                                        <th class="min-w-50px"> SL</th>
                                        <th class="min-w-50px"> Weekend Day</th>
                                        <th class="min-w-100px"> Done By</th>
                                        @can('write timetable')
                                            <th class="text-end min-w-100px">Action</th>
                                        @endcan
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-semibold text-gray-700">
                                    @foreach ($weekendDays as $weekendDay)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $weekendDay->weekend_day }}
                                            </td>
                                            <td>
                                                {{ $weekendDay->created_by }}
                                            </td>
                                            
                                            @can('write timetable')
                                                <td class="text-end">
                                                    <form method="POST" action="{{ route('weekend_day.destroy', $weekendDay->id) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="button" class="btn btn-sm btn-success p-2"
                                                            data-lateTime="{{ json_encode($weekendDay) }}"
                                                            onclick="openEditModalWeekendDayId(this)">
                                                            <i class="fa fa-pencil text-white px-2"></i>
                                                        </button>
                                                        <button type="submit" class="btn btn-sm btn-danger p-2">
                                                            <i class="fa fa-trash text-white px-2"></i>
                                                        </button>
                                                    </form>
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

            <!--begin:: New Add Modal - Create App-->
            <div class="modal fade" id="kt_modal_new_card_weekend_day" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2>Add Weekend Day</h2>
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
                            <form id="kt_modal_new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                method="POST" action="{{ route('weekend_day.store') }}" enctype="multipart/form-data">
                                @csrf
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Weekend Day</span>
                                    </label>
                                    <!--end::Label-->
                                    {{-- <input type="time" class="form-control form-control-sm" name="weekend_day"> --}}
                                    <select class="form-select form-select-sm" data-control="select2" name="weekend_day" required>
                                        <option selected disabled>Select Weekend Day </option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                        <option value="Sunday">Sunday</option>
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wednesday">Wednesday</option>
                                        <option value="Thursday">Thursday</option>
                                    </select>
                                </div>

                                <div class="text-center pt-15">
                                    <a href="{{ route('work_time.index') }}" id="kt_modal_new_card_cancel"
                                        class="btn btn-light me-5">Cancel</a>

                                    <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->

            <!--begin:: Edit Modal - Create App-->
            <div class="modal fade" id="kt_modal_edit_card_weekend_day" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2>Edit Weekend Day</h2>
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
                            <form id="kt_modal_edit_card_form_weekend_day" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                method="POST" action="{{ route('weekend_day.update', ':weekendDayId') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Weekend Day</span>
                                    </label>
                                    <!--end::Label-->
                                    <select class="form-select form-select-sm" data-control="select2" name="weekend_day" required>
                                        <option selected disabled>Select Weekend Day </option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                        <option value="Sunday">Sunday</option>
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wednesday">Wednesday</option>
                                        <option value="Thursday">Thursday</option>
                                    </select>
                                </div>

                                <div class="text-center pt-15">
                                    <a href="{{ route('work_time.index') }}" id="kt_modal_new_card_cancel"
                                        class="btn btn-light me-5">Cancel</a>

                                    <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>

                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
        </div>
        
        {{-- Late Time Start --}}
        <div class="col-md-6 col-12 pb-15">
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="px-5">
                    <!--begin::Products-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h3>Manage Late Time</h3>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                @can('create timetable')
                                    <a href="#" class="btn btn-sm btn-flex btn-light-primary" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_new_card_late_time">
                                        Add New
                                    </a>
                                @endcan
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Table-->
                            <table class="table table-striped table-bordered align-middle table-row-dashed fs-7 gy-5 mb-0" >
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start fs-7 text-uppercase gs-0">
                                        <th class="min-w-50px"> SL</th>
                                        <th class="min-w-50px"> Late Time</th>
                                        <th class="min-w-100px"> Done By</th>
                                        @can('write timetable')
                                            <th class="text-end min-w-100px">Action</th>
                                        @endcan
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-semibold text-gray-700">
                                    @foreach ($lateTimes as $lateTime)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $lateTime->late_time }}
                                            </td>
                                            <td>
                                                {{ $lateTime->created_by }}
                                            </td>
                                            
                                            @can('write timetable')
                                                <td class="text-end">
                                                    <form method="POST" action="{{ route('late_time.destroy', $lateTime->id) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="button" class="btn btn-sm btn-success p-2"
                                                            data-lateTime="{{ json_encode($lateTime) }}"
                                                            onclick="openEditModalLateTimeId(this)">
                                                            <i class="fa fa-pencil text-white px-2"></i>
                                                        </button>
                                                        <button type="submit" class="btn btn-sm btn-danger p-2">
                                                            <i class="fa fa-trash text-white px-2"></i>
                                                        </button>
                                                    </form>
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

            <!--begin:: New Add Modal - Create App-->
            <div class="modal fade" id="kt_modal_new_card_late_time" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2>Add Late Time</h2>
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
                            <form id="kt_modal_new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                method="POST" action="{{ route('late_time.store') }}" enctype="multipart/form-data">
                                @csrf
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Late Time</span>
                                    </label>
                                    <!--end::Label-->
                                    <input type="time" class="form-control form-control-sm" name="late_time">
                                </div>

                                <div class="text-center pt-15">
                                    <a href="{{ route('work_time.index') }}" id="kt_modal_new_card_cancel"
                                        class="btn btn-light me-5">Cancel</a>

                                    <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->

            <!--begin:: Edit Modal - Create App-->
            <div class="modal fade" id="kt_modal_edit_card_late_time" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2>Edit Late Time</h2>
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
                            <form id="kt_modal_edit_card_form_late_time" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                method="POST" action="{{ route('late_time.update', ':lateTimeId') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                        <span class="required">Late Time</span>
                                    </label>
                                    <!--end::Label-->
                                    <input type="time" class="form-control form-control-sm" name="late_time" >
                                </div>

                                <div class="text-center pt-15">
                                    <a href="{{ route('work_time.index') }}" id="kt_modal_new_card_cancel"
                                        class="btn btn-light me-5">Cancel</a>

                                    <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>

                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal - Create App-->
        </div>

    </div>
    

</x-default-layout>


<script type="text/javascript">

    // Work Time Start
    function openEditModalWorkTimeId(button) {

        const workTimeData = JSON.parse(button.getAttribute('data-workTime'));

        const formAction = $('#kt_modal_edit_card').find('#kt_modal_edit_card_form_work_time').attr('action');
        const workTimeId = workTimeData.id;
        const updatedFormAction = formAction.replace(':workTimeId', workTimeId);
        $('#kt_modal_edit_card').find('#kt_modal_edit_card_form_work_time').attr('action', updatedFormAction);

        var formattedStartTime = workTimeData.start_time.split(':')[0] + ':' + workTimeData.start_time.split(':')[1];
        var formattedEndTime = workTimeData.end_time.split(':')[0] + ':' + workTimeData.end_time.split(':')[1];

        $('#kt_modal_edit_card').find('input[name="start_time"]').val(formattedStartTime);
        $('#kt_modal_edit_card').find('input[name="end_time"]').val(formattedEndTime);
        
        $('#kt_modal_edit_card').modal('show');
    }
    // Break Time Start
    function openEditModalBreakTimeId(button) {

        const breakTimeData = JSON.parse(button.getAttribute('data-breakTime'));

        const formAction = $('#kt_modal_edit_card_break_time').find('#kt_modal_edit_card_form_break_time').attr('action');
        const breakTimeId = breakTimeData.id;
        const updatedFormAction = formAction.replace(':breakTimeId', breakTimeId);
        $('#kt_modal_edit_card_break_time').find('#kt_modal_edit_card_form_break_time').attr('action', updatedFormAction);

        var formattedStartTime = breakTimeData.start_time.split(':')[0] + ':' + breakTimeData.start_time.split(':')[1];
        var formattedEndTime = breakTimeData.end_time.split(':')[0] + ':' + breakTimeData.end_time.split(':')[1];

        $('#kt_modal_edit_card_break_time').find('input[name="start_time"]').val(formattedStartTime);
        $('#kt_modal_edit_card_break_time').find('input[name="end_time"]').val(formattedEndTime);

        $('#kt_modal_edit_card_break_time').modal('show');
    }
    // Late Time Start
    function openEditModalLateTimeId(button) {

        const lateTimeData = JSON.parse(button.getAttribute('data-lateTime'));

        const formAction = $('#kt_modal_edit_card_late_time').find('#kt_modal_edit_card_form_late_time').attr('action');
        const lateTimeId = lateTimeData.id;
        const updatedFormAction = formAction.replace(':lateTimeId', lateTimeId);
        $('#kt_modal_edit_card_late_time').find('#kt_modal_edit_card_form_late_time').attr('action', updatedFormAction);

        var formattedTime = lateTimeData.late_time.split(':')[0] + ':' + lateTimeData.late_time.split(':')[1];

        $('#kt_modal_edit_card_late_time').find('input[name="late_time"]').val(formattedTime);

        $('#kt_modal_edit_card_late_time').modal('show');
    }

    // Late Time Start
    function openEditModalWeekendDayId(button) {

        const weekendDayData = JSON.parse(button.getAttribute('data-lateTime'));

        const formAction = $('#kt_modal_edit_card_weekend_day').find('#kt_modal_edit_card_form_weekend_day').attr('action');
        const weekendDayId = weekendDayData.id;
        const updatedFormAction = formAction.replace(':weekendDayId', weekendDayId);
        $('#kt_modal_edit_card_weekend_day').find('#kt_modal_edit_card_form_weekend_day').attr('action', updatedFormAction);

        $('#kt_modal_edit_card_weekend_day').find('select[name="weekend_day"]').val(weekendDayData.weekend_day);

        $('#kt_modal_edit_card_weekend_day').modal('show');
    }

</script>
