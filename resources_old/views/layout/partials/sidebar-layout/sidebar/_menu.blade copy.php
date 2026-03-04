<!--begin::sidebar menu-->
<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <!--begin::Menu wrapper-->
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true"
        data-kt-scroll-activate="true" data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
        data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">

        @role('employee')
            <div class="menu menu-column menu-rounded menu-sub-indention px-3 fw-semibold fs-6" id="#kt_app_sidebar_menu"
                data-kt-menu="true" data-kt-menu-expand="false">

                <!--begin:Menu item-->
                <div class="menu-item menu-accordion {{ request()->routeIs('dashboard') ? 'here show' : '' }}">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="bi bi-grid-fill fs-2"></i>
                        <span class="menu-title ms-4">Dashboards</span>
                        {{-- <span class="menu-arrow"></span> --}}
                    </a>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->

                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->routeIs('employee_profile.*') ? 'active' : '' }}"
                        href="{{ route('employee_profile') }}">
                        <i class="bi bi-person-bounding-box fs-2"></i>
                        <span class="menu-title ms-4">Employee Profile</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->

                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->routeIs('single_employee_all_attendance_search.*') ? 'active' : '' }}"
                        href="{{ route('single_employee_all_attendance_search') }}">
                        {{-- <i class="bi bi-window-plus fs-2"></i> --}}
                        <i class="bi bi-card-list fs-2"></i>
                        <span class="menu-title ms-4">Attendance Report</span>
                    </a>
                    <!--end:Menu link-->
                </div>



                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->routeIs('employee_profile_leave_entry.*') ? 'active' : '' }}"
                        href="{{ route('employee_profile_leave_entry') }}">
                        <i class="bi bi-window-plus fs-2"></i>
                        <span class="menu-title ms-4">Employee Leave</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->routeIs('employee_profile_ledger.*') ? 'active' : '' }}"
                        href="{{ route('employee_profile_ledger') }}">
                        <i class="bi bi-card-checklist fs-2"></i>
                        <span class="menu-title ms-4">Ledgers Details</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->


            </div>
        @else
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention px-3 fw-semibold fs-6" id="#kt_app_sidebar_menu"
                data-kt-menu="true" data-kt-menu-expand="false">


                <!--begin:Menu Dashboards -->
                <div class="menu-item menu-accordion {{ request()->routeIs('dashboard') ? 'here show' : '' }}">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="bi bi-grid-fill fs-2"></i>
                        <span class="menu-title ms-4">Dashboards</span>
                        {{-- <span class="menu-arrow"></span> --}}
                    </a>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->

                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->


                <!--begin:Menu Room Type -->
                <div class="menu-item menu-accordion {{ request()->routeIs('room_type') ? 'here show' : '' }}">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->routeIs('room_type') ? 'active' : '' }}"
                        href="{{ route('room_type.index') }}">
                        <i class="bi bi-grid-fill fs-2"></i>
                        <span class="menu-title ms-4">Room Type</span>
                        {{-- <span class="menu-arrow"></span> --}}
                    </a>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->

                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->

                <!--begin:Menu Room List -->
                <div class="menu-item menu-accordion {{ request()->routeIs('room') ? 'here show' : '' }}">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->routeIs('room') ? 'active' : '' }}"
                        href="{{ route('room.index') }}">
                        <i class="bi bi-grid-fill fs-2"></i>
                        <span class="menu-title ms-4">Room List</span>
                        {{-- <span class="menu-arrow"></span> --}}
                    </a>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->

                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->

                <!--begin:Menu Room List -->
                <div class="menu-item menu-accordion {{ request()->routeIs('booking') ? 'here show' : '' }}">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->routeIs('booking') ? 'active' : '' }}"
                        href="{{ route('booking.index') }}">
                        <i class="bi bi-grid-fill fs-2"></i>
                        <span class="menu-title ms-4">Booking</span>
                        {{-- <span class="menu-arrow"></span> --}}
                    </a>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->

                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->

                {{-- Add: Customer Menu item --}}
                @can('read customer')
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('customers*', 'customer_type*', 'customer_ledgers*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-person-vcard-fill fs-2"></i>
                            <span class="menu-title ms-4"> Customer</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                @can('read customer type')
                                    <a class="menu-link {{ request()->routeIs('customer_type.*') ? 'active' : '' }}"
                                        href="{{ route('customer_type.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Customer Type</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read customer')
                                    <a class="menu-link {{ request()->routeIs('customers.*') ? 'active' : '' }}"
                                        href="{{ route('customers.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Add Customer</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                {{-- @can('read customer ledger')
                                            <a class="menu-link {{ request()->routeIs('customer_ledgers.*') ? 'active' : '' }}"
                                                href="{{ route('customer_ledgers.index') }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Customer Ledgers</span>
                                            </a>
                                        @endcan --}}
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcan
                {{-- End: Customer Menu item --}}

                {{-- Add: General Setting item --}}
                @canany(['read company setting'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('emp_company*', 'emp_branch*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-gear fs-2"></i>
                            <span class="menu-title ms-4"> General Setting</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                @can('read company setting')
                                    <a class="menu-link {{ request()->routeIs('emp_company.*') ? 'active' : '' }}"
                                        href="{{ route('emp_company.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Company Setting</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                {{-- @can('read employee branch')
                                <a class="menu-link {{ request()->routeIs('emp_branch.*') ? 'active' : '' }}"
                                    href="{{ route('emp_branch.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Branch </span>
                                </a>
                            @endcan --}}
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                {{-- @can('read employee department')
                                <a class="menu-link {{ request()->routeIs('emp_department.*') ? 'active' : '' }}"
                                    href="{{ route('emp_department.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title"> Department</span>
                                </a>
                            @endcan --}}
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                {{-- @can('read employee designation')
                                <a class="menu-link {{ request()->routeIs('emp_designation.*') ? 'active' : '' }}"
                                    href="{{ route('emp_designation.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title"> Designation</span>
                                </a>
                            @endcan --}}
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany
                {{-- End: General Setting item --}}


                {{-- Add: Supplier Menu item --}}
                @can('read supplier')
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('suppliers*', 'supplier_ledgers*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-person-video2 fs-2"></i>
                            <span class="menu-title ms-4"> Supplier</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                @can('read supplier')
                                    <a class="menu-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}"
                                        href="{{ route('suppliers.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Add Supplier</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                {{-- @can('read supplier ledger')
                                <a class="menu-link {{ request()->routeIs('supplier_ledgers.*') ? 'active' : '' }}"
                                    href="{{ route('supplier_ledgers.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Supplier Ledgers</span>
                                </a>
                            @endcan --}}
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcan
                {{-- End: Supplier Menu item --}}



                {{-- Add: Product item --}}
                @canany(['read product'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs(
                            'products*',
                            'product_type*',
                            'product_fragrance*',
                            'categories.*',
                            'sub_category*',
                            'brands*',
                            'colors*',
                            'sizes*',
                            'units*',
                        )
                            ? 'here show'
                            : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-bag-check fs-2"></i>
                            <span class="menu-title ms-4"> Product</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                @can('read product')
                                    <a class="menu-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                                        href="{{ route('products.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Add Product</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read product type')
                                    <a class="menu-link {{ request()->routeIs('product_type.index*') ? 'active' : '' }}"
                                        href="{{ route('product_type.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Product Type</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read category')
                                    <a class="menu-link {{ request()->routeIs('categories.index*') ? 'active' : '' }}"
                                        href="{{ route('categories.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Category</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read sub category')
                                    <a class="menu-link {{ request()->routeIs('sub_category.*') ? 'active' : '' }}"
                                        href="{{ route('sub_category.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Sub Category</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read brand')
                                    <a class="menu-link {{ request()->routeIs('brands.*') ? 'active' : '' }}"
                                        href="{{ route('brands.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Brand</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read product fragrance')
                                    <a class="menu-link {{ request()->routeIs('product_fragrance.*') ? 'active' : '' }}"
                                        href="{{ route('product_fragrance.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Fragrance</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read color')
                                    <a class="menu-link {{ request()->routeIs('colors.*') ? 'active' : '' }}"
                                        href="{{ route('colors.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Color</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read size')
                                    <a class="menu-link {{ request()->routeIs('sizes.*') ? 'active' : '' }}"
                                        href="{{ route('sizes.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Size</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read unit')
                                    <a class="menu-link {{ request()->routeIs('units.*') ? 'active' : '' }}"
                                        href="{{ route('units.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Unit</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany
                {{-- End: Product Menu item --}}

                {{-- Add: Product purchase item --}}
                @canany(['read purchase', 'read purchase report'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('purchase*', 'purchase_invoice_return_list', 'purchase_invoice_edit', 'purchase_invoice_list*', 'supplier_wise_purchase_list*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-bag-check fs-2"></i>
                            <span class="menu-title ms-4"> Purchase</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                @can('read purchase')
                                    <a class="menu-link {{ request()->routeIs('purchase.*') ? 'active' : '' }}"
                                        href="{{ route('purchase.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Product Purchase</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->

                                <!--begin:Menu link-->
                                @can('read purchase report')
                                    <a class="menu-link {{ request()->routeIs('purchase_invoice_return_list*') ? 'active' : '' }}"
                                        href="{{ route('purchase_invoice_return_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Purchase Return Report</span>
                                    </a>
                                @endcan
                                @can('read purchase report')
                                    <a class="menu-link {{ request()->routeIs('purchase_invoice_list*', 'purchase_invoice_edit*', 'purchase_invoice_details*') ? 'active' : '' }}"
                                        href="{{ route('purchase_invoice_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Invoice Wise Purchase Report</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->

                                <!--begin:Menu link-->
                                @can('read purchase report')
                                    <a class="menu-link {{ request()->routeIs('supplier_wise_purchase_list*') ? 'active' : '' }}"
                                        href="{{ route('supplier_wise_purchase_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Supplier Wise Purchase Register Report</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany
                {{-- End: Product purchase Menu item --}}

                {{-- Add: Product sales item --}}
                @canany(['read sales', 'read sales report', 'sales report datewise', 'item wise profit'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('sales*', 'sales_invoice_return_list', 'sales_invoice_return_details', 'sales_invoice_edit', 'item_wise_profit_list', 'invoice_wise_profit_list', 'item_wise_sales_list', 'sales_invoice_list*', 'customer_wise_sales_list*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-bag-check fs-2"></i>
                            <span class="menu-title ms-4"> Sales</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">

                                <!--begin:Menu link-->
                                @can('read sales')
                                    <a class="menu-link {{ request()->routeIs('sales.*') ? 'active' : '' }}"
                                        href="{{ route('sales.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Product Sales</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->

                                <!--begin:Menu link-->
                                @can('read sales report')
                                    <a class="menu-link {{ request()->routeIs('sales_challan_list*', 'sales_challan_details*') ? 'active' : '' }}"
                                        href="{{ route('sales_challan_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Challan Wise Sales Reports</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read sales report')
                                    <a class="menu-link {{ request()->routeIs('sales_invoice_list*', 'sales_invoice_edit*', 'sales_invoice_details*') ? 'active' : '' }}"
                                        href="{{ route('sales_invoice_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Invoice Wise Sales Reports</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read sales report')
                                    <a class="menu-link {{ request()->routeIs('sales_invoice_return_list*', 'sales_invoice_return_details*') ? 'active' : '' }}"
                                        href="{{ route('sales_invoice_return_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Invoice Wise Sales Return Reports</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->

                                <!--begin:Menu link-->
                                @can('read sales report datewise')
                                    <a class="menu-link {{ request()->routeIs('customer_wise_sales_list*') ? 'active' : '' }}"
                                        href="{{ route('customer_wise_sales_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Customer Wise Sales Reports</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->

                                <!--begin:Menu link-->
                                {{-- @can('read sales report datewise')
                                    <a class="menu-link {{ request()->routeIs('item_wise_sales_list*') ? 'active' : '' }}"
                                        href="{{ route('item_wise_sales_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Item Wise Sales Reports</span>
                                    </a>
                                @endcan --}}
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read item wise profit')
                                    <a class="menu-link {{ request()->routeIs('item_wise_profit_list*') ? 'active' : '' }}"
                                        href="{{ route('item_wise_profit_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Item Wise Profit Reports</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read invoice wise profit')
                                    <a class="menu-link {{ request()->routeIs('invoice_wise_profit_list*') ? 'active' : '' }}"
                                        href="{{ route('invoice_wise_profit_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Invoice Wise Profit Reports</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->


                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany
                {{-- End: Product sales Menu item --}}

                {{-- Add: Service --}}
                @canany(['read product service', 'read pending product service', 'read complete product service'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('product_services*', 'pending_product_services*', 'complete_product_services*', 'service_invoice*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-gear fs-2"></i>
                            <span class="menu-title ms-4"> Service</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                @can('read product service')
                                    <a class="menu-link {{ request()->routeIs('product_services') ? 'active' : '' }}"
                                        href="{{ route('product_services') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Product Services</span>
                                    </a>
                                @endcan
                            </div>
                            <!--end:Menu item-->
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                @can('read pending product service')
                                    <a class="menu-link {{ request()->routeIs('pending_product_services*') ? 'active' : '' }}"
                                        href="{{ route('pending_product_services') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Pending Product Services</span>
                                    </a>
                                @endcan
                            </div>
                            <!--end:Menu item-->
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                @can('read complete product service')
                                    <a class="menu-link {{ request()->routeIs('complete_product_services*') ? 'active' : '' }}"
                                        href="{{ route('complete_product_services') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Complete Product Services</span>
                                    </a>
                                @endcan
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany
                {{-- End: Service --}}

                {{-- Add: Service --}}
                {{-- @canany(['read company setting'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('product_services*','pending_product_services*' ) ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-gear fs-2"></i>
                            <span class="menu-title ms-4"> Service</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                @can('read company setting')
                                    <a class="menu-link {{ request()->routeIs('product_services*') ? 'active' : '' }}"
                                        href="{{ route('product_services') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Product Services</span>
                                    </a>
                                @endcan
                            </div>
                            <!--end:Menu item-->
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                @can('read company setting')
                                    <a class="menu-link {{ request()->routeIs('pending_product_services*') ? 'active' : '' }}"
                                        href="{{ route('pending_product_services') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Pending Product Services</span>
                                    </a>
                                @endcan
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany --}}
                {{-- End: Service --}}

                {{-- Add: Order Menu item --}}
                @canany(['read order'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('order*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <span class="menu-icon"><span class="svg-icon svg-icon-success svg-icon-1">
                                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3"
                                            d="M8.9 21L7.19999 22.6999C6.79999 23.0999 6.2 23.0999 5.8 22.6999L4.1 21H8.9ZM4 16.0999L2.3 17.8C1.9 18.2 1.9 18.7999 2.3 19.1999L4 20.9V16.0999ZM19.3 9.1999L15.8 5.6999C15.4 5.2999 14.8 5.2999 14.4 5.6999L9 11.0999V21L19.3 10.6999C19.7 10.2999 19.7 9.5999 19.3 9.1999Z"
                                            fill="currentColor" />
                                        <path
                                            d="M21 15V20C21 20.6 20.6 21 20 21H11.8L18.8 14H20C20.6 14 21 14.4 21 15ZM10 21V4C10 3.4 9.6 3 9 3H4C3.4 3 3 3.4 3 4V21C3 21.6 3.4 22 4 22H9C9.6 22 10 21.6 10 21ZM7.5 18.5C7.5 19.1 7.1 19.5 6.5 19.5C5.9 19.5 5.5 19.1 5.5 18.5C5.5 17.9 5.9 17.5 6.5 17.5C7.1 17.5 7.5 17.9 7.5 18.5Z"
                                            fill="currentColor" />
                                    </svg>
                                </span></span>
                            <span class="menu-title"> Order</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->

                                <a class="menu-link {{ request()->routeIs('order.*', 'orderEdit', 'orderView') ? 'active' : '' }}"
                                    href="{{ route('order.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Product Order</span>
                                </a>

                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->

                                <a class="menu-link {{ request()->routeIs('order.*', 'orderEdit', 'orderView', 'orderSummary') ? 'active' : '' }}"
                                    href="{{ route('orderSummary') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Order Summary Report</span>
                                </a>

                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany
                {{-- End: Order Menu item --}}

                {{-- Add: Damage Product Entry --}}

                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->routeIs('damage*') ? 'here show' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon"><span class="svg-icon svg-icon-success svg-icon-1">
                                <svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.3"
                                        d="M8.9 21L7.19999 22.6999C6.79999 23.0999 6.2 23.0999 5.8 22.6999L4.1 21H8.9ZM4 16.0999L2.3 17.8C1.9 18.2 1.9 18.7999 2.3 19.1999L4 20.9V16.0999ZM19.3 9.1999L15.8 5.6999C15.4 5.2999 14.8 5.2999 14.4 5.6999L9 11.0999V21L19.3 10.6999C19.7 10.2999 19.7 9.5999 19.3 9.1999Z"
                                        fill="currentColor" />
                                    <path
                                        d="M21 15V20C21 20.6 20.6 21 20 21H11.8L18.8 14H20C20.6 14 21 14.4 21 15ZM10 21V4C10 3.4 9.6 3 9 3H4C3.4 3 3 3.4 3 4V21C3 21.6 3.4 22 4 22H9C9.6 22 10 21.6 10 21ZM7.5 18.5C7.5 19.1 7.1 19.5 6.5 19.5C5.9 19.5 5.5 19.1 5.5 18.5C5.5 17.9 5.9 17.5 6.5 17.5C7.1 17.5 7.5 17.9 7.5 18.5Z"
                                        fill="currentColor" />
                                </svg>
                            </span></span>
                        <span class="menu-title"> Damage Product</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->

                            <a class="menu-link {{ request()->routeIs('damage.*') ? 'active' : '' }}"
                                href="{{ route('damage.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Damage Product Entry</span>
                            </a>

                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->

                </div>

                {{-- End: Damage Product Entry --}}

                {{-- Add: Product Stock item --}}
                @canany(['read stock report'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs(
                            'stock_report*',
                            'finish_good_wise_stock_report*',
                            'material_wise_stock_report*',
                            'item_wise_stock_report*',
                        )
                            ? 'here show'
                            : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-bag-check fs-2"></i>
                            <span class="menu-title ms-4"> Product Stock</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                {{-- @can('read stock report')
                                <a class="menu-link {{ request()->routeIs('stock_report*') ? 'active' : '' }}"
                                    href="{{ route('stock_report') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Today Stock Report</span>
                                </a>
                            @endcan --}}
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read stock report')
                                    <a class="menu-link {{ request()->routeIs('finish_good_wise_stock_report*') ? 'active' : '' }}"
                                        href="{{ route('finish_good_wise_stock_report') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Finish Good Wise Stock</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read stock report')
                                    <a class="menu-link {{ request()->routeIs('material_wise_stock_report*') ? 'active' : '' }}"
                                        href="{{ route('material_wise_stock_report') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Material Wise Stock</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read stock report')
                                    <a class="menu-link {{ request()->routeIs('item_wise_stock_report*') ? 'active' : '' }}"
                                        href="{{ route('item_wise_stock_report') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Item Wise Stock</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany
                {{-- End: Product Menu item --}}

                {{-- Add: Product warehouse --}}
                @canany(['read warehouse'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('warehouse*', 'stock_transfer*', 'invoice_wise_stock_transfer_list*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-bag-check fs-2"></i>
                            <span class="menu-title ms-4"> Warehouse Management</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                @can('read warehouse')
                                    <a class="menu-link {{ request()->routeIs('warehouse*') ? 'active' : '' }}"
                                        href="{{ route('warehouse.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Add Warehouse</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read warehouse')
                                    <a class="menu-link {{ request()->routeIs('stock_transfer*') ? 'active' : '' }}"
                                        href="{{ route('stock_transfer') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Warehouse Stock Transfer</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read warehouse')
                                    <a class="menu-link {{ request()->routeIs('invoice_wise_stock_transfer_list*') ? 'active' : '' }}"
                                        href="{{ route('invoice_wise_stock_transfer_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Warehouse Stock Transfer List</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany
                {{-- End: Product warehouse --}}

                {{-- Add: Production Menu item --}}
                @canany(['read billofmaterials', 'read requisition', 'read requisition list', 'read requisition approved
                    list'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('cost_sheet*', 'product_order*', 'billofmaterials*', 'requisitions*', 'additional_requisition_add*', 'production.requisition_list*', 'production.requisition_approved_list*', 'finalproductions*', 'production.finalproduction_report*', 'finalproduction_report_search*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <span class="menu-icon"><span class="svg-icon svg-icon-success svg-icon-1">
                                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3"
                                            d="M8.9 21L7.19999 22.6999C6.79999 23.0999 6.2 23.0999 5.8 22.6999L4.1 21H8.9ZM4 16.0999L2.3 17.8C1.9 18.2 1.9 18.7999 2.3 19.1999L4 20.9V16.0999ZM19.3 9.1999L15.8 5.6999C15.4 5.2999 14.8 5.2999 14.4 5.6999L9 11.0999V21L19.3 10.6999C19.7 10.2999 19.7 9.5999 19.3 9.1999Z"
                                            fill="currentColor" />
                                        <path
                                            d="M21 15V20C21 20.6 20.6 21 20 21H11.8L18.8 14H20C20.6 14 21 14.4 21 15ZM10 21V4C10 3.4 9.6 3 9 3H4C3.4 3 3 3.4 3 4V21C3 21.6 3.4 22 4 22H9C9.6 22 10 21.6 10 21ZM7.5 18.5C7.5 19.1 7.1 19.5 6.5 19.5C5.9 19.5 5.5 19.1 5.5 18.5C5.5 17.9 5.9 17.5 6.5 17.5C7.1 17.5 7.5 17.9 7.5 18.5Z"
                                            fill="currentColor" />
                                    </svg>
                                </span></span>
                            <span class="menu-title"> Production</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                {{-- @can('read cost sheet')
                                <a class="menu-link {{ request()->routeIs('cost_sheet.*') ? 'active' : '' }}"
                                    href="{{ route('cost_sheet.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Cost Sheet</span>
                                </a>
                            @endcan --}}
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                {{-- @can('read product order')
                            <a class="menu-link {{ request()->routeIs('product_order.*') ? 'active' : '' }}"
                                href="{{ route('product_order.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Product Order</span>
                            </a>
                            @endcan --}}
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read billofmaterials')
                                    <a class="menu-link {{ request()->routeIs('billofmaterials.*') ? 'active' : '' }}"
                                        href="{{ route('billofmaterials.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Bill of Materials</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                {{-- @can('read finalproductions')
                                <a class="menu-link {{ request()->routeIs('finalproductions.*') ? 'active' : '' }}"
                                    href="{{ route('finalproductions.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Requisition</span>
                                </a>
                            @endcan --}}
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read requisition')
                                    <a class="menu-link {{ request()->routeIs('additional_requisition_add*') ? 'active' : '' }}"
                                        href="{{ route('additional_requisition_add') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Additional Requisition</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read requisition')
                                    <a class="menu-link {{ request()->routeIs('requisitions.*') ? 'active' : '' }}"
                                        href="{{ route('requisitions.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Requisition Add</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read requisition list')
                                    <a class="menu-link {{ request()->routeIs('production.requisition_list*') ? 'active' : '' }}"
                                        href="{{ route('production.requisition_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Requisition Pending List</span>
                                    </a>
                                @endcan
                                @can('read requisition approved list')
                                    <a class="menu-link {{ request()->routeIs('production.requisition_approved_list*') ? 'active' : '' }}"
                                        href="{{ route('production.requisition_approved_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Requisition Final Approved List</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                {{-- @can('read finalproduction report') --}}
                                @can('read requisition')
                                    <a class="menu-link {{ request()->routeIs('production.finalproduction_report*') ? 'active' : '' }}"
                                        href="{{ route('production.finalproduction_report') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Requisition Stock Out Report</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany
                {{-- End: Production Menu item --}}

                {{-- Add: Finance Menu item --}}
                @canany(['read general ledger'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('journal_voucher', 'cash_book_ledger', 'cash_book_ledger_search', 'bank_book_ledger', 'bank_book_ledger_search', 'total_customer_receivable_list', 'total_supplier_payable_list', 'received_voucher*', 'payment_voucher*', 'finances.index*', 'accounts.index*', 'general_ledger*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-cash-stack fs-2"></i>
                            <span class="menu-title ms-4">Accounts</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">

                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('finances.index*') ? 'active' : '' }}"
                                    href="{{ route('finances.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Add Group</span>
                                </a>
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('accounts.index*') ? 'active' : '' }}"
                                    href="{{ route('accounts.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Add Account</span>
                                </a>
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('general_ledger*') ? 'active' : '' }}"
                                    href="{{ route('general_ledger') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">General Ledger</span>
                                </a>
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('cash_book_ledger*') ? 'active' : '' }}"
                                    href="{{ route('cash_book_ledger') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Cash Book Ledger</span>
                                </a>
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('bank_book_ledger*') ? 'active' : '' }}"
                                    href="{{ route('bank_book_ledger') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Bank Book Ledger</span>
                                </a>
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('total_customer_receivable_list*') ? 'active' : '' }}"
                                    href="{{ route('total_customer_receivable_list') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Total Customer Receivable List</span>
                                </a>
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('total_supplier_payable_list*') ? 'active' : '' }}"
                                    href="{{ route('total_supplier_payable_list') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Total Supplier Payable List</span>
                                </a>
                                <!--end:Menu link-->

                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('journal_voucher*') ? 'active' : '' }}"
                                    href="{{ route('journal_voucher') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Journal Voucher</span>
                                </a>
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('received_voucher*') ? 'active' : '' }}"
                                    href="{{ route('received_voucher') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Received Voucher</span>
                                </a>
                                <!--end:Menu link-->
                                <a class="menu-link {{ request()->routeIs('payment_voucher*') ? 'active' : '' }}"
                                    href="{{ route('payment_voucher') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Payment Voucher</span>
                                </a>
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('summary_report*') ? 'active' : '' }}"
                                    href="{{ route('summary_report') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Summary Report</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany
                {{-- End: Finance Menu item --}}

                {{-- Add: Employee Menu item --}}
                @canany(['read employee', 'read employee ledger'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('employee_type*', 'employees*', 'employee_ledger*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-people fs-2"></i>
                            <span class="menu-title ms-4"> Employee</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                @can('read employee')
                                    <a class="menu-link {{ request()->routeIs('employee_type.*') ? 'active' : '' }}"
                                        href="{{ route('employee_type.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Emp Master Setting</span>
                                    </a>
                                @endcan
                            </div>
                            <!--end:Menu link-->
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                @can('read employee')
                                    <a class="menu-link {{ request()->routeIs('employees.*') ? 'active' : '' }}"
                                        href="{{ route('employees.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Add Employee</span>
                                    </a>
                                @endcan
                            </div>
                            <!--end:Menu link-->
                            <!--begin:Menu link-->
                            <div class="menu-item">
                                @can('read employee ledger')
                                    <a class="menu-link {{ request()->routeIs('employee_ledger.*') ? 'active' : '' }}"
                                        href="{{ route('employee_ledger.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Employee Ledgers</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany
                {{-- End: Employee Menu item --}}

                {{-- Add: Employee Leave item --}}
                @canany(['read leave setting', 'read leave entry'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs(
                            'leave_report*',
                            'late_of_leave*',
                            'employee_leave_setting*',
                            'employee_leave_entry*',
                            'employee_leave_approve_dept*',
                            'employee_leave_approve_hr*',
                            'employee_leave_approve_manag*',
                            'employee_leave_entry_list*',
                        )
                            ? 'here show'
                            : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-person-vcard fs-2"></i>
                            <span class="menu-title ms-4"> Employee Leave</span>
                            <span class="menu-arrow"></span>
                        </span>

                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">


                                <!--begin:Menu link-->
                                @can('read leave setting')
                                    <a class="menu-link {{ request()->routeIs('employee_leave_setting.*') ? 'active' : '' }}"
                                        href="{{ route('employee_leave_setting.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Leave Setting</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read leave entry')
                                    <a class="menu-link {{ request()->routeIs('employee_leave_entry.*') ? 'active' : '' }}"
                                        href="{{ route('employee_leave_entry.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Leave Entry</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read leave entry')
                                    <a class="menu-link {{ request()->routeIs('employee_leave_entry_list') ? 'active' : '' }}"
                                        href="{{ route('employee_leave_entry_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Leave Entry List</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read leave approved department')
                                    <a class="menu-link {{ request()->routeIs('employee_leave_approve_dept.*') ? 'active' : '' }}"
                                        href="{{ route('employee_leave_approve_dept.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Leave Approved</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                {{-- @can('read leave approved hr')
                                <a class="menu-link {{ request()->routeIs('employee_leave_approve_hr.*') ? 'active' : '' }}"
                                    href="{{ route('employee_leave_approve_hr.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Leave Approved HR</span>
                                </a>
                            @endcan --}}
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                {{-- @can('read leave approved management')
                                <a class="menu-link {{ request()->routeIs('employee_leave_approve_manag.*') ? 'active' : '' }}"
                                    href="{{ route('employee_leave_approve_manag.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Leave Approved Management</span>
                                </a>
                            @endcan --}}
                                <!--end:Menu link-->

                                <!--begin:Menu link-->
                                @can('read leave entry')
                                    <a class="menu-link {{ request()->routeIs('leave_report.*') ? 'active' : '' }}"
                                        href="{{ route('leave_report.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Leave Report</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read leave entry')
                                    <a class="menu-link {{ request()->routeIs('late_of_leave') ? 'active' : '' }}"
                                        href="{{ route('late_of_leave') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Late of Leave</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->

                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany
                {{-- End: Employee Leave item --}}

                {{-- Add: Attendance Menu item --}}
                @canany(['read attendance', 'read delayin earlyout'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs(
                            'manual_attendance_input*',
                            'attendance*',
                            'present_attendance_list',
                            'absent_attendance_list',
                            'emp_delayin_earlyout*',
                            'daily_attendance_summary*',
                            'monthly_attendance_time_card*',
                        )
                            ? 'here show'
                            : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-card-list fs-2"></i>
                            <span class="menu-title ms-4">Employee Attendance</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                @can('read attendance')
                                    <a class="menu-link {{ request()->routeIs('manual_attendance_input*') ? 'active' : '' }}"
                                        href="{{ route('manual_attendance_input') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Manual Attendance</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read attendance')
                                    <a class="menu-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}"
                                        href="{{ route('attendance.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Attendance Report</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read attendance')
                                    <a class="menu-link {{ request()->routeIs('present_attendance_list*') ? 'active' : '' }}"
                                        href="{{ route('present_attendance_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Present Report</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read attendance')
                                    <a class="menu-link {{ request()->routeIs('absent_attendance_list*') ? 'active' : '' }}"
                                        href="{{ route('absent_attendance_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Absent Report</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read attendance')
                                    <a class="menu-link {{ request()->routeIs('daily_attendance_summary*') ? 'active' : '' }}"
                                        href="{{ route('daily_attendance_summary') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Daily Attendance Summary</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read attendance')
                                    <a class="menu-link {{ request()->routeIs('monthly_attendance_time_card*') ? 'active' : '' }}"
                                        href="{{ route('monthly_attendance_time_card') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Monthly Time Card</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read delayin earlyout')
                                    <a class="menu-link {{ request()->routeIs('emp_delayin_earlyout.*') ? 'active' : '' }}"
                                        href="{{ route('emp_delayin_earlyout.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Delay In Early Exit</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany
                {{-- End: Attendance Menu item --}}

                {{-- Add: HR Admin Setup Menu item --}}
                @canany(['read timetable', 'read holiday'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('work_time*', 'promotion*', 'announcement*', 'holiday*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-card-list fs-2"></i>
                            <span class="menu-title ms-4">HR Admin Setup</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                @can('read timetable')
                                    <a class="menu-link {{ request()->routeIs('work_time.*') ? 'active' : '' }}"
                                        href="{{ route('work_time.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Set Timetable </span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->

                                <!--begin:Menu link-->
                                {{-- @can('read promotion')
                                <a class="menu-link {{ request()->routeIs('promotion.*') ? 'active' : '' }}"
                                    href="{{ route('promotion.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Promotion</span>
                                </a>
                            @endcan --}}
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                {{-- @can('read announcement')
                                <a class="menu-link {{ request()->routeIs('announcement.*') ? 'active' : '' }}"
                                    href="{{ route('announcement.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Announcement</span>
                                </a>
                            @endcan --}}
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read holiday')
                                    <a class="menu-link {{ request()->routeIs('holiday.*') ? 'active' : '' }}"
                                        href="{{ route('holiday.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Holiday</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany
                {{-- End: HR Admin Setup Menu item --}}

                {{-- Add: Employee payroll Menu item --}}

                @canany(['read monthly salary', 'read payroll head'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('payroll_formulas*', 'payroll*', 'set_salaries*', 'monthly_salaries*', 'payslips*', 'payslip_type*', 'income_head*', 'deduction_head*', 'allowance_option*', 'loan_option*', 'payroll_head*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-cash-stack fs-2"></i>
                            <span class="menu-title ms-4">Employee Payroll</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">

                                <!--begin:Menu link-->
                                @can('read set salaries')
                                    <a class="menu-link {{ request()->routeIs('set_salaries.*') ? 'active' : '' }}"
                                        href="{{ route('set_salaries.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Set Salaries</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read monthly salary')
                                    <a class="menu-link {{ request()->routeIs('monthly_salaries.*') ? 'active' : '' }}"
                                        href="{{ route('monthly_salaries.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Salary Sheet</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                {{-- @can('read payslips')
                                <a class="menu-link {{ request()->routeIs('payslips.*') ? 'active' : '' }}"
                                    href="{{ route('payslips.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Payslips</span>
                                </a>
                            @endcan --}}
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read payslip type')
                                    <a class="menu-link {{ request()->routeIs('payslip_type.*') ? 'active' : '' }}"
                                        href="{{ route('payslip_type.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Payslip Type</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->

                                <!--begin:Menu link-->
                                {{-- @can('read income head')
                                <a class="menu-link {{ request()->routeIs('income_head.*') ? 'active' : '' }}"
                                    href="{{ route('income_head.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Income Head</span>
                                </a>
                            @endcan --}}
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                {{-- @can('read deduction head')
                                <a class="menu-link {{ request()->routeIs('deduction_head.*') ? 'active' : '' }}"
                                    href="{{ route('deduction_head.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Deduction Head</span>
                                </a>
                            @endcan --}}
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read payroll head')
                                    <a class="menu-link {{ request()->routeIs('payroll_head.*') ? 'active' : '' }}"
                                        href="{{ route('payroll_head.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Payroll Head</span>
                                    </a>
                                @endcan
                                <!--begin:Menu link-->
                                @can('read payroll formula')
                                    <a class="menu-link {{ request()->routeIs('payroll_formulas.*') ? 'active' : '' }}"
                                        href="{{ route('payroll_formulas.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Payroll Formula</span>
                                    </a>
                                @endcan
                                <!--begin:Menu link-->
                                @can('read payroll add')
                                    <a class="menu-link {{ request()->routeIs('payroll.*') ? 'active' : '' }}"
                                        href="{{ route('payroll.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Payroll Add</span>
                                    </a>
                                @endcan

                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany
                
                {{-- End: Employee payroll Menu item --}}

                {{-- Add: Employee payroll Formula Menu item --}}
                {{-- <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->routeIs('overtime_formula*') ? 'here show' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <i class="bi bi-cash-stack fs-2"></i>
                        <span class="menu-title ms-4">Payroll Formula</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <!--begin:Menu item-->
                        <div class="menu-item">

                            <!--begin:Menu link-->
                            @can('read set salaries')
                                <a class="menu-link {{ request()->routeIs('overtime_formula.*') ? 'active' : '' }}"
                                    href="{{ route('overtime_formula.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Overtime Formula</span>
                                </a>
                            @endcan
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->
                </div> --}}
                {{-- End: Employee payroll Menu item --}}

                {{-- Add: Employee Performance Menu item --}}
                {{-- <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->routeIs('performance_type*', 'employee_performance*') ? 'here show' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <i class="bi bi-person-check fs-2"></i>
                        <span class="menu-title ms-4">Employee Performance</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            @can('read performance type')
                                <a class="menu-link {{ request()->routeIs('performance_type.*') ? 'active' : '' }}"
                                    href="{{ route('performance_type.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Performance Type</span>
                                </a>
                            @endcan
                            <!--end:Menu link-->
                            <!--begin:Menu link-->
                            @can('read employee performance')
                                <a class="menu-link {{ request()->routeIs('employee_performance.*') ? 'active' : '' }}"
                                    href="{{ route('employee_performance.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Employee Performance</span>
                                </a>
                            @endcan
                            <!--end:Menu link-->

                        </div>
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->
                </div> --}}
                {{-- End: Employee Performance Menu item --}}




                <!--begin:Menu item-->
                <div class="menu-item pt-5">
                    <!--begin:Menu content-->
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">Apps</span>
                    </div>
                    <!--end:Menu content-->
                </div>
                <!--end:Menu item-->

                @can('write user management')
                    <!--begin:Menu item-->
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('user-management.*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-person-fill-gear fs-2"></i>
                            <span class="menu-title ms-4">User Management</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('user-management.users.*') ? 'active' : '' }}"
                                    href="{{ route('user-management.users.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Users</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('user-management.roles.*') ? 'active' : '' }}"
                                    href="{{ route('user-management.roles.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Roles</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('user-management.permissions.*') ? 'active' : '' }}"
                                    href="{{ route('user-management.permissions.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Permissions</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                    <!--end:Menu item-->
                @endcan
                <!--end:Menu item-->

                {{-- Add: DB Backup Menu item --}}
                {{-- @can('read dbbackup')
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('dbbackups.*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-database-check fs-2"></i>
                            <span class="menu-title ms-4">Database</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->

                                <a class="menu-link {{ request()->routeIs('dbbackups.index*') ? 'active' : '' }}"
                                    href="{{ route('dbbackups.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Database Backup</span>
                                </a>

                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcan --}}
                {{-- End: DB Backup Menu item --}}

            </div>
        @endrole
        <!--end::Menu-->
    </div>
    <!--end::Menu wrapper-->
</div>
<!--end::sidebar menu-->
