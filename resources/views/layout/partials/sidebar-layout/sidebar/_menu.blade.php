

<style>
.menu-link i,
.menu-link .menu-title,
.menu-link .menu-arrow {
    color: white !important;
}

.menu-link {
    color: white !important;
}

.menu-bullet .bullet-dot {
    background-color: white !important;
}

/* NRB Blue Metronic-style menu */
.menu-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0.75rem 1rem;
    border-radius: 0.65rem;
    font-weight: 500;
    color: #1d1d1d;
    transition: all 0.25s ease;
    /* background-color: #148FB8; */
    /* background: linear-gradient(90deg, #1d439a 0%, #2b5fd3 100%); */
}

/* Hover state */
.menu-link:hover {
    background-color: #148FB8;
    /* light blue hover background */
    color: #dbe6ff;
    /* NRB primary blue */
}

/* Active state */
.menu-link.active {
    background-color: #148FB8 !important;
    color: #ffffff !important;
    /* white text for contrast */
    box-shadow: 0 4px 12px rgba(20, 143, 184, 0.3);
    /* optional shadow with same tone */
}


/* Icon style */
.menu-icon {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.65rem;
    background-color: #f2f3f8;
    transition: all 0.25s ease;
    font-size: 1.3rem;
    color: #1d439a;
}

/* Hover and active icon color */
.menu-link:hover .menu-icon {
    background-color: #dbe6ff;
    color: #000000;
}

.menu-link.active .menu-icon {
    background-color: rgba(255, 255, 255, 0.2);
    color: #ffffff;
}

.menu-title {
    font-size: 1rem;
    letter-spacing: 0.2px;
    color: #dbe6ff;
}

.menu-item>a {
    margin: 0.15rem 0;
}

[data-kt-app-layout="dark-sidebar"] .app-sidebar .menu>.menu-item .menu-sub .menu-item .menu-link .menu-title {
    color: #dbe6ff;
}

[data-kt-app-layout="dark-sidebar"] .app-sidebar {
    background-color: #fff;
    border-right: 0;
    /* box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15); */
    /* box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25); */

}

/* Force all sidebar menu arrows to stay white */
.menu-item .menu-arrow::after {
    background-color: white !important;
    -webkit-mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' fill='white'%3E%3Cpath d='M1 3l4 4 4-4'/%3E%3C/svg%3E");
    mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' fill='white'%3E%3Cpath d='M1 3l4 4 4-4'/%3E%3C/svg%3E");
    -webkit-mask-repeat: no-repeat;
    mask-repeat: no-repeat;
    -webkit-mask-position: center;
    mask-position: center;
    transition: none !important;
    opacity: 1 !important;
}
</style>

<!--begin::sidebar menu-->
<div class="app-sidebar-menu overflow-hidden flex-column-fluid"style="background-color:#0981AA;">
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
                        <i class="ki-outline ki-home fs-2"></i>
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


                {{-- Add: General Setting item --}}
                @canany(['read company setting'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('emp_company*', 'emp_branch*', 'emp_department.*', 'emp_designation.*') ? 'here show' : '' }}">
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
                                @can('read employee branch')
                                    <a class="menu-link {{ request()->routeIs('emp_branch.*') ? 'active' : '' }}"
                                        href="{{ route('emp_branch.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Branch </span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read employee department')
                                    <a class="menu-link {{ request()->routeIs('emp_department.*') ? 'active' : '' }}"
                                        href="{{ route('emp_department.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title"> Department</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read employee designation')
                                    <a class="menu-link {{ request()->routeIs('emp_designation.*') ? 'active' : '' }}"
                                        href="{{ route('emp_designation.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title"> Designation</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany
                {{-- End: General Setting item --}}


                
               <!--begin:Menu Content Setup -->
