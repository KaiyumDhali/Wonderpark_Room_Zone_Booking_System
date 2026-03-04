<a href="{{ route('total_supplier_payable_list') }}" class="card card-xl-stretch mb-5 mb-xl-8" style="background-color: #00CCDD;">
    <!--begin::Body-->
    <div class="card-body">
		<i class="fa-solid fa-money-bill-trend-up fs-4x text-light"></i>
        <div class="text-white fw-bold fs-2 mb-2 mt-5" id="TotalPayableAmount">৳</div>
        <div class="fw-semibold text-white">Today Payment Amount</div>
    </div>
    <!--end::Body-->
</a>

@push('scripts')
<script type="text/javascript">
    function updatePayableAmount(pdfdata = 'list') {
        let url = '{{ route('total_supplier_payable_list_search', ['pdf' => ':pdf']) }}'.replace(':pdf', pdfdata);

        if (pdfdata === 'list') {
            $.get(url, {_token: "{{ csrf_token() }}"}, function(data) {
                if (data.length > 0) {
                    let totalAmount = data.reduce((sum, item) => sum + parseFloat(item.due || 0), 0);
                    $('#TotalPayableAmount').text('৳' + totalAmount.toFixed(2));
                } else {
                    console.log('No records found');
                }
            }).fail(function() {
                console.log('An error occurred while fetching data');
            });
        } else if (pdfdata === 'pdfurl') {
            $('#downloadPdf').attr('href', url);
        } else {
            console.log('Please select a date');
        }
    }
    updatePayableAmount();
</script>
@endpush
