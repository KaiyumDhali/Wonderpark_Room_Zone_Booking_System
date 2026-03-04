<a href="{{ route('total_customer_receivable_list') }}" class="card card-xl-stretch mb-xl-8" style="background-color: #008000;">
    <!--begin::Body-->
    <div class="card-body">
        <i class="fas fa-chart-pie fs-4x text-light"></i>
        <div class="text-white fw-bold fs-2 mb-2 mt-5" id="TotalReceivableAmount">৳</div>
        <div class="fw-semibold text-white">Total Receivable Amount</div>
    </div>
    <!--end::Body-->
</a>

@push('scripts')
<script type="text/javascript">
    function updateReceivableAmount(pdfdata = 'list') {
        let url = '{{ route('total_customer_receivable_list_search', ['pdf' => ':pdf']) }}'.replace(':pdf', pdfdata);

        if (pdfdata === 'list') {
            $.get(url, {_token: "{{ csrf_token() }}"}, function(data) {
                if (data.length > 0) {
                    let totalAmount = data.reduce((sum, item) => sum + parseFloat(item.due || 0), 0);
                    $('#TotalReceivableAmount').text('৳' + totalAmount.toFixed(2));
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
    updateReceivableAmount();
</script>
@endpush
