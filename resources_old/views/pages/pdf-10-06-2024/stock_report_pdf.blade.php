<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $data['company_name'] }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style>
   .pagenum:before {
        content: counter(page);
    }
    </style>
</head>

<body style="font-size:12px">
    <table class="table table-borderless table-sm m-0">
        <tbody>
            <tr class="text-center">
                <td>
                    <h4>{{ $data['company_name'] }}</h4>
                    <h6 class="">Stocks Summary Report as on @php echo date("Y/m/d h:i:s A");@endphp</h6>
                </td>
            </tr>
            <tr>
                {{-- <h6 class="">Employee: {{ $data['employeeInfo']->employee_name }}</h6> --}}
                {{-- <h6 class="">Date: {{ $data['start_date'] }} to {{ $data['end_date'] }}</h6> --}}
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered table-striped table-sm table-hover pt-3">
        <!--begin::Table head-->
        <thead>
            <tr class="text-start text-uppercase text-center text-light bg-success">
                <th class="min-w-50px">Product ID</th>
                <th class="min-w-100px">Product Name</th>
                <th class="min-w-100px">Unit</th>
                <th class="min-w-100px">Product Category</th>
                <th class="min-w-100px">Product Sub Category</th>
                <th class="min-w-100px">Stock</th>
            </tr>
        </thead>
        <tbody class="fw-semibold text-gray-600">
            @foreach ($stocks as $key => $stock)
                <tr>
                    <td class="text-center">
                        <a class="text-dark text-hover-primary">{{ $stock->product_id }}</a>
                    </td>
                    <td class="text-left">{{ $stock->product->product_name }}</td>
                    <td class="text-center">{{ $stock->product->unit->unit_name }}</td>
                    <td class="text-left">{{ $stock->product->category->category_name ?? 'NULL' }}</td>
                    <td class="text-left">{{ $stock->product->subCategory->sub_category_name ?? 'NULL' }}</td>
                    <td class="text-right">{{ $stock->total_quantity }}</td>
                </tr>
            @endforeach
        </tbody>

    </table>
    
    
<footer class="footer fixed-bottom text-center">
    
    <table class="table">
        <tr>
            <td style="border: none; border-bottom: none; border-top: none;">
                Design and Developed By: NRB Telecom Ltd.
            </td>
            <td style="border: none; border-bottom: none; border-top: none;">
                        @php
                            echo date("l, F j, Y h:i A");
                        @endphp
            </td>
            <td style="border: none; border-bottom: none; border-top: none;">
                Page  <span class="pagenum"></span>
            </td>
        </tr>
    </table>
</footer>

</body>

</html>
