<a href="{{ route('purchase_invoice_list') }}" class="card bg-info  card-xl-stretch mb-5 mb-xl-8">
	<!--begin::Body-->
	<div class="card-body">
		<i class="fa-solid fa-money-bill-transfer fs-4x text-light"></i>
		<!--end::Svg Icon-->
		<div class="text-white fw-bold fs-2 mb-2 mt-5">{{ formatCurrency($todayPurchaseCount) }} ৳</div>
		<div class="fw-semibold text-white">Today Purchase Amount</div>
	</div>
	<!--end::Body-->
</a>