<div data-kt-menu-trigger="click"
    class="menu-item menu-accordion {{ request()->routeIs(
        'gallery*',
        'video*',
        'team*',
        'blog*',
        'offers*',
        'slider*',
        'pages*',
        'rides*',
        'about_us*'
    ) ? 'here show' : '' }}">

    <!--begin:Menu link-->
    <span class="menu-link">
        <i class="ki-outline ki-element-12 fs-2"></i>
        <span class="menu-title ms-4">Content Setup</span>
        <span class="menu-arrow"></span>
    </span>
    <!--end:Menu link-->

    <!--begin:Menu sub-->
    <div class="menu-sub menu-sub-accordion">

        <!--Gallery-->
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('gallery*') ? 'active' : '' }}"
                href="{{ route('gallery.index') }}">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Gallery</span>
            </a>
        </div>

        <!--Video-->
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('video*') ? 'active' : '' }}"
                href="{{ route('video.index') }}">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Video</span>
            </a>
        </div>

        <!--Team-->
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('team*') ? 'active' : '' }}"
                href="{{ route('team.index') }}">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Team</span>
            </a>
        </div>

        <!--Blog-->
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('blog*') ? 'active' : '' }}"
                href="{{ route('blog.index') }}">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Blog</span>
            </a>
        </div>

        <!--Offer-->
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('offers*') ? 'active' : '' }}"
                href="{{ route('offers.index') }}">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Offer</span>
            </a>
        </div>
        <div class="menu-item">
           <a class="menu-link {{ request()->routeIs('slider*') ? 'active' : '' }}"
                        href="{{ route('slider.index') }}">
                        <i class="ki-outline ki-arrow-right-left fs-2"></i>
                        <span class="menu-title ms-4">Slider</span>
                        {{-- <span class="menu-arrow"></span> --}}
                    </a>
        </div>
        <div class="menu-item">
           <a class="menu-link {{ request()->routeIs('pages*') ? 'active' : '' }}"
                        href="{{ route('pages.index') }}">
                        <i class="ki-outline ki-picture fs-2"></i>
                        <span class="menu-title ms-4">Pages Banner</span>
                        {{-- <span class="menu-arrow"></span> --}}
                    </a>
        </div>
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('rides*') ? 'active' : '' }}"
                        href="{{ route('rides.index') }}">
                        <i class="ki-outline ki-rocket fs-2"></i>

                        <span class="menu-title ms-4">Rides</span>
                        {{-- <span class="menu-arrow"></span> --}}
                    </a>
        </div>
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('about_us*') ? 'active' : '' }}"
                        href="{{ route('about_us.index') }}">
                        {{-- <i class="bi bi-grid-fill fs-2"></i> --}}
                        <i class="ki-outline ki-eye fs-2"></i>
                        <span class="menu-title ms-4">About Us</span>
                        {{-- <span class="menu-arrow"></span> --}}
                    </a>
        </div>
    </div>
    <!--end:Menu sub-->
