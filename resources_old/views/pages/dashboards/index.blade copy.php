<x-default-layout>
    @role('employee')
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
        @include('pages/dashboards/employee')

    @else


        @php
            $currentDate = now()->toDateString();
            // $coustomerCount = \App\Models\Customer::where('status', 1)->get()->count();
            // $supplierCount = \App\Models\Supplier::where('status', 1)->get()->count();


            $roomTypes = \App\Models\RoomType::where('status', 1)->get()->count();
            $room = \App\Models\Room::where('status', 1)->get()->count();



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

            
            $orderData = \App\Models\Order::selectRaw('DAY(orders.order_date) as day, SUM(order_details.stock_out_total_amount) as total')
                ->join('order_details', 'orders.id', '=', 'order_details.order_id') // Adjust the join condition as needed
                ->whereYear('orders.order_date', now()->year) // Specify the table name
                ->whereMonth('orders.order_date', now()->month) // Specify the table name
                ->groupBy('day')
                ->orderBy('day')
                ->get();
            $orderDays = $orderData->pluck('day');
            $orderTotals = $orderData->pluck('total');
        @endphp


        @section('title')
            Dashboard
        @endsection





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



            <div class="col-xl-2">
                @include('partials/widgets/cards/total_supplier')
            </div>

            
            <div class="col-xl-2">
                @include('partials/widgets/cards/total_products')
            </div>

            {{-- order --}}
            <div class="col-xl-2">
                @include('partials/widgets/cards/total_order')
            </div>
            <div class="col-xl-2">
                @include('partials/widgets/cards/pending_order')
            </div>
            <div class="col-xl-2">
                @include('partials/widgets/cards/approved_order')
            </div>

            {{-- 2nd row --}}

            {{-- purchase --}}
            <div class="col-xl-3">
                @include('partials/widgets/cards/today_purchase')
            </div>
            <div class="col-xl-3">
                @include('partials/widgets/cards/total_purchase')
            </div>

            {{-- sales --}}
            <div class="col-xl-3">
                @include('partials/widgets/cards/today_sales')
            </div>
            <div class="col-xl-3">
                @include('partials/widgets/cards/total_sales')
            </div>

            {{-- Due --}}
            <div class="col-xl-3">
                @include('partials/widgets/cards/total_payable_amount')
            </div>
            <div class="col-xl-3">
                @include('partials/widgets/cards/total_receivable_amount')
            </div>

            {{-- Cash --}}
            <div class="col-xl-3">
                @include('partials/widgets/cards/today_received_amount')
            </div>
            {{-- Bank --}}
            <div class="col-xl-3">
                @include('partials/widgets/cards/today_payment_amount')
            </div>
        </div>
        <!--end::Row-->

        <!--begin::Row-->
        {{-- <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                @include('partials/widgets/cards/logo')
                @include('partials/widgets/cards/total_customer')
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                @include('partials/widgets/cards/total_products')
                @include('partials/widgets/cards/total_supplier')
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                @include('partials/widgets/cards/today_sales')
                @include('partials/widgets/cards/total_sales')
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                @include('partials/widgets/cards/today_purchase')
                @include('partials/widgets/cards/total_purchase')
            </div>
        </div> --}}
        <!--end::Row-->

        <!--begin::Row-->
        <div class="row g-5 g-xl-10 mb-5 mt-2 mb-xl-10 mt-xl-4">
            <div class="col-xxl-6">
                {{-- <div class="card card-flush overflow-hidden h-md-100 p-3">
                    <h2 style="text-align: center;">Sales Chart for the Current Month</h2>
                    <canvas id="salesChart"></canvas>
                </div> --}}
                @include('partials/widgets/charts/product_sales_this_month')
            </div>
            <div class="col-xxl-6">
                @include('partials/widgets/charts/product_order_charts')
            </div>
        </div>
        <!--end::Row-->

        <!--begin::Row-->
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <div class="col-xl-12">
                @include('partials/widgets/tables/product_order_list')
                {{-- @include('partials/widgets/tables/attendance_report') --}}
                {{-- @include('partials/widgets/tables/_widget-14') --}}
            </div>
            <!--begin::Col-->
            {{-- <div class="col-xl-12">
                @include('partials/widgets/tables/product_stock_finish_good')
            </div> --}}
            <!--end::Col-->
        </div>
        <!--end::Row-->

    @endrole


    @push('scripts')
        <script>
            const orderctx = document.getElementById('orderChart').getContext('2d');
            const orderChart = new Chart(orderctx, {
                type: 'line', // Type of chart (line chart)
                data: {
                    labels: @json($orderDays), // X-axis labels (Days of the month)
                    datasets: [{
                        label: 'Order Total (in currency)', // Label for the dataset
                        data: @json($orderTotals), // Y-axis data (Order Totals)
                        borderColor: 'rgba(0, 123, 255, 0.9)', // Line color
                        backgroundColor: 'rgba(0, 123, 255, 0.2)', // Primary color with 20% opacity
                        borderWidth: 2, // Line thickness
                        fill: true, // Fill the area under the line
                        pointBackgroundColor: 'rgba(0, 123, 255, 0.9)', // Point color
                        pointBorderColor: '#fff', // Point border color
                        pointHoverRadius: 7, // Point size when hovered
                        pointHoverBackgroundColor: 'rgba(0, 123, 255, 0.9)', // Point background color when hovered
                        pointHoverBorderColor: 'rgba(0, 123, 255, 0.9)', // Point border color when hovered
                        pointRadius: 5, // Point size
                        pointHitRadius: 10, // Area around the point that can be hovered
                    }]
                },
                options: {
                    responsive: true, // Make the chart responsive
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Day of the Month' // X-axis title
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Order Total (in currency)' // Y-axis title
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true, // Show the legend
                            position: 'top', // Position of the legend
                        },
                        title: {
                            display: true, // Display a title on the chart
                            text: 'Daily Order Totals for the Current Month', // Title text
                        },
                        tooltip: {
                            callbacks: {
                                // Tooltip label callback to display both day and amount
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += context.raw.toLocaleString(); // Convert number to string with commas
                                    return label;
                                },
                                title: function(tooltipItems) {
                                    return 'Day ' + tooltipItems[0].label; // Customize title to show 'Day X'
                                }
                            }
                        }
                    },
                    hover: {
                        mode: 'nearest', // Set hover mode to nearest point
                        intersect: true // Hover intersects only the points, not the lines
                    }
                }
            });
        </script>
        <script>
            // Initialize the chart
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(ctx, {
                type: 'line', // Type of chart (line chart)
                data: {
                    labels: @json($days), // X-axis labels (Days of the month)
                    datasets: [{
                        label: 'Sales Amount (in currency)', // Label for the dataset
                        data: @json($totals), // Y-axis data (Sales Amount)
                        borderColor: 'rgba(75, 192, 192, 1)', // Line color
                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Fill under the line
                        borderWidth: 2, // Line thickness
                        fill: true, // Fill the area under the line
                    }]
                },
                options: {
                    responsive: true, // Make the chart responsive
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Day of the Month' // X-axis title
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Sales Amount (in currency)' // Y-axis title
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true, // Show the legend
                            position: 'top', // Position of the legend
                        },
                        title: {
                            display: true, // Display a title on the chart
                            text: 'Daily Sales Amount for the Current Month', // Title text
                        },
                    },
                }
            });
        </script>
        <script>
            am5.ready(function() {

                // Create root element
                // https://www.amcharts.com/docs/v5/getting-started/#Root_element
                var root = am5.Root.new("kt_amcharts_1");

                // Set themes
                // https://www.amcharts.com/docs/v5/concepts/themes/
                root.setThemes([
                    am5themes_Animated.new(root)
                ]);

                // Create chart
                // https://www.amcharts.com/docs/v5/charts/xy-chart/
                var chart = root.container.children.push(am5xy.XYChart.new(root, {
                    panX: false,
                    panY: false,
                    wheelX: "panX",
                    wheelY: "zoomX",
                    layout: root.verticalLayout
                }));

                // Add legend
                // https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
                var legend = chart.children.push(
                    am5.Legend.new(root, {
                        centerX: am5.p50,
                        x: am5.p50
                    })
                );

                var data = [...]

                // Create axes
                // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
                var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                    categoryField: "year",
                    renderer: am5xy.AxisRendererX.new(root, {
                        cellStartLocation: 0.1,
                        cellEndLocation: 0.9
                    }),
                    tooltip: am5.Tooltip.new(root, {})
                }));

                xAxis.data.setAll(data);

                var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                    renderer: am5xy.AxisRendererY.new(root, {})
                }));

                // Add series
                // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
                function makeSeries(name, fieldName) {
                    var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                        name: name,
                        xAxis: xAxis,
                        yAxis: yAxis,
                        valueYField: fieldName,
                        categoryXField: "year"
                    }));

                    series.columns.template.setAll({
                        tooltipText: "{name}, {categoryX}:{valueY}",
                        width: am5.percent(90),
                        tooltipY: 0
                    });

                    series.data.setAll(data);

                    // Make stuff animate on load
                    // https://www.amcharts.com/docs/v5/concepts/animations/
                    series.appear();

                    series.bullets.push(function() {
                        return am5.Bullet.new(root, {
                            locationY: 0,
                            sprite: am5.Label.new(root, {
                                text: "{valueY}",
                                fill: root.interfaceColors.get("alternativeText"),
                                centerY: 0,
                                centerX: am5.p50,
                                populateText: true
                            })
                        });
                    });

                    legend.data.push(series);
                }

                makeSeries("Europe", "europe");
                makeSeries("North America", "namerica");
                makeSeries("Asia", "asia");
                makeSeries("Latin America", "lamerica");
                makeSeries("Middle East", "meast");
                makeSeries("Africa", "africa");


                // Make stuff animate on load
                // https://www.amcharts.com/docs/v5/concepts/animations/
                chart.appear(1000, 100);

            }); // end am5.ready()
        </script>
    @endpush

</x-default-layout>
