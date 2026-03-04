<x-default-layout>
    {{-- @role('employee') --}}
        @php
            $employeeData = \App\Models\Employee::where('user_id', auth()->user()->id)->first();
            if ($employeeData) {
                $employeeID = $employeeData->id;
                $employeeCode = $employeeData->employee_code;
                $employeeName = $employeeData->employee_name;
                $employeeBranch = $employeeData->branch_id;
                $employeePhoto = $employeeData->photo;
            } else {
                $employeeID = null;
                $employeeCode = null;
                $employeeName = null;
                $employeeBranch = null;
                $employeePhoto = null;
            }
        @endphp

        {{-- @include('pages.dashboards.employee', ['chartData' => json_encode($chartData)]) --}}
        {{-- @include('pages/dashboards/employee') --}}
    {{-- @else --}}
        @php

            $currentDate = now()->toDateString();
            // $coustomerCount = \App\Models\Customer::where('status', 1)->get()->count();
            // $supplierCount = \App\Models\Supplier::where('status', 1)->get()->count();

            $roomTypes = \App\Models\RoomType::where('status', 1)->get()->count();

            $total_room = \App\Models\Room::where('status', 1)->get()->count();
// dd($room);
            $startDate = \Carbon\Carbon::today()->toDateString();
            $endDate = \Carbon\Carbon::tomorrow()->toDateString();

            $ava_room_today = DB::connection()->select('CALL sp_GetBookingCheck_2(?, ?)', [$startDate, $startDate]);

            // dd($ava_room_today);

            $todayAvailableRoomCount = 0;
            foreach ($ava_room_today as $room) {
                if ($room->is_booked == 'Available') {
                    $todayAvailableRoomCount++;
                }
            }
            $todayAvailableRoomCount = $todayAvailableRoomCount ?? 0;



            $ava_room_tomorrow = DB::connection()->select('CALL sp_GetBookingCheck_2(?, ?)', [$endDate, $endDate]);
            $tomorrowAvailableRoomCount = 0;
            foreach ($ava_room_tomorrow as $room) {
                if ($room->is_booked == 'Available') {
                    $tomorrowAvailableRoomCount++;
                }
            }
            $tomorrowAvailableRoomCount = $tomorrowAvailableRoomCount ?? 0;
            
            // dd($todayAvailableRoomCount, $tomorrowAvailableRoomCount);

            $coustomerCount = \App\Models\FinanceAccount::where('account_status', 1)
                ->where('account_group_code', '100020001')
                ->get()
                ->count();
            $supplierCount = \App\Models\FinanceAccount::where('account_status', 1)
                ->where('account_group_code', '400010001')
                ->get()
                ->count();
            $employeeCount = \App\Models\Employee::where('status', 1)->get()->count();
            $productCount = \App\Models\Product::where('status', 1)->count();
            $totalSalesCount = \App\Models\Stock::sum('stock_out_total_amount');
            $todaySalesCount = \App\Models\Stock::whereDate('stock_date', $currentDate)->sum('stock_out_total_amount');
            $currentMonthSalesCount = \App\Models\Stock::whereYear('stock_date', now()->year)
                ->whereMonth('stock_date', now()->month)
                ->sum('stock_out_total_amount');

            $salesData = \App\Models\Stock::selectRaw('DAY(stock_date) as day, SUM(stock_out_total_amount) as total')
                ->whereYear('stock_date', now()->year)
                ->whereMonth('stock_date', now()->month)
                ->groupBy('day')
                ->orderBy('day')
                ->get();
            $days = $salesData->pluck('day');
            $totals = $salesData->pluck('total');

            $totalPurchaseCount = \App\Models\Stock::sum('stock_in_total_amount');
            $todayPurchaseCount = \App\Models\Stock::whereDate('stock_date', $currentDate)->sum(
                'stock_in_total_amount',
            );

            $orderCount = \App\Models\Order::count();
            $orderPendingCount = \App\Models\Order::where('status', 0)->count();
            $orderApprovedCount = \App\Models\Order::where('status', 1)->count();
            $orderList = \App\Models\Order::leftJoin(
                'finance_accounts',
                'finance_accounts.id',
                '=',
                'orders.customer_id',
            )
                ->select('orders.*', 'finance_accounts.account_name')
                ->orderby('id', 'desc')
                ->limit(5)
                ->get();

            $orderData = \App\Models\Order::selectRaw(
                'DAY(orders.order_date) as day, SUM(order_details.stock_out_total_amount) as total',
            )
                ->join('order_details', 'orders.id', '=', 'order_details.order_id') // Adjust the join condition as needed
                ->whereYear('orders.order_date', now()->year) // Specify the table name
                ->whereMonth('orders.order_date', now()->month) // Specify the table name
                ->groupBy('day')
                ->orderBy('day')
                ->get();
            $orderDays = $orderData->pluck('day');
            $orderTotals = $orderData->pluck('total');
            // dd($todayAvailableRoomCount, $tomorrowAvailableRoomCount);
        @endphp







        <!--begin::Row-->
        <div class="row g-5 g-xl-8">

            <div class="col-xl-2">
                @include('partials/widgets/cards/total_customer')
            </div>

            <div class="col-xl-2">
                @include('partials/widgets/cards/total_room')
            </div>

            <div class="col-xl-2">
                @include('partials/widgets/cards/total_room_type')
            </div>

            <div class="col-xl-2">
                @include('partials/widgets/cards/available_room_today')
            </div>

            <div class="col-xl-2">
                @include('partials/widgets/cards/available_room_tomorrow')
            </div>


        </div>
        <!--end::Row-->



    {{-- @endrole --}}



</x-default-layout>