</div>
<!--end:Menu Content Setup -->

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
                            @can('read supplier ledger')
                                <a class="menu-link {{ request()->routeIs('supplier_ledgers.*') ? 'active' : '' }}"
                                    href="{{ route('supplier_ledgers.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Supplier Ledgers</span>
                                </a>
                            @endcan
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->
                </div>
                @endcan
                {{-- End: Supplier Menu item --}}

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
                                @can('read customer ledger')
                                    <a class="menu-link {{ request()->routeIs('customer_ledgers.*') ? 'active' : '' }}"
                                        href="{{ route('customer_ledgers.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Customer Ledgers</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcan
                {{-- End: Customer Menu item --}}


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
                
                  <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion ">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                           <i class="bi bi-box fs-2"></i>

                            <span class="menu-title ms-4"> Inventory</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                

              

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
                           <i class="bi bi-box fs-2"></i>

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
                            <i class="bi bi-cart fs-2"></i>

                            <span class="menu-title ms-4"> Consume Portal</span>
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
                                        <span class="menu-title">Product Consumer</span>
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
                                        <span class="menu-title">Issue No Wise Consume Reports</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                <!-- @can('read sales report')
                                    <a class="menu-link {{ request()->routeIs('sales_challan_list*', 'sales_challan_details*') ? 'active' : '' }}"
                                        href="{{ route('sales_challan_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Challan Wise Sales Reports</span>
                                    </a>
                                @endcan -->
                                <!--end:Menu link-->

                                <!--begin:Menu link-->
                                <!-- @can('read sales report')
                                    <a class="menu-link {{ request()->routeIs('sales_invoice_return_list*', 'sales_invoice_return_details*') ? 'active' : '' }}"
                                        href="{{ route('sales_invoice_return_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Invoice Wise Sales Return Reports</span>
                                    </a>
                                @endcan -->
                                <!--end:Menu link-->

                                <!--begin:Menu link-->
                                <!-- @can('read sales report datewise')
                                    <a class="menu-link {{ request()->routeIs('customer_wise_sales_list*') ? 'active' : '' }}"
                                        href="{{ route('customer_wise_sales_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Customer Wise Sales Reports</span>
                                    </a>
                                @endcan -->
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
                                     <!-- @can('read sales details report')
                                    <a class="menu-link {{ request()->routeIs('read sales details report*') ? 'active' : '' }}"
                                        href="{{ route('report.sales.details') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Sales Details Reports</span>
                                    </a>
                                @endcan -->
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                <!-- @can('read item wise profit')
                                    <a class="menu-link {{ request()->routeIs('item_wise_profit_list*') ? 'active' : '' }}"
                                        href="{{ route('item_wise_profit_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Item Wise Profit Reports</span>
                                    </a>
                                @endcan -->
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                <!-- @can('read invoice wise profit')
                                    <a class="menu-link {{ request()->routeIs('invoice_wise_profit_list*') ? 'active' : '' }}"
                                        href="{{ route('invoice_wise_profit_list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Invoice Wise Profit Reports</span>
                                    </a>
                                @endcan -->
                                <!--end:Menu link-->
                                <!-- @can('read customer wise profit')
                                    <a class="menu-link {{ request()->routeIs('report.customer-item-profit*') ? 'active' : '' }}"
                                        href="{{ route('report.customer-item-profit') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Item & Customer Wise Profit Reports</span>
                                    </a>
                                @endcan -->

                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany
                {{-- End: Product sales Menu item --}}


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
                            <i class="bi bi-building fs-2"></i>

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
                                        <span class="menu-title">Products Stock</span>
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
                                        <span class="menu-title">Item Wise Stock Ledger</span>
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
                                </div>
                                </div>
              
                
            <!--begin:Menu Room -->
<div data-kt-menu-trigger="click"
    class="menu-item menu-accordion {{ request()->routeIs('room_type*','room*') ? 'here show' : '' }}">

    <!--begin:Menu link-->
    <span class="menu-link">
        <i class="bi bi-house-door fs-2"></i>
        <span class="menu-title ms-4">Room Setting</span>
        <span class="menu-arrow"></span>
    </span>
    <!--end:Menu link-->

    <!--begin:Menu sub-->
    <div class="menu-sub menu-sub-accordion">

        <!--begin:Room Type-->
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('room_type*') ? 'active' : '' }}"
                href="{{ route('room_type.index') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Room Type</span>
            </a>
        </div>
        <!--end:Room Type-->

        <!--begin:Room List-->
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('room*') ? 'active' : '' }}"
                href="{{ route('room.index') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Room List</span>
            </a>
        </div>
        <!--end:Room List-->

    </div>
    <!--end:Menu sub-->
</div>
<!--end:Menu Room-->




            <!--begin:Menu Room Booking -->
<div data-kt-menu-trigger="click"
    class="menu-item menu-accordion {{ request()->routeIs('booking*','multiple_booking*') ? 'here show' : '' }}">

    <!--begin:Menu link-->
    <span class="menu-link">
        <i class="bi bi-calendar-event fs-2"></i>
        <span class="menu-title ms-4">Booking</span>
        <span class="menu-arrow"></span>
    </span>
    <!--end:Menu link-->

    <!--begin:Menu sub-->
    <div class="menu-sub menu-sub-accordion">

        <!--begin:Booking-->
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('booking*') ? 'active' : '' }}"
                href="{{ route('booking.create') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Hourly Booking</span>
            </a>
        </div>
        <!--end:Booking-->

        <!--begin:Multiple Booking-->
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('multiple_booking*') ? 'active' : '' }}"
                href="{{ route('multiple_booking') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Room Booking</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('booking.index*') ? 'active' : '' }}"
                href="{{ route('booking.index') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Room Booking List</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('spot-bookings.create') ? 'active' : '' }}"
                        href="{{ route('spot-bookings.create') }}">
                        <i class="bullet bullet-dot"></i> <!-- Location Pin -->

                        <span class="menu-title ms-4">Spot Booking</span>
                        {{-- <span class="menu-arrow"></span> --}}
                    </a>
        </div>
         <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('spot-bookings.index') ? 'active' : '' }}"
                        href="{{ route('spot-bookings.index') }}">
                        <i class="bullet bullet-dot"></i> <!-- Location Pin -->

                        <span class="menu-title ms-4">Spot Booking List</span>
                        {{-- <span class="menu-arrow"></span> --}}
                    </a>
        </div>
         <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('spot-bookings.report') ? 'active' : '' }}"
                        href="{{ route('spot-bookings.report') }}">
                        <i class="bullet bullet-dot"></i> <!-- Location Pin -->

                        <span class="menu-title ms-4">Spot Booking Report</span>
                        {{-- <span class="menu-arrow"></span> --}}
                    </a>
        </div>
        <!--end:Multiple Booking-->

    </div>
    <!--end:Menu sub-->
