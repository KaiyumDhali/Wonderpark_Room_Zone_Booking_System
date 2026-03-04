@php
	$companySetting = \App\Models\CompanySetting::where('status', 1)->first();
	if ($companySetting) {
		$company_name = $companySetting->company_name;
		$company_logo_one = $companySetting->company_logo_one;
		$company_logo_two = $companySetting->company_logo_two;
		$website_url = $companySetting->website_url;
	} else {
		$company_logo_one = null;
		$company_logo_two = null;
		$company_name = 'Company Name';
		$website_url = null;
	}
@endphp
<!--begin::Card widget 7-->
{{-- <div class="card bg-light-dark card-xl-stretch mb-xl-8">
	<!--begin::Body-->
	<div class="card-body my-3">
		<a href="{{$website_url}}" class="card-title fw-bold text-success fs-5 mb-3 d-block" target="_blank">{{$company_name}}</a>
		<div class="py-1">
			@if (!empty($company_logo_one))
				<img src="{{ url(Storage::url($company_logo_one)) }}" width="80px" class="mt-2" alt="{{$company_name}}" />
			@else
				<p class="pt-2">{{$company_name}}</p>
			@endif
		</div>
		<div class="progress h-7px bg-success bg-opacity-50 mt-7">
			<div class="progress-bar bg-success" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
	</div>
	<!--end:: Body-->
</div> --}}

<div class="card card-flush h-md-50 mb-5 mb-xl-10">
	<div class="card-header">
		@if (!empty($company_logo_one))
			<img src="{{ url(Storage::url($company_logo_one)) }}" class="mt-2" alt="{{$company_name}}" />
		@else
			<p class="pt-2">{{$company_name}}</p>
		@endif
	</div>
	<div class="card-body py-0">
		<h3 class="card-title text-gray-800 fw-bold my-2">Our Website Links</h3>

		<div class="separator separator-dashed my-2"></div>
		<div class="d-flex flex-stack">
			<a href="{{$website_url}}" class="text-primary fw-semibold fs-6 me-2" target="_blank">{{$company_name}}</a>
		</div>
		<div class="separator separator-dashed my-2"></div>
	</div>
</div>
<!--end::Card widget 7-->
