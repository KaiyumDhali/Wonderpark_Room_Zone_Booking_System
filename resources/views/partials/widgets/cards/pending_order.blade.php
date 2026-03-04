<a href="{{ route('order.index') }}" class="card bg-warning card-xl-stretch mb-xl-8">
	<div class="card-body">
		{{-- <i class="fa-solid fa-list fs-4x text-light"></i> --}}
		<i class="fa-solid fa-list-check fs-4x text-light"></i>
		<!--end::Svg Icon-->
		<div class="text-white fw-bold fs-2 mb-2 mt-5">{{$orderPendingCount}}</div>
		<div class="fw-semibold text-white">Pending Order</div>
	</div>
</a>