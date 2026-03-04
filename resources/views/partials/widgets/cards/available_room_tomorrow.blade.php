<a href="{{ route('room.index') }}" class="card bg-primary card-xl-stretch mb-5 mb-xl-8" style="background-image:url('assets/media/svg/shapes/widget-bg-1.png')">
	<!--begin::Body-->
	<div class="card-body">
		<i class="fa-solid fa-user-plus fs-4x text-light"></i>
		<!--end::Svg Icon-->
		<div class="text-white fw-bold fs-2 mb-2 mt-5">{{$tomorrowAvailableRoomCount}}</div>
		<div class="fw-semibold text-white">Available Rooms Tomorrow</div>
	</div>
	<!--end::Body-->
</a>