</div>
<!--end:Menu Room Booking-->

                

<!--begin:Menu Spot Setting -->
@canany([
    'read spot',
    'read common facility',
    'read additional service',
    'read advance return policy'
])
<div data-kt-menu-trigger="click"
    class="menu-item menu-accordion {{ request()->routeIs(
        'spots*',
        'spot-packages*',
        'common-facilities*',
        'additional-services*',
        'terms-conditions*',
        'advance-return-rules*'
    ) ? 'here show' : '' }}">

    <!--begin:Menu link-->
    <span class="menu-link">
        <i class="bi bi-geo-alt fs-2"></i>
        <span class="menu-title ms-4">Spot Setting</span>
        <span class="menu-arrow"></span>
    </span>
    <!--end:Menu link-->

    <!--begin:Menu sub-->
    <div class="menu-sub menu-sub-accordion">

        <!--begin:Spots-->
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('spots*') ? 'active' : '' }}"
                href="{{ route('spots.index') }}">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Spots</span>
            </a>
        </div>

        <!--begin:Spot Packages-->
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('spot-packages*') ? 'active' : '' }}"
                href="{{ route('spot-packages.index') }}">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Spot Packages</span>
            </a>
        </div>

        <!--begin:Common Facilities-->
        @can('read common facility')
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('common-facilities*') ? 'active' : '' }}"
                href="{{ route('common-facilities.index') }}">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Common Facilities</span>
            </a>
        </div>
        @endcan

        <!--begin:Additional Services-->
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('additional-services*') ? 'active' : '' }}"
                href="{{ route('additional-services.index') }}">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Additional Services</span>
            </a>
        </div>

        <!--begin:Terms Conditions-->
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('terms-conditions*') ? 'active' : '' }}"
                href="{{ route('terms-conditions.index') }}">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Terms Conditions</span>
            </a>
        </div>
          @can('read advance return policy')
        <div class="menu-item">
            <a class="menu-link {{ request()->routeIs('advance-return-rules*') ? 'active' : '' }}"
                href="{{ route('advance-return-rules.index') }}">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Advanced Return Rules Policy</span>
            </a>
        </div>
        @endcan

    </div>
    <!--end:Menu sub-->
</div>
<!--end:Menu Spot Setting-->
@endcanany

                {{-- Add: Finance Menu item --}}
                @canany(['read general ledger'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('journal_voucher', 'cash_book_ledger', 'cash_book_ledger_search', 'bank_book_ledger', 'bank_book_ledger_search', 'total_customer_receivable_list', 'total_supplier_payable_list', 'received_voucher*', 'payment_voucher*', 'finances.index*', 'accounts.index*', 'general_ledger*', 'summary_report', 'summary_report_full') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-currency-exchange fs-2"></i>


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
                                <a class="menu-link {{ request()->routeIs('summary_report') ? 'active' : '' }}"
                                    href="{{ route('summary_report') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Summary Report</span>
                                </a>
                                <!--end:Menu link-->

                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->routeIs('summary_report_full') ? 'active' : '' }}" href="{{ route('summary_report_full') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Summary Report Full</span>
                                </a>
                                <!--end:Menu link-->

                                <a class="menu-link {{ request()->routeIs('report.income-expense-detail') ? 'active' : '' }}"
                                    href="{{ route('report.income-expense-detail') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Income & Expense</span>
                                </a>
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endcanany
                {{-- End: Finance Menu item --}}



{{-- ================= HR MENU ================= --}}
@canany([
    'read leave setting',
    'read leave entry',
    'read attendance',
    'read delayin earlyout'
])
<div data-kt-menu-trigger="click"
    class="menu-item menu-accordion {{ request()->routeIs(
        'leave_report*',
        'late_of_leave*',
        'employee_leave_*',
        'attendance*',
        'manual_attendance_input*',
        'present_attendance_list*',
        'absent_attendance_list*',
        'emp_delayin_earlyout*',
        'daily_attendance_summary*',
        'monthly_attendance_time_card*'
    ) ? 'here show' : '' }}">

    <!-- Main HR Menu -->
    <span class="menu-link">
        <i class="bi bi-people fs-2"></i>
        <span class="menu-title ms-4">HR Management</span>
        <span class="menu-arrow"></span>
    </span>
