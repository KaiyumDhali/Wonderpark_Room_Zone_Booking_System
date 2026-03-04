@php
	$companySetting = \App\Models\CompanySetting::where('status', 1)->first();
	if ($companySetting) {
		$company_logo_one = $companySetting->company_logo_one;
		$company_logo_two = $companySetting->company_logo_two;
		$company_name = $companySetting->company_name;
		
	} else {
		$company_logo_one = null;
		$company_logo_two = null;
		$company_name = null;
	}
@endphp
<!--begin::Logo-->
<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
	<!--begin::Logo image-->
	
	<a href="{{route('dashboard')}}">
		{{-- <img src="{{ Storage::url($company_logo_one) }}" class="h-65px app-sidebar-logo-default" alt="{{$company_name}}"/>
		<img src="{{ Storage::url($company_logo_two) }}" class="h-30px app-sidebar-logo-minimize" alt="{{$company_name}}"/> --}}

		<img src="{{ url(Storage::url($company_logo_one)) }}" class="h-65px app-sidebar-logo-default" alt="{{$company_name}}"/>
		<img src="{{ url(Storage::url($company_logo_two)) }}" class="h-30px app-sidebar-logo-minimize" alt="{{$company_name}}"/>
	</a>
	<!--end::Logo image-->
	<!--begin::Sidebar toggle-->
	<!--begin::Minimized sidebar setup:
            if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") {
                1. "src/js/layout/sidebar.js" adds "sidebar_minimize_state" cookie value to save the sidebar minimize state.
                2. Set data-kt-app-sidebar-minimize="on" attribute for body tag.
                3. Set data-kt-toggle-state="active" attribute to the toggle element with "kt_app_sidebar_toggle" id.
                4. Add "active" class to to sidebar toggle element with "kt_app_sidebar_toggle" id.
            }
        -->
	<div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">{!! getIcon('black-left-line', 'fs-3 rotate-180 ms-1') !!}</div>
	<script type="text/javascript">
		var sidebar_toggle = document.getElementById("kt_app_sidebar_toggle");  // Get the sidebar toggle button element
		@if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") 
			document.body.setAttribute("data-kt-app-sidebar-minimize", "on");  // Set the 'data-kt-app-sidebar-minimize' attribute for the body tag
			sidebar_toggle.setAttribute("data-kt-toggle-state", "active");  // Set the 'data-kt-toggle-state' attribute for the sidebar toggle button
			sidebar_toggle.classList.add("active");  // Add the 'active' class to the sidebar toggle button
		@endif
	</script>
	<!--end::Sidebar toggle-->
</div>
<!--end::Logo-->