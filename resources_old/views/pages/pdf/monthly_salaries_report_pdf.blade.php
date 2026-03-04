
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Perfume PLC.</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style>
        @page {
            size: Legal landscape;
        }
        /* .title_head{
            padding-right: 30% !important;
        } */
    </style>
</head>

<body style="font-size:10px">
    <table class="table table-borderless table-sm m-0">
        <tbody class="text-center">
            <tr>
                {{-- <td> --}}
                    {{-- <img src="assets/media/logos/default-dark.jpeg" alt="SEML" width="160px" height="70" /> --}}
                    {{-- @if($data['branch_id'] == 1)
                        <img src="assets/media/logos/default-dark.jpeg" alt="SEML" width="160px" height="70" />
                    @elseif($data['branch_id'] == 2)
                        <img src="assets/media/logos/default-dark.jpeg" alt="SEML" width="160px" height="70" />
                    @else
                        <img src="assets/media/logos/default-dark.jpeg" alt="SEML" width="160px" height="70" />
                    @endif --}}
                {{-- </td> --}}
                <td>
                    {{-- @if($data['branch_id'] == 1)
                        <h4>Perfume PLC.</h4>
                    @elseif($data['branch_id'] == 2)
                        <h4>Perfume PLC.</h4>
                    @else
                        <h4>Perfume PLC.</h4>
                    @endif --}}
                    <h4>Perfume PLC.</h4>
                    <h6>Employee Salary Sheet <br>
                        Month of {{ \Carbon\Carbon::parse($data['month'])->format('F, Y') }}
                    </h6>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered table-striped table-sm table-hover">
        @if ($monthlySalaries)
            <thead class="text-light bg-secondary ">
                <tr style="text-uppercase text-align: center; vertical-align: middle;">
                    <th class="min-w-50px" style="text-align: center; vertical-align: middle;" rowspan="2">ID</th>
                    {{-- <th class="min-w-100px" rowspan="2">Employee Id</th> --}}
                    <th class="min-w-100px" style="text-align: center; vertical-align: middle;" rowspan="2">Employee Name</th>
                    <th style="text-align: center; vertical-align: middle;" colspan="7">Income</th>
                    <th style="text-align: center; vertical-align: middle;" colspan="3">Deduction</th>
                    <th class="text-start min-w-80px" style="text-align: center; vertical-align: middle;" rowspan="2">Net Payable</th>
                    <th class="text-start min-w-80px" style="text-align: center; vertical-align: middle;" rowspan="2">Remarks</th>
                </tr>
                <!--begin::Table row-->
                <tr class="text-start fs-7 text-uppercase gs-0"
                    style="text-align: center; vertical-align: middle;">
                    <th class="min-w-80px">Basic</th>
                    <th class="min-w-80px">House Rent</th>
                    <th class="min-w-80px">Medical</th>
                    <th class="min-w-80px">Gross Salary</th>
                    <th class="min-w-80px">P/F</th>
                    <th class="min-w-80px">Other</th>
                    <th class="min-w-80px">Total Payable</th>
                    <th class="text-start min-w-80px">Tax</th>
                    <th class="text-start min-w-80px">P/F</th>
                    <th class="text-start min-w-80px">Other</th>
                </tr>
                <!--end::Table row-->
            </thead>
            <tbody>
                @php
                    $SumTotalPayable = 0;
                    $SumTax = 0;
                    $SumDPF = 0;
                    $SumDOther = 0;
                    $SumNetPayable = 0;
                @endphp
                @foreach ($monthlySalaries as $key => $monthlySalarie)
                    @php
                        // Income
                        $baseSalary = floatval($monthlySalarie->Base_Salary) ?? 0;
                        $houseRent = floatval($monthlySalarie->House_Rent) ?? 0;
                        $medical = floatval($monthlySalarie->Medical) ?? 0;
                        $pf = floatval($monthlySalarie->PF) ?? 0;
                        $others = floatval($monthlySalarie->iOthers) ?? 0;
                        // Deduction
                        $TAX = floatval($monthlySalarie->TAX) ?? 0;
                        $dPF = floatval($monthlySalarie->dPF) ?? 0;
                        $dOthers = floatval($monthlySalarie->dOthers) ?? 0;
                        // Calculate total payable
                        $Gross_Salary = $baseSalary + $houseRent + $medical;
                        $Total_Payable = $baseSalary + $houseRent + $medical + $pf + $others;
                        // Calculate total Net Payable
                        $Net_Payable = $Total_Payable - $TAX - $dPF - $dOthers;
                        $Remarks = "";


                        $SumTotalPayable +=$Total_Payable;
                        $SumTax +=$TAX;
                        $SumDPF +=$dPF;
                        $SumDOther +=$dOthers;
                        $SumNetPayable +=$Net_Payable;
                    @endphp
                    <tr>
                        <td>{{ $monthlySalarie->employee_code }}</td>
                        {{-- <td>{{ $monthlySalarie->employee_id }}</td> --}}
                        <td>{{ $monthlySalarie->employee_name }}</td>
                        <td class="text-right">{{ $monthlySalarie->Base_Salary }}</td>
                        <td class="text-right">{{ $monthlySalarie->House_Rent }}</td>
                        <td class="text-right">{{ $monthlySalarie->Medical }}</td>
                        <td class="text-right">{{ $Gross_Salary }}</td>
                        <td class="text-right">{{ $monthlySalarie->PF }}</td>
                        <td class="text-right">{{ $monthlySalarie->iOthers }}</td>
                        <td class="text-right">{{ $Total_Payable }}</td>
                        <td class="text-right">{{ $monthlySalarie->TAX }}</td>
                        <td class="text-right">{{ $monthlySalarie->dPF }}</td>
                        <td class="text-right">{{ $monthlySalarie->dOthers }}</td>
                        <td class="text-right">{{ $Net_Payable }}</td>
                        <td>{{ $Remarks }}</td>
                    </tr>
                @endforeach
                <tr style="text-uppercase">
                    <th class="min-w-50px text-center text-light bg-secondary " colspan="8">Total BDT</th>
                    <th class="min-w-80px text-light bg-secondary text-right">{{ $SumTotalPayable}}</th>
                    <th class="text-start min-w-80px text-light bg-secondary text-right">{{$SumTax}}</th>
                    <th class="text-start min-w-80px text-light bg-secondary text-right">{{$SumDPF}}</th>
                    <th class="text-start min-w-80px text-light bg-secondary text-right">{{$SumDOther}}</th>
                    <th class="text-start min-w-80px text-light bg-secondary text-right">{{$SumNetPayable}}</th>
                    <th class="text-start min-w-80px text-light bg-secondary text-right"></th>
                </tr>
            </tbody>
        @else
            <tr class="text-center">
                <td colspan="5">No Statistics History Found !!!</td>
            </tr>
        @endif
    </table>
    
    <table class="table table-borderless pt-5 mt-5">
            <thead>
                <tr>
                    <th class="text-center" style="border-top: 1px solid">Prepared By</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="text-center" style="border-top: 1px solid">HR & Admin</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="text-center" style="border-top: 1px solid">Accounts & Finance</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="text-center" style="border-top: 1px solid">Approved By</th>
                </tr>
            </thead>
            
    </table>

</body>

</html>
