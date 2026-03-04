<!--begin::Card widget 7-->
<a href="{{ route('order.index') }}" class="card card-flush h-md-50 mb-5 mb-xl-10 bg-light-danger">
	<!--begin::Header-->
	<div class="card-header pt-15">
		<!--begin::Title-->
		<div class="card-title d-flex flex-column" >
			<!--begin::Amount-->
			<h1 class="text-dark fw-bold" style="font-size: 42px;">{{$orderPendingCount}}</h1>
			<!--end::Amount-->
			<!--begin::Subtitle-->
			<h4 class="text-dark pt-3 fw-semibold" style="font-size: 22px">Total Order Pending</h4>
			<!--end::Subtitle-->
		</div>
		<!--end::Title-->
	</div>
	<!--end::Header-->
</a>