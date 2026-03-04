<a href="#" class="card card-xl-stretch mb-xl-8" style="background-color: #008000;">
    <!--begin::Body-->
    <div class="card-body">
        <i class="fas fa-chart-pie fs-4x text-light"></i>
        <!--end::Svg Icon-->
        <div class="text-white fw-bold fs-2 mb-2 mt-5" id="TotalReceivableAmount">৳</div>
        <div class="fw-semibold text-white">Total Receivable Amount</div>
    </div>
    <!--end::Body-->
</a>
@push('scripts')
<script type="text/javascript">
    function CallBack(pdfdata) {
        let url = '{{ route('total_customer_receivable_list_search', ['pdf' => ':pdf']) }}';
        url = url.replace(':pdf', pdfdata);

        if (pdfdata == 'list') {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    if (data.length > 0) {
                        let totalAmount = 0;
                        $.each(data, function(key, value) {
                            let amount = parseFloat(value.due) || 0;
                            totalAmount += amount;
                        });

                        // Update the TotalReceivableAmount with the calculated total
                        $('#TotalReceivableAmount').text('৳' + totalAmount.toFixed(2));

                    } else {
                        $('#jsdataerror').text('No records found');
                        console.log('No records found');
                    }
                },
                error: function() {
                    $('#jsdataerror').text('An error occurred while fetching data');
                }
            });
        } else if (pdfdata == 'pdfurl') {
            $('#downloadPdf').attr('href', url);
        } else {
            $('#jsdataerror').text('Please select a date');
        }
    }
    // Call the function with the "list" parameter
    CallBack("list");
</script>
@endpush