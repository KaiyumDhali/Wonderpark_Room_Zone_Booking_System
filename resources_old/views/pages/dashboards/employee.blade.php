<x-default-layout>
    <div class="row">
		<div class="col-md-3 col-12 pb-10 text-center text-md-start ">
            @if ($employeePhoto !== null)
                <img src="{{ Storage::url($employeePhoto) }}" height="120" width="120" alt="Profile Image" />
            @endif
        </div>

        <div class="col-md-6 col-12 pb-10 text-center">
            @if ($employeeBranch == 1)
                <h1>Shahjalal Equity Management Limited</h1>
            @elseif($employeeBranch == 2)
                <h1>Shahjalal Asset Management Limited</h1>
            @else
                <h1>Shahjalal Equity Management Limited</h1>
            @endif

            <h2>Employee Dashboard</h2>
            <h2>{{ $employeeName }}</h2>
        </div>
        
		<div class="col-md-3 col-12 pb-10 text-center text-md-end">
            @if ($employeeBranch == 1)
            <img src="{{ asset('assets/media/logos/default-dark.jpeg') }}" alt="SEML" width="210px" />
            @elseif($employeeBranch == 2)
            <img src="{{ asset('assets/media/logos/shahjalalasset_logo.jpg') }}" alt="SAML"
            width="210px" />
            @else
            <img src="{{ asset('assets/media/logos/default-dark.jpeg') }}" alt="SEML" width="210px" />
            @endif
        </div>
    </div>

    {{-- @include('partials.widgets.charts.pie_chart_report', ['chartData' => json_encode($chartData)]) --}}
    @include('partials/widgets/charts/pie_chart_report')
    @include('partials/widgets/tables/employee_attendance_report')
    @include('partials/widgets/tables/employee_leave_report')
    @include('partials/widgets/tables/announcement_modal')
        {{--@include('partials/widgets/tables/announcement')--}}


    {{-- @if ($employeeID == 1)
    @include('partials/widgets/tables/announcement_modal')
    @else
    @include('partials/widgets/tables/announcement')
    @endif --}}

</x-default-layout>