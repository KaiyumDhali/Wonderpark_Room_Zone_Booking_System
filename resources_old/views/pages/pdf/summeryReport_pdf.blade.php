<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $data['company_name'] }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style>
        .double-underline {
            border-bottom: 4px double;
        }

        .page-break {
            page-break-before: always;
        }

        .page-header {
            display: block;
            position: fixed;
            top: 0;
            width: 100%;
            text-align: center;
            margin-top: -40px;
        }
    </style>
</head>

<body style="font-size:12px">
    {{-- include header --}}
    @include('pages.pdf.partials.header_pdf')

    <div class="text-center">
        @if ($data['start_date'] == $data['end_date'])
            <h5 class="pb-0 mb-0 pt-0 pdf_title">Today Summary Report</h5>
            <p>{{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }}</p>
        @else
            <h5 class="pb-0 mb-0 pt-0 pdf_title">Summary Report Date Between</h5>
            <p>Form {{ \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') }} to
                {{ \Carbon\Carbon::parse($data['end_date'])->translatedFormat('d F Y') }}</p>
        @endif
    </div>

    <br>
    <br>


    <div class="card">

        <table>
            <tr>
                <td style="width: 310px; padding: 20px;">
                    <div class="card" style="padding: 30px 10px; text-align: center;">
                        <h4 id="totalSales">{{ $getTotalSummeryReport[0]->total_sales_amount }} BDT</h4>
                        <h4>Total Sales</h4>
                    </div>
                </td>
                <td style="width: 310px; padding: 20px;">
                    <div class="card" style="padding: 30px 10px; text-align: center;">
                        <h4 id="totalPurchase">{{ $getTotalSummeryReport[0]->total_purchase_amount }} BDT</h4>
                        <h4>Total Purchase</h4>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 310px; padding: 20px;">
                    <div class="card" style="padding: 30px 10px; text-align: center;">
                        <h4 id="totalExpense">{{ $getTotalSummeryReport[0]->total_expense_amount }} BDT</h4>
                        <h4>Total Expense</h4>
                    </div>
                </td>
                <td style="width: 310px; padding: 20px;">
                    <div class="card" style="padding: 30px 10px; text-align: center;">
                        <h4 id="currentStockAmount">{{ $getTotalSummeryReport[0]->current_stock_amount }} BDT</h4>
                        <h4>Current Stock Balance</h4>
                    </div>
                </td>
            </tr>
        </table>
    </div>




    {{-- include footer --}}
    @include('pages.pdf.partials.footer_pdf')

</body>

</html>