<div class="menu-sub menu-sub-accordion">
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
                                @can('read leave approved hr')
                                    <a class="menu-link {{ request()->routeIs('employee_leave_approve_hr.*') ? 'active' : '' }}"
                                        href="{{ route('employee_leave_approve_hr.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Leave Approved HR</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read leave approved management')
                                    <a class="menu-link {{ request()->routeIs('employee_leave_approve_manag.*') ? 'active' : '' }}"
                                        href="{{ route('employee_leave_approve_manag.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Leave Approved Management</span>
                                    </a>
                                @endcan
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
                            <span class="menu-title ms-4"> Attendance</span>
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
                                @can('read promotion')
                                    <a class="menu-link {{ request()->routeIs('promotion.*') ? 'active' : '' }}"
                                        href="{{ route('promotion.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Promotion</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read announcement')
                                    <a class="menu-link {{ request()->routeIs('announcement.*') ? 'active' : '' }}"
                                        href="{{ route('announcement.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Announcement</span>
                                    </a>
                                @endcan
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
                                @can('read payslips')
                                    <a class="menu-link {{ request()->routeIs('payslips.*') ? 'active' : '' }}"
                                        href="{{ route('payslips.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Payslips</span>
                                    </a>
                                @endcan
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
                                @can('read income head')
                                    <a class="menu-link {{ request()->routeIs('income_head.*') ? 'active' : '' }}"
                                        href="{{ route('income_head.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Income Head</span>
                                    </a>
                                @endcan
                                <!--end:Menu link-->
                                <!--begin:Menu link-->
                                @can('read deduction head')
                                    <a class="menu-link {{ request()->routeIs('deduction_head.*') ? 'active' : '' }}"
                                        href="{{ route('deduction_head.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Deduction Head</span>
                                    </a>
                                @endcan
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
                <div data-kt-menu-trigger="click"
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
                </div>
                {{-- End: Employee Performance Menu item --}}



</div>
</div>
 @endcanany
               
                
                

                

              
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



                 @can('write user management')
                    <!--begin:Menu item-->
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('work.*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-person-fill-gear fs-2"></i>
                            <span class="menu-title ms-4">Work Order</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">

                               <a class="menu-link {{ request()->routeIs('work-orders.*') ? 'active' : '' }}"
                                href="{{ route('work-orders.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Work Order</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <div class="menu-item">

                               <a class="menu-link {{ request()->routeIs('work-orders.*') ? 'active' : '' }}"
                               href="{{ route('infos.create') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Add Clients</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                    <!--end:Menu item-->
                @endcan
                 @can('write user management')
                    <!--begin:Menu item-->
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('work.*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <i class="bi bi-person-fill-gear fs-2"></i>
                            <span class="menu-title ms-4">Proposal</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <!--begin:Menu item-->
                            <div class="menu-item">

                               <a class="menu-link {{ request()->routeIs('work-orders.*') ? 'active' : '' }}"
                                href="{{ route('proposals.create') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Add Proposal</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <div class="menu-item">

                               <a class="menu-link {{ request()->routeIs('proposals.index.*') ? 'active' : '' }}"
                                href="{{ route('proposals.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Proposal List</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>
                    <!--end:Menu item-->
                @endcan
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
