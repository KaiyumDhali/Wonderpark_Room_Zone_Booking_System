<div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px position-relative"
    data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end"
    id="kt_menu_item_wow">{!! getIcon('message-text-2', 'fs-2') !!}
    <span id="notificationsIcon"></span>
</div>

<!--begin::Menu-->
<div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true" id="kt_menu_notifications">
    <!--begin::Heading-->
    <div class="d-flex flex-column bgi-no-repeat rounded-top"
        style="background-image: url('{{ asset('assets/media/misc/menu-header-bg.jpg') }}');">
        <!--begin::Title-->
        <h3 class="text-white fw-semibold px-9 mt-10 mb-6">Notifications
            <span class="fs-8 opacity-75 ps-3">reports</span>
        </h3>
        <!--end::Title-->
        <!--begin::Tabs-->
        <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">
            <li class="nav-item">
                <a class="nav-link text-white opacity-75 opacity-state-100 pb-4 active" data-bs-toggle="tab"
                    href="#kt_topbar_notifications_1">Requisitions</a>
            </li>
            {{-- <li class="nav-item">
				<a class="nav-link text-white opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab" href="#kt_topbar_notifications_2">Updates</a>
			</li>
			<li class="nav-item">
				<a class="nav-link text-white opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab" href="#kt_topbar_notifications_3">Logs</a>
			</li> --}}
        </ul>
        <!--end::Tabs-->
    </div>
    <!--end::Heading-->
    <!--begin::Tab content-->
    <div class="tab-content">
        <!--begin::Tab panel requisition_notifications_list-->
        <div class="tab-pane fade show active" id="kt_topbar_notifications_1" role="tabpanel">
            <!--begin::Items-->
            <div class="scroll-y mh-325px my-5 px-8" id="requisition_notifications_list">
                {{-- <div class="d-flex flex-stack py-4">
					<div class="d-flex align-items-center">
						<div class="symbol symbol-35px me-4">
							<span class="symbol-label bg-light-danger">{!! getIcon('information', 'fs-2 text-danger') !!}</span>
						</div>
						<div class="mb-0 me-2">
							<a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">HR Confidential</a>
							<div class="text-gray-400 fs-7">Confidential staff documents</div>
						</div>
					</div>
					<span class="badge badge-light fs-8">2 hrs</span>
				</div> --}}
            </div>

            <!--end::Items-->
            <!--begin::View more-->
            <div class="py-3 text-center border-top">
                <a href="{{ route('production.requisition_list') }}"
                    class="btn btn-color-gray-600 btn-active-color-primary">View All {!! getIcon('arrow-right', 'fs-5') !!}</a>
            </div>
            <!--end::View more-->
        </div>
        <!--end::Tab panel requisition_notifications_list-->
    </div>
    <!--end::Tab content-->
</div>
<!--end::Menu-->
@push('scripts')
    <script>
        function requisitionsNotifications() {
            let url;
            url = '{{ route('requisition_notifications') }}';
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    // leaveReport.clear().draw();
                    console.log('requisitionsNotifications', data);
                    // if (data) {
					if (data && Object.keys(data).length > 0 && Object.values(data).some(arr => Array.isArray(arr) && arr.length > 0)) {

                        $('#jsdataerror').text('');

                        $('#notificationsIcon').html(
                            `<span class="bullet bullet-dot bg-danger h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink"></span>`
                        );

                        $('#requisition_notifications_list').empty();
                        $.each(data, function(key, value) {
                            let requisitionDate = value[0].stock_date;
                            let invoiceNo = value[0].invoice_no;
                            let statusValue = value[0].status;
                            let statusValueApproved = statusValue + 1;
                            // Determine status text
                            let statusTextMap = {
                                0: 'Pending',
                                1: 'Approved by Production Manager',
                                2: 'Approved by Production Manager and CEO',
                                3: 'Approved by Production Manager, CEO and Store Incharge'
                            };
                            let statusText = statusTextMap[statusValue] || 'Unknown Status';

                            let showUrl =
                                `{{ route('requisition_invoice_report_search', ['invoiceNo' => '_invoiceNo_']) }}`
                                .replace('_invoiceNo_', invoiceNo);

                            $('#requisition_notifications_list').append(
                                `
								<div class="d-flex flex-stack py-4">
									<div class="d-flex align-items-center">
										<div class="symbol symbol-35px me-4">
											<span class="symbol-label bg-light-danger">{!! getIcon('information', 'fs-2 text-danger') !!}</span>
										</div>
										<div class="mb-0 me-2">
											<a href="${showUrl}" class="fs-6 text-gray-800 text-hover-primary fw-bold">` + invoiceNo + `</a>
											<div class="text-gray-400 fs-7">` + statusText + `</div>
										</div>
									</div>
									<span class="badge badge-light fs-8"> Requisition Date <br class="my-1">` + requisitionDate + `</span>
								</div>
								`
                            );
                        });

                    } else {
                        $('#jsdataerror').text('No records found');
                        console.log('No records found');
                    }
                }
            });
        }
        requisitionsNotifications();
    </script>
@endpush
