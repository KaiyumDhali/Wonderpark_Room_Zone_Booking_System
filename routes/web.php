<?php

use App\Http\Controllers\Apps\PermissionManagementController;
use App\Http\Controllers\Apps\RoleManagementController;
use App\Http\Controllers\Apps\UserManagementController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdvanceReturnRuleController;
use App\Http\Controllers\WorkOrderController;

// Product order
use App\Http\Controllers\Production\CostSheetController;
use App\Http\Controllers\Production\ProductOrderController;

use App\Http\Controllers\Production\ProductionBillOfMatrialController;
use App\Http\Controllers\Production\FinalProductionController;

use App\Http\Controllers\Production\RequisitionController;
//Purchase
use App\Http\Controllers\Product\PurchaseController;
//Sales
use App\Http\Controllers\Product\SalesController;
//Service
use App\Http\Controllers\Product\ProductServiceController;
// Product
use App\Http\Controllers\Product\WarehouseController;
use App\Http\Controllers\Product\StockController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductSettingsController;
use App\Http\Controllers\Product\ProductTypeController;
use App\Http\Controllers\Product\ProductFragranceController;
use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Product\SubCategoryController;
use App\Http\Controllers\Product\BrandController;
use App\Http\Controllers\Product\ColorController;
use App\Http\Controllers\Product\SizeController;
use App\Http\Controllers\Product\UnitController;

use App\Http\Controllers\Product\DamageProductController;

use App\Http\Controllers\Product\OrderController;
// Supplier
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\Supplier\SupplierLedgerController;
// Customer
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\CustomerTypeController;
use App\Http\Controllers\Customer\CustomerLedgerController;
// Employee
use App\Http\Controllers\Employee\EmpTypeController;
use App\Http\Controllers\Employee\EmpSectionController;
use App\Http\Controllers\Employee\EmpLineController;
use App\Http\Controllers\Employee\EmpGradeController;
use App\Http\Controllers\Employee\EmpSalarySectionController;
use App\Http\Controllers\Employee\EmpQuiteTypeController;

use App\Http\Controllers\Employee\CompanySettingController;
use App\Http\Controllers\Employee\EmpBranchController;
use App\Http\Controllers\Employee\EmpDepartmentController;
use App\Http\Controllers\Employee\EmpDesignationController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\EmployeeEducationController;
use App\Http\Controllers\Employee\EmployeeJobHistoryController;
use App\Http\Controllers\Employee\EmployeeJobResponsibilityController;
use App\Http\Controllers\Employee\EmployeeLedgerController;
// Employee Leave
use App\Http\Controllers\Employee\leaveReportController;
use App\Http\Controllers\Employee\EmployeeLeaveSettingsController;
use App\Http\Controllers\Employee\EmployeeLeaveEntryController;
use App\Http\Controllers\Employee\EmployeeLeaveApproveDeptController;
use App\Http\Controllers\Employee\EmployeeLeaveApproveHrController;
use App\Http\Controllers\Employee\EmployeeLeaveApproveManagController;
use App\Http\Controllers\Employee\EmployeeDelayinEarlyoutController;
// Performance
use App\Http\Controllers\Employee\PerformanceTypeController;
use App\Http\Controllers\Employee\EmployeePerformanceController;
// Payroll
use App\Http\Controllers\Payroll\PayrollHeadController;
use App\Http\Controllers\Payroll\PayrollFormulaController;
use App\Http\Controllers\Payroll\PayrollController;

use App\Http\Controllers\Payroll\EmployeeSalaryController;
use App\Http\Controllers\Payroll\PayslipTypeController;
use App\Http\Controllers\Payroll\IncomeHeadController;
use App\Http\Controllers\Payroll\IncomeController;

use App\Http\Controllers\Payroll\DeductionController;
use App\Http\Controllers\Payroll\DeductionHeadController;

use App\Http\Controllers\Payroll\AllowanceOptionController;
use App\Http\Controllers\Payroll\AllowanceController;
use App\Http\Controllers\Payroll\CommissionController;
use App\Http\Controllers\Payroll\LoanOptionController;
use App\Http\Controllers\Payroll\LoanController;
use App\Http\Controllers\Payroll\OtherPaymentController;
use App\Http\Controllers\Payroll\OvertimeController;
use App\Http\Controllers\Payroll\SetSalaryController;
use App\Http\Controllers\Payroll\MonthlySalaryController;
use App\Http\Controllers\Payroll\PayslipController;
//HR Admin Setup
use App\Http\Controllers\Hradmin\WorkTimeController;
use App\Http\Controllers\Hradmin\BreakTimeController;
use App\Http\Controllers\Hradmin\LateTimeController;
use App\Http\Controllers\Hradmin\WeekendDayController;
use App\Http\Controllers\Hradmin\AttendanceController;
use App\Http\Controllers\Hradmin\PromotionController;
use App\Http\Controllers\Hradmin\ResignationController;
use App\Http\Controllers\Hradmin\TerminationController;
use App\Http\Controllers\Hradmin\AnnouncementController;
use App\Http\Controllers\Hradmin\HolidayController;
use App\Http\Controllers\Hradmin\MeetingController;
use App\Http\Controllers\DownloadDB\DownloaddbController;

//finance
use App\Http\Controllers\Finance\FinanceGroupController;
use App\Http\Controllers\Finance\FinanceAccountController;
use App\Http\Controllers\Finance\FinanceTransactionController;

// Room
use App\Http\Controllers\Room\RoomTypeController;
use App\Http\Controllers\Room\RoomController;
use App\Http\Controllers\Room\BookingController;
use App\Http\Controllers\Room\PaymentDetailController;

//Ride
use App\Http\Controllers\Ride\RideController;

//Offer
use App\Http\Controllers\Offer\OfferController;

// blog 
use App\Http\Controllers\Blog\BlogController;


use App\Http\Controllers\Team\TeamController;



//About Us
use App\Http\Controllers\AboutUs\AboutUsController;
use App\Http\Controllers\Video\VideoController;

use App\Http\Controllers\Page\PageController;

use App\Http\Controllers\ProposalController;
use App\Http\Controllers\Frontend\FrontController;
use App\Http\Controllers\frontend\FrontendController;
use App\Http\Controllers\frontend\FrontendBookingController;
use App\Http\Controllers\slider\SliderController;
use App\Http\Controllers\gallery\GalleryController;
use App\Http\Controllers\Spot\SpotController;
use App\Http\Controllers\Spot\CommonFacilityController;
use App\Http\Controllers\Spot\SpotPackageController;
use App\Http\Controllers\Spot\SpotBookingController;
use App\Http\Controllers\Spot\AdditionalServiceController;

use App\Http\Controllers\Spot\TermsConditionController;

use App\Models\Ride;
use App\Models\Video;

// frontend route start


Route::get('/', [FrontendController::class, 'index'])->name('frontend.home');
Route::get('about', [FrontendController::class, 'about'])->name('frontend.about');
Route::get('photo', [FrontendController::class, 'photo'])->name('frontend.photo');
Route::get('video', [FrontendController::class, 'video'])->name('frontend.video');
Route::get('contacts', [FrontendController::class, 'contacts'])->name('frontend.contacts');
Route::get('rides', [FrontendController::class, 'rides'])->name('frontend.rides');
Route::get('blogs', [FrontendController::class, 'blog'])->name('frontend.blogs');
Route::get('blog_details/{id}', [FrontendController::class, 'blogDetails'])->name('frontend.blog_details');
Route::get('room_list', [FrontendController::class, 'room'])->name('frontend.rooms');
Route::get('roomdetails/{id}', [FrontendController::class, 'roomDetails'])->name('frontend.room_details');
// Route::get('offers', [FrontendController::class, 'offers'])->name('frontend.offers');

Route::get('spots', [FrontendController::class, 'spot'])->name('frontend.spots');
Route::get('spot_details/{id}', [FrontendController::class, 'spotDetails'])->name('frontend.spot_details');



Route::get('/room', [FrontendController::class, 'roomview'])->name('room2.index');
// Route::get('/about', [FrontendController::class, 'aboutview'])->name('about.index');

// Route::get('/roomdetails/{id}', [FrontendController::class, 'room_details'])->name('roomdetails.index');

// Route::get('/check-availability', [FrontendController::class, 'checkAvailability'])->name('check.availability');
// Route::post('/rooms', [FrontendController::class, 'search'])->name('search');
Route::match(['get', 'post'], '/rooms', [FrontendController::class, 'search'])->name('rooms.search');

// //Booking
Route::post('/book-room', [FrontendBookingController::class, 'frontendbookRoom'])->name('book.room');


/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */


Route::middleware(['auth', 'verified'])->group(function () {

Route::get('spot-bookings/{invoice}/edit', [SpotBookingController::class,'edit1'])->name('spot-bookings.edit1');
Route::put('spot-bookings/{invoice}', [SpotBookingController::class,'update1'])->name('spot-bookings.update1');
Route::get('/zone-availability', [SpotBookingController::class, 'index3'])
    ->name('zone.availability');
// web.php
Route::post('/book-spot', [SpotBookingController::class, 'bookSpot'])->name('frontend.book.spot');

Route::get('/spot-booking/report', [SpotBookingController::class, 'spotReport'])
    ->name('spot-bookings.report');

        Route::get('/booking/hourly-search', [BookingController::class, 'hourlySearch'])->name('booking.hourly.search');
     Route::get('/booking/create-bulk', [BookingController::class, 'create2'])->name('booking.create.bulk');

// web.php
Route::get('/booking/{bookingNo}/invoice', [BookingController::class, 'generateInvoice'])->name('booking.invoice.pdf');



        Route::prefix('proposals')->group(function () {

    // list
    Route::get('/', [ProposalController::class, 'index'])->name('proposals.index');

    // create
    Route::get('/create', [ProposalController::class, 'create'])->name('proposals.create');
    Route::post('/', [ProposalController::class, 'store'])->name('proposals.store');

    // edit/update
    Route::get('/{proposal}/edit', [ProposalController::class, 'edit'])->name('proposals.edit');
    Route::put('/{proposal}', [ProposalController::class, 'update'])->name('proposals.update');

    // delete
    Route::delete('/{proposal}', [ProposalController::class, 'destroy'])->name('proposals.destroy');

    // show
    Route::get('/{proposal}', [ProposalController::class, 'show'])->name('proposals.show');

    // pdf
    Route::get('/{proposal}/pdf', [ProposalController::class, 'pdf'])->name('proposals.pdf');
});

Route::resource('advance-return-rules', AdvanceReturnRuleController::class)
    ->except(['show']);
Route::get('/infos/create', [WorkOrderController::class, 'createclients'])->name('infos.create');
Route::post('/infos', [WorkOrderController::class, 'storeclients'])->name('infos.store');
// Edit & Update routes
Route::put('/infos/{client}', [WorkOrderController::class, 'updateclients'])->name('infos.update');
Route::delete('/infos/{client}', [WorkOrderController::class, 'destroyclients'])->name('infos.destroy');

   Route::resource('work-orders', WorkOrderController::class);
Route::get('work-orders/{workOrder}/pdf', 
    [WorkOrderController::class, 'pdf']
)->name('work-orders.pdf');

    Route::get('/admin', [DashboardController::class, 'index']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route::get('/ceo/notifications', [CEOController::class, 'getNotifications'])->name('ceo.notifications');

    Route::name('user-management.')->group(function () {
        Route::resource('/user-management/users', UserManagementController::class);
        Route::resource('/user-management/roles', RoleManagementController::class);
        Route::resource('/user-management/permissions', PermissionManagementController::class);
    });

    Route::prefix('about-us')->group(function () {
        Route::resource('about_us', AboutUsController::class);
        Route::get('about_us_image_destroy/{id}', [AboutUsController::class, 'imageDestroy'])->name('about_us.image_destroy');
    });

    Route::prefix('video')->group(function () {
        Route::resource('video', VideoController::class);
        Route::delete('video_destroy/{id}', [VideoController::class, 'videoDestroy'])->name('video.destroy');
        // Route::delete('{id}', [VideoController::class, 'videoDestroy'])->name('video.destroy');


    });

    Route::prefix('pages')->group(function () {
        Route::resource('pages', PageController::class);
        Route::get('page_banner_destroy/{id}', [PageController::class, 'bannerDestroy'])->name('pages.banner_destroy');
    });


    Route::prefix('rides')->group(function () {
        Route::resource('rides', RideController::class);
        Route::get('ride_image_destroy/{id}', [RideController::class, 'imageDestroy'])->name('ride.image_destroy');
    });


Route::prefix('spots')->group(function () {

    // ✅ Custom routes FIRST
    Route::get(
        'spot-bookings/invoice/{invoice}',
        [SpotBookingController::class, 'showInvoice']
    )->name('spot-bookings.show.invoice');

    Route::get(
        'spot-bookings/invoice/{invoice}/pdf',
        [SpotBookingController::class, 'exportInvoicePdf']
    )->name('spot-bookings.invoice.pdf');

    Route::post(
        'spot-bookings/update-status',
        [SpotBookingController::class, 'updateStatus']
    )->name('spot-bookings.updateStatus');
Route::post(
    '/additional-services/toggle-editable',
    [AdditionalServiceController::class, 'toggleEditableAjax']
)->name('additional-services.toggle-editable-ajax');
Route::get('spot_image_destroy/{id}', [SpotController::class, 'imageDestroy'])->name('spot.image_destroy');
    // ✅ Resource route AFTER
    Route::get('spot_gallery_image_destroy/{id}', [SpotController::class, 'galleryImageDestroy'])->name('spot_gallery_image_destroy');
    Route::resource('spot-bookings', SpotBookingController::class);

    Route::resource('spots', SpotController::class);
    Route::resource('spot-packages', SpotPackageController::class);
    Route::resource('common-facilities', CommonFacilityController::class);
    Route::resource('additional-services', AdditionalServiceController::class);
    Route::resource('terms-conditions', TermsConditionController::class);
});



    // Route::post('/spot-bookings/store', [SpotBookingController::class, 'store'])->name('spot-bookings.store');
    // Route::get('/spot-bookings/services/{booking}', [SpotBookingController::class, 'services'])->name('spot-bookings.services');
    // Route::post('/spot-bookings/services/{booking}', [SpotBookingController::class, 'servicesSave'])->name('spot-bookings.services.save');



    // Route::prefix('spots')->group(function () {
    //     Route::resource('spot_package', SpotPackageController::class);
    //     // Route::get('spot_image_destroy/{id}', [SpotController::class, 'imageDestroy'])->name('spot.image_destroy');
    //     // Route::get('spot_gallery_image_destroy/{id}', [SpotController::class, 'galleryImageDestroy'])->name('spot_gallery_image_destroy');
    // });


    Route::prefix('offers')->group(function () {
        Route::resource('offers', OfferController::class);
        Route::get('offer_image_destroy/{id}', [OfferController::class, 'imageDestroy'])->name('offer.image_destroy');
    });


    Route::prefix('blog')->group(function () {
        Route::resource('blog', BlogController::class);
        Route::get('blog_image_destroy/{id}', [BlogController::class, 'imageDestroy'])->name('blog.image_destroy');
    });

    Route::prefix('team')->group(function () {
        Route::resource('team', TeamController::class);
        Route::get('team_image_destroy/{id}', [TeamController::class, 'imageDestroy'])->name('team.image_destroy');
    });



    Route::prefix('room')->group(function () {

        Route::resource('room_type', RoomTypeController::class);
        Route::resource('room', RoomController::class);
        Route::get('room_image_destroy/{id}', [RoomController::class, 'imageDestroy'])->name('room_image_destroy');
        Route::get('room_thumbnail_destroy/{id}', [RoomController::class, 'thumbnailImageDestroy'])->name('room_thumbnail_destroy');
        Route::resource('payment', PaymentDetailController::class);
        Route::resource('booking', BookingController::class);
        Route::get('multiple_booking', [BookingController::class, 'multipleBookingRoomList'])->name('multiple_booking');
        Route::post('multiple_booking_store', [BookingController::class, 'multipleBookingStore'])->name('multiple_booking_store');

        Route::get('booking_add/{id}/{date}', [BookingController::class, 'create2'])->name('booking_add');
        Route::post('booking_store', [BookingController::class, 'store'])->name('booking_store');
        // Route::get('item_wise_stock_report', [StockController::class, 'stockReportItemWise'])->name('item_wise_stock_report');
        Route::get('booking_search/{startDate}/{endDate}', [BookingController::class, 'bookingSearch'])->name('booking_search');
        Route::get('room_booking_search/{id}/{startDate}/{endDate_2}', [BookingController::class, 'roomBookingSearch'])->name('room_booking_search');
        Route::get('get_customer/{customer_mobile}', [BookingController::class, 'getCustomer'])->name('get_customer');

        // Route::get('room-lock-status', [BookingController::class, 'getLockStatus'])->name('room_lock_status');
        // Route::get('/room-lock-status', [BookingController::class, 'getLockStatus'])->name('room_lock_status');
        // Route::post('/booking-add', [BookingController::class, 'bookingAdd'])->name('booking_add');
        //add from kaium
        Route::post('/update-booking-status', [BookingController::class, 'updateStatus'])->name('update.booking.status');


        //slider
        Route::get('/sliders', [SliderController::class, 'index'])->name('slider.index');
        Route::post('/sliders', [SliderController::class, 'store'])->name('slider.store');
        Route::put('sliders/{id}', [SliderController::class, 'update'])->name('slider.update');
        Route::delete('sliders/{id}', [SliderController::class, 'destroy'])->name('slider.destroy');

        //Gallery
        Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
        Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');
        Route::put('gallery/{id}', [GalleryController::class, 'update'])->name('gallery.update');
        Route::delete('gallery/{id}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
    });




    // product pages
    Route::prefix('product')->group(function () {

        // service Route
        Route::get('product_services', [ProductServiceController::class, 'productServices'])->name('product_services');
        Route::get('invoice_date_search_service/{startDate}/{endDate}/{pdf}', [ProductServiceController::class, 'serviceInvoiceDateSearch'])->name('invoice_date_search_service');
        Route::get('service_invoice/{invoiceNo}', [ProductServiceController::class, 'serviceInvoice'])->name('service_invoice');
        Route::post('product_services_store', [ProductServiceController::class, 'store'])->name('product_services_store');

        Route::get('pending_product_services', [ProductServiceController::class, 'pendingProductServices'])->name('pending_product_services');
        Route::get('pending_product_services_search/{startDate}/{endDate}/{pdf}', [ProductServiceController::class, 'pendingProductServicesSearch'])->name('pending_product_services_search');

        Route::get('complete_product_services', [ProductServiceController::class, 'completeProductServices'])->name('complete_product_services');
        Route::get('complete_product_services_search/{startDate}/{endDate}/{pdf}', [ProductServiceController::class, 'completeProductServicesSearch'])->name('complete_product_services_search');

        Route::get('product_services_entry/{id}', [ProductServiceController::class, 'productServicesEntry'])->name('product_services_entry');
        Route::put('product_services_entry_store/{id}', [ProductServiceController::class, 'productServicesEntryStore'])->name('product_services_entry_store');

        Route::get('not_complete_product_services/{id}', [ProductServiceController::class, 'notCompleteProductServices'])->name('not_complete_product_services');

        // warehouse
        Route::resource('warehouse', WarehouseController::class);
        Route::get('warehouse_wise_product_list/{warehouseId}', [WarehouseController::class, 'warehouseWiseProductList'])->name('warehouse_wise_product_list');
        Route::get('stock_transfer', [WarehouseController::class, 'stockTransfer'])->name('stock_transfer');
        Route::post('warehouse_transfer', [WarehouseController::class, 'warehouseTransferStore'])->name('warehouse_transfer');
        Route::get('invoice_wise_stock_transfer_list', [WarehouseController::class, 'stockTransferList'])->name('invoice_wise_stock_transfer_list');
        Route::get('stock_transfer_list_search/{startDate}/{endDate}/{warehouseId}/{statusId}/{pdf}', [WarehouseController::class, 'stockTransferListSearch'])->name('stock_transfer_list_search');
        Route::get('stock_transfer_invoice_details/{invoiceNo}', [WarehouseController::class, 'stockTransferInvoiceDetails'])->name('stock_transfer_invoice_details');
        Route::POST('invoice_wise_stock_transfer_individual_approved_store', [WarehouseController::class, 'invoiceWiseStockTransferIndividualApprovedStore'])->name('invoice_wise_stock_transfer_individual_approved_store');

        // Stock 
        Route::get('warehouse_wise_product_details/{productId}/{warehouseId}', [StockController::class, 'warehouseWiseProductDetails'])->name('warehouse_wise_product_details');

        Route::get('productDetails/{id}', [StockController::class, 'productDetails'])->name('productDetails');

        // Order Controller route --------------------------
        Route::resource('order', OrderController::class);
        Route::get('orderEdit/{id}', [OrderController::class, 'orderEdit'])->name('orderEdit');
        Route::get('orderView/{id}', [OrderController::class, 'orderView'])->name('orderView');
        Route::get('orderDetailsPdf/{id}', [OrderController::class, 'orderDetailsPdf'])->name('orderDetailsPdf');
        Route::get('orderApproved/{id}', [OrderController::class, 'orderApproved'])->name('orderApproved');
        Route::get('orderProductDetails/{productID}', [OrderController::class, 'productDetails'])->name('orderProductDetails');
        Route::get('orderDetailsDestroy/{id}', [OrderController::class, 'detailsDestroy'])->name('orderDetailsDestroy');
        // get order list ------------------
        Route::get('getOrderLists/{startDate}/{endDate}/{type}/{pdf}', [OrderController::class, 'getOrderList'])->name('getOrderLists');

        Route::get('orderSummary', [OrderController::class, 'orderSummary'])->name('orderSummary');
        Route::get('orderSummaryReports/{startDate}/{endDate}/{pdf}', [OrderController::class, 'orderSummaryReport'])->name('orderSummaryReports');



        Route::resource('damage', DamageProductController::class);
        Route::get('damage_invoice_details/{invoiceNo}', [DamageProductController::class, 'damageInvoiceDetails'])->name('damage_invoice_details');
        Route::get('damage_invoice_details_pdf/{pdf}', [DamageProductController::class, 'damageInvoiceDetailsPdf'])->name('damage_invoice_details_pdf');
        Route::get('damage_product_list_search/{startDate}/{endDate}/{warehouseId}/{productId}/{pdf}', [DamageProductController::class, 'damageProductListSearch'])->name('damage_product_list_search');





        // Sales route --------------------------
        Route::resource('sales', SalesController::class);
        Route::get('customerDetails/{id}', [SalesController::class, 'customerDetails'])->name('productCustomerDetails');
        Route::get('sales_invoice_edit/{invoiceNo}', [SalesController::class, 'salesInvoiceEdit'])->name('sales_invoice_edit');
        Route::put('sales_invoice_update/{invoiceNo}', [SalesController::class, 'salesInvoiceUpdate'])->name('sales_invoice_update');

        // sales_invoice_return
        Route::get('sales_invoice_return/{invoiceNo}', [SalesController::class, 'salesInvoiceReturn'])->name('sales_invoice_return');
        Route::put('sales_invoice_return_store/{invoiceNo}', [SalesController::class, 'salesInvoiceReturnStore'])->name('sales_invoice_return_store');
        Route::get('sales_invoice_return_list', [SalesController::class, 'salesInvoiceReturnList'])->name('sales_invoice_return_list');
        Route::get('sales_invoice_return_list_search/{startDate}/{endDate}/{pdf}', [SalesController::class, 'salesInvoiceReturnListSearch'])->name('sales_invoice_return_list_search');

        Route::get('sales_invoice_return_details/{invoiceNo}', [SalesController::class, 'salesReturnInvoiceDetails'])->name('sales_invoice_return_details');
        Route::get('sales_invoice_return_details_Pdf/{data}', [SalesController::class, 'purchaseReturnInvoiceDetailsPdf'])->name('sales_invoice_return_details_Pdf');

        // Sales Invoice Report
        Route::get('sales_invoice_list', [SalesController::class, 'salesInvoiceReport'])->name('sales_invoice_list');
        Route::get('sales_invoice_date_search/{startDate}/{endDate}/{pdf}', [SalesController::class, 'salesInvoiceDateSearch'])->name('sales_invoice_date_search');
        Route::get('sales_invoice_details/{invoiceNo}', [SalesController::class, 'salesInvoiceDetails'])->name('sales_invoice_details');
        Route::get('sales_invoice_details_pdf/{data}', [SalesController::class, 'salesInvoiceDetailsPdf'])->name('sales_invoice_details_pdf');
        // Customer Wise Sales Report
        Route::get('customer_wise_sales_list', [SalesController::class, 'salesReportCustomerWise'])->name('customer_wise_sales_list');
        Route::get('customer_wise_sales_search/{startDate}/{endDate}/{accountName}/{pdf}', [SalesController::class, 'salesReportCustomerWiseSearch'])->name('customer_wise_sales_search');
        // Item Wise Sales Report
        Route::get('item_wise_sales_list', [SalesController::class, 'salesReportItemWise'])->name('item_wise_sales_list');
        Route::get('item_wise_sales_search/{startDate}/{endDate}/{productID}/{accountName}/{pdf}', [SalesController::class, 'salesReportItemWiseSearch'])->name('item_wise_sales_search');

        // Item Wise Profit Report
        Route::get('item_wise_profit_list', [SalesController::class, 'salesProfitItemWise'])->name('item_wise_profit_list');
        Route::get('item_wise_profit_search/{startDate}/{endDate}/{productID}/{pdf}', [SalesController::class, 'salesProfitItemWiseSearch'])->name('item_wise_profit_search');
        // Invoice Wise Profit Report
        Route::get('invoice_wise_profit_list', [SalesController::class, 'salesProfitInvoiceWise'])->name('invoice_wise_profit_list');
        Route::get('invoice_wise_profit_search/{startDate}/{endDate}/{pdf}', [SalesController::class, 'salesProfitInvoiceWiseSearch'])->name('invoice_wise_profit_search');
        Route::get('invoice_wise_profit_sales_details/{invoiceNo}', [SalesController::class, 'salesProfitInvoiceWiseDetails'])->name('invoice_wise_profit_sales_details');

        // Sales Challan Report
        Route::get('sales_challan_list', [SalesController::class, 'salesChallanReport'])->name('sales_challan_list');
        Route::get('sales_challan_date_search/{startDate}/{endDate}/{pdf}', [SalesController::class, 'salesChallanDateSearch'])->name('sales_challan_date_search');
        Route::get('sales_challan_details/{challanNo}', [SalesController::class, 'salesChallanDetails'])->name('sales_challan_details');
        Route::get('sales_challan_details_pdf/{data}', [SalesController::class, 'salesChallanDetailsPdf'])->name('sales_challan_details_pdf');

        // Purchase route --------------------------
        Route::resource('purchase', PurchaseController::class);
        Route::get('supplierDetails/{id}', [PurchaseController::class, 'supplierDetails']);
        // Purchase Invoice Report
        Route::put('purchase_invoice_update/{invoiceNo}', [PurchaseController::class, 'purchaseInvoiceUpdate'])->name('purchase_invoice_update');
        Route::get('purchase_invoice_list', [PurchaseController::class, 'purchaseInvoiceReport'])->name('purchase_invoice_list');
        Route::get('purchase_invoice_date_search/{startDate}/{endDate}/{pdf}', [PurchaseController::class, 'purchaseInvoiceDateSearch'])->name('purchase_invoice_date_search');
        // purchase_invoice_return
        Route::get('purchase_invoice_return/{invoiceNo}', [PurchaseController::class, 'purchaseInvoiceReturn'])->name('purchase_invoice_return');
        Route::post('purchase_invoice_return_store/{invoiceNo}', [PurchaseController::class, 'purchaseInvoiceReturnStore'])->name('purchase_invoice_return_store');
        Route::get('purchase_invoice_return_list', [PurchaseController::class, 'purchaseInvoiceReturnList'])->name('purchase_invoice_return_list');
        Route::get('purchase_invoice_return_list_search/{startDate}/{endDate}/{pdf}', [PurchaseController::class, 'purchaseInvoiceReturnListSearch'])->name('purchase_invoice_return_list_search');
        Route::get('purchase_invoice_return_details/{invoiceNo}', [PurchaseController::class, 'purchaseReturnInvoiceDetails'])->name('purchase_invoice_return_details');
        Route::get('purchase_invoice_return_details_Pdf/{data}', [PurchaseController::class, 'purchaseReturnInvoiceDetailsPdf'])->name('purchase_invoice_return_details_Pdf');

        // purchase_invoice_edit
        Route::get('purchase_invoice_edit/{invoiceNo}', [PurchaseController::class, 'purchaseInvoiceEdit'])->name('purchase_invoice_edit');
        Route::get('purchase_invoice_single_row_destroy/{id}', [PurchaseController::class, 'purchaseInvoiceSingleRowDestroy'])->name('purchase_invoice_single_row_destroy');

        Route::get('purchase_invoice_details/{invoiceNo}', [PurchaseController::class, 'purchaseInvoiceDetails'])->name('purchase_invoice_details');
        Route::get('purchase_invoice_details_Pdf/{data}', [PurchaseController::class, 'purchaseInvoiceDetailsPdf'])->name('purchase_invoice_details_Pdf');
        // Supplier Wise Purchase Report
        Route::get('supplier_wise_purchase_list', [PurchaseController::class, 'purchaseReportSupplierWise'])->name('supplier_wise_purchase_list');
        Route::get('supplier_wise_purchase_search/{startDate}/{endDate}/{accountName}/{pdf}', [PurchaseController::class, 'purchaseReportSupplierWiseSearch'])->name('supplier_wise_purchase_search');

        // Stock route --------------------------
        Route::get('stock_report', [StockController::class, 'stock'])->name('stock_report');
        Route::get('stock_report_pdf', [StockController::class, 'stock_pdf'])->name('stock_report_pdf');




        Route::get('material_wise_stock_report', [StockController::class, 'stockReportMaterialWise'])->name('material_wise_stock_report');
        Route::get('stock_report_material_wise_search/{startDate}/{endDate}/{warehouseId}/{productId}/{pdf}', [StockController::class, 'stockReportMaterialWiseSearch'])->name('stock_report_material_wise_search');





        // finish_good_wise_stock_report
        Route::get('finish_good_wise_stock_report', [StockController::class, 'stockReportFinishGoodWise'])->name('finish_good_wise_stock_report');
        Route::get('finish_good_wise_stock_search/{startDate}/{endDate}/{warehouseId}/{productId}/{pdf}', [StockController::class, 'stockReportFinishGoodWiseSearch'])->name('finish_good_wise_stock_search');

        // item_wise_stock_report
        Route::get('item_wise_stock_report', [StockController::class, 'stockReportItemWise'])->name('item_wise_stock_report');
        Route::get('item_wise_stock_search/{startDate}/{endDate}/{productID}/{pdf}', [StockController::class, 'stockReportItemWiseSearch'])->name('item_wise_stock_search');

        Route::resource('cost_sheet', CostSheetController::class);
        Route::resource('product_order', ProductOrderController::class);
        Route::get('product_order_image_destroy/{id}', [ProductOrderController::class, 'imageDestroy'])->name('product_order.image_destroy');
        Route::get('allColor', [ProductOrderController::class, 'allColor'])->name('allColor');
        Route::get('/get-colors', 'ProductOrderController@allColor');

        Route::resource('products', ProductController::class);

        Route::resource('product_type', ProductTypeController::class);
        Route::resource('product_fragrance', ProductFragranceController::class);
        Route::get('category', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('category_create', [CategoryController::class, 'create'])->name('category_create.create');
        Route::post('categories_store', [CategoryController::class, 'store'])->name('categories_store.store');
        Route::get('category_edit/{id}', [CategoryController::class, 'edit'])->name('category_edit.edit');
        Route::POST('category_update/{id}', [CategoryController::class, 'update'])->name('category_update.update');
        Route::get('category_delete/{id}', [CategoryController::class, 'destroy'])->name('category_delete.destroy');

        Route::resource('sub_category', SubCategoryController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('colors', ColorController::class);
        Route::resource('sizes', SizeController::class);
        Route::resource('units', UnitController::class);
    });

    // Production
    Route::prefix('production')->group(function () {
        Route::resource('billofmaterials', ProductionBillOfMatrialController::class);
        Route::get('billofmaterials/productDetails/{id}', [ProductionBillOfMatrialController::class, 'billOfMatrialProductDetails']);
        // Route::get('billofmaterials/productionDetails/{id}', [FinalProductionController::class, 'productionDetails']);
        Route::get('billofmaterials_pdf/{id}', [ProductionBillOfMatrialController::class, 'billOfMaterialsPdf'])->name('billofmaterials_pdf');

        Route::get('billOfMatrialProductDetails/{id}', [ProductionBillOfMatrialController::class, 'billOfMatrialProductDetails'])->name('billOfMatrialProductDetails');
        Route::get('productDetailsDestroy/{id}', [ProductionBillOfMatrialController::class, 'fileDestroy'])->name('productDetailsDestroy.file_destroy');

        Route::resource('finalproductions', FinalProductionController::class);
        Route::get('productionDetails/{id}', [FinalProductionController::class, 'productionDetails']);

        Route::get('finalproduction_report', [FinalProductionController::class, 'finalProductionReport'])->name('production.finalproduction_report');
        Route::get('invoice_date_search/{startDate}/{endDate}', [FinalProductionController::class, 'invoiceDateSearch'])->name('invoice_date_search');

        Route::post('finalproduction_report_search', [FinalProductionController::class, 'finalProductionReportSearch'])->name('finalproduction_report_search');

        Route::get('finalproduction_report_search_pdf/{data}', [FinalProductionController::class, 'finalProductionReportSearchPdf'])->name('finalproduction_report_search_pdf');

        // RequisitionController
        Route::resource('requisitions', RequisitionController::class);
        Route::get('requisitionDetails/{invoiceNo}', [RequisitionController::class, 'requisitionDetails'])->name('requisitionDetails');
        Route::post('additional_requisition_store', [RequisitionController::class, 'additionalRequisitionStore'])->name('additional_requisition_store');

        Route::get('requisition_notifications', [RequisitionController::class, 'requisitionNotification'])->name('requisition_notifications');
        Route::get('requisition_list', [RequisitionController::class, 'requisitionList'])->name('production.requisition_list');
        Route::get('requisition_invoice_date_search/{startDate}/{endDate}', [RequisitionController::class, 'invoiceDateSearch'])->name('requisition_invoice_date_search');

        Route::get('requisition_approved_list', [RequisitionController::class, 'requisitionApprovedList'])->name('production.requisition_approved_list');
        Route::get('requisition_invoice_approved_date_search/{startDate}/{endDate}', [RequisitionController::class, 'invoiceApprovedDateSearch'])->name('requisition_invoice_approved_date_search');

        Route::get('requisition_invoice_report_search/{invoiceNo}', [RequisitionController::class, 'requisitionInvoiceReportSearch'])->name('requisition_invoice_report_search');
        Route::get('requisition_invoice_report_status_approved/{invoiceNo}/{statusValueApproved}', [RequisitionController::class, 'requisitionInvoiceReportStatusApproved'])->name('requisition_invoice_report_status_approved');

        Route::get('requisition_invoice_report_pdf/{data}', [RequisitionController::class, 'requisitionInvoiceReportPdf'])->name('requisition_invoice_report_pdf');

        // additionalRequisitionAdd
        Route::get('additional_requisition_add', [RequisitionController::class, 'additionalRequisitionAdd'])->name('additional_requisition_add');
    });

    // supplier pages
    Route::prefix('supplier')->group(function () {
        Route::resource('suppliers', SupplierController::class);
        Route::resource('supplier_ledgers', SupplierLedgerController::class);
        Route::get('supplierLedgerDetails/{id}', [SupplierLedgerController::class, 'supplierDetails'])->name('supplierLedgerDetails');
        Route::get('supplierDetails/{ac_name}', [PurchaseController::class, 'supplierDetails'])->name('supplierDetails');
        Route::get('supplier_ledger_search/{startDate}/{endDate}/{supplierID}/{pdf}', [SupplierLedgerController::class, 'supplierLedgerSearch'])->name('supplier_ledger_search');
    });

    // customer pages
    Route::prefix('customer')->group(function () {
        Route::resource('customers', CustomerController::class);
        Route::resource('customer_type', CustomerTypeController::class);
        Route::resource('customer_ledgers', CustomerLedgerController::class);
        Route::get('customerDetails/{id}', [CustomerLedgerController::class, 'customerDetails'])->name('customerDetails');
        Route::get('customer_ledger_search/{startDate}/{endDate}/{customerID}/{pdf}', [CustomerLedgerController::class, 'customerLedgerSearch'])->name('customer_ledger_search');
    });

    // employee pages
    Route::prefix('employee')->group(function () {

        Route::get('employee_profile_pdf/{id}', [EmployeeController::class, 'employeeProfilePdf'])->name('employee_profile_pdf');

        Route::resource('emp_company', CompanySettingController::class);
        Route::resource('emp_branch', EmpBranchController::class);
        Route::resource('emp_department', EmpDepartmentController::class);
        Route::resource('emp_designation', EmpDesignationController::class);

        Route::resource('employee_type', EmpTypeController::class);
        Route::resource('emp_section', EmpSectionController::class);
        Route::resource('emp_line', EmpLineController::class);
        Route::resource('emp_grade', EmpGradeController::class);
        Route::resource('emp_salary_section', EmpSalarySectionController::class);
        Route::resource('emp_quite_type', EmpQuiteTypeController::class);

        // Single Employee Profile
        Route::get('employeeProfile', [EmployeeController::class, 'employeeProfile'])->name('employee_profile');
        Route::get('employeeProfileLedger', [EmployeeLedgerController::class, 'employeeProfileLedger'])->name('employee_profile_ledger');
        Route::get('employee_profile_leave_entry', [EmployeeLeaveEntryController::class, 'employeeProfileLeaveEntry'])->name('employee_profile_leave_entry');



        // Admin Employee Profile
        Route::resource('employees', EmployeeController::class);
        Route::get('employees_search/{employeeCode}/{typeId}/{departmentId}/{designationId}/{pdf}', [EmployeeController::class, 'employeesSearch'])->name('employees_search');
        Route::resource('employee_education', EmployeeEducationController::class);
        Route::resource('employee_job_history', EmployeeJobHistoryController::class);
        Route::resource('employee_job_responsibility', EmployeeJobResponsibilityController::class);




        Route::get('branchEmployees/{id}', [EmployeeController::class, 'branchEmployees'])->name('branchEmployees');
        Route::get('branchDepartment/{id}', [EmployeeController::class, 'branchDepartment'])->name('branchDepartment');

        // Employee ledger
        Route::get('employeeDetails/{id}', [EmployeeLedgerController::class, 'employeeDetails'])->name('employeeDetails');
        Route::resource('employee_ledger', EmployeeLedgerController::class);
        Route::get('employee_ledger_search/{startDate}/{endDate}/{employeeID}/{pdf}', [EmployeeLedgerController::class, 'employeeLedgerSearch'])->name('employee_ledger_search');

        // Employee leave settings
        Route::resource('employee_leave_setting', EmployeeLeaveSettingsController::class);
        Route::get('allEmployeeLeaveSetting', [EmployeeLeaveSettingsController::class, 'allEmployeeLeaveSetting'])->name('allEmployeeLeaveSetting');
        Route::get('sectionWaiseSingleEmployee/{id}', [EmployeeLeaveSettingsController::class, 'sectionWaiseSingleEmployee'])->name('sectionWaiseSingleEmployee');
        Route::get('sectionDetails/{id}', [EmployeeLeaveSettingsController::class, 'sectionDetails'])->name('sectionDetails');
        Route::put('update_leave_settings/{editLeaveID}', [EmployeeLeaveSettingsController::class, 'updateLeaveSettings'])->name('update_leave_settings');

        Route::get('branchDetails/{id}', [EmployeeLeaveSettingsController::class, 'branchDetails'])->name('branchDetails');
        Route::get('employeeLeaveDetails/{id}', [EmployeeLeaveSettingsController::class, 'employeeLeaveDetails'])->name('employeeLeaveDetails');

        // Employee leave entry 
        Route::resource('employee_leave_entry', EmployeeLeaveEntryController::class);
        // Select Section show leave entry list and select employee list
        Route::get('leaveEntrySectionWiseEmployeeDetails/{id}', [EmployeeLeaveEntryController::class, 'leaveEntrySectionWiseEmployeeDetails'])->name('leaveEntrySectionWiseEmployeeDetails');
        // Select Employee show leave entry list 
        Route::get('employeeLeaveEntryDetails/{id}', [EmployeeLeaveEntryController::class, 'employeeLeaveEntryDetails'])->name('employeeLeaveEntryDetails');

        // Employee leave entry list
        Route::get('employee_leave_entry_list', [EmployeeLeaveEntryController::class, 'employeeLeaveEntryList'])->name('employee_leave_entry_list');
        // Employee leave entry list search
        Route::get('employee_leave_entry_list_search/{sectionID}/{employeeID}/{startDate}/{endDate}/{pdf}', [EmployeeLeaveEntryController::class, 'leaveEntryListSearch'])->name('employee_leave_entry_list_search');
        // Section wise employee list search
        Route::get('sectionWaisEmployee/{sectionID}', [EmployeeLeaveEntryController::class, 'sectionWaisEmployee'])->name('sectionWaisEmployee');
        // Employee leave entry list delete
        Route::delete('employee_leave_entry_list_destroy/{id}', [EmployeeLeaveEntryController::class, 'employeeLeaveEntryListDestroy'])->name('employee_leave_entry_list_destroy');

        // Final Leave Report
        Route::resource('leave_report', leaveReportController::class);
        Route::get('leave_report_search/{year}/{month}/{pdf}', [leaveReportController::class, 'leaveReport'])->name('leave_report_search');

        // Late of Leave Manage
        Route::get('late_of_leave', [leaveReportController::class, 'lateOFLeave'])->name('late_of_leave');
        Route::get('late_of_leave_report_search/{year}/{month}/{pdf}', [leaveReportController::class, 'lateOFLeaveReport'])->name('late_of_leave_report_search');
        Route::post('late_of_leave_add', [leaveReportController::class, 'addLateOFLeave'])->name('late_of_leave_add');

        Route::resource('employee_leave_approve_dept', EmployeeLeaveApproveDeptController::class);
        Route::get('employee_leave_approve_dept_list', [EmployeeLeaveApproveDeptController::class, 'approvedIndex'])->name('employee_leave_approve_dept_list');

        Route::resource('employee_leave_approve_hr', EmployeeLeaveApproveHrController::class);
        Route::get('employee_leave_approve_hr_list', [EmployeeLeaveApproveHrController::class, 'approvedIndex'])->name('employee_leave_approve_hr_list');

        Route::resource('employee_leave_approve_manag', EmployeeLeaveApproveManagController::class);
        Route::get('employee_leave_approve_manag_list', [EmployeeLeaveApproveManagController::class, 'approvedIndex'])->name('employee_leave_approve_manag_list');

        Route::resource('emp_delayin_earlyout', EmployeeDelayinEarlyoutController::class);

        // performance
        Route::resource('performance_type', PerformanceTypeController::class);
        Route::resource('employee_performance', EmployeePerformanceController::class);
        Route::get('employeePerformanceBranchDetails/{id}', [EmployeePerformanceController::class, 'employeePerformanceBranchDetails'])->name('employeePerformanceBranchDetails');
        Route::get('employeePerformancType/{id}', [EmployeePerformanceController::class, 'employeePerformancType'])->name('employeePerformancType');
    });

    // hr admin pages
    Route::prefix('hradmin')->group(function () {
        // time table 
        Route::resource('work_time', WorkTimeController::class);
        Route::resource('late_time', LateTimeController::class);
        Route::resource('break_time', BreakTimeController::class);
        Route::resource('weekend_day', WeekendDayController::class);

        // manual  attendance input
        Route::get('manual_attendance_input', [AttendanceController::class, 'manualAttendanceInput'])->name('manual_attendance_input');
        Route::post('add_selected_employee', [AttendanceController::class, 'addSelectedEmployee'])->name('add_selected_employee');
        Route::get('employee_shorting/{employee_code}/{type_id}/{department_id}/{section_id}/{line_id}/{designation_id}/{salary_section_id}', [AttendanceController::class, 'employeeShorting'])->name('employee_shorting');

        // Monthly attendance time card
        Route::get('monthly_attendance_time_card', [AttendanceController::class, 'monthlyAttendanceTimeCard'])->name('monthly_attendance_time_card');
        Route::get('monthly_attendance_time_card_search/{month}/{pdf}', [AttendanceController::class, 'monthlyAttendanceTimeCardSearch'])->name('monthly_attendance_time_card_search');
        Route::get('monthly_attendance_time_card_search__/{month}/{pdf}', [AttendanceController::class, 'monthlyAttendanceTimeCardSearch'])->name('monthly_attendance_time_card_search__');

        // daily attendance summary search
        Route::get('daily_attendance_summary', [AttendanceController::class, 'dailyAttendanceSummary'])->name('daily_attendance_summary');
        Route::get('daily_attendance_summary_search/{sectionId}/{startDate}/{pdf}', [AttendanceController::class, 'dailyAttendanceSummarySearch'])->name('daily_attendance_summary_search');

        // present attendance search
        Route::get('present_attendance_list', [AttendanceController::class, 'presentAttendanceList'])->name('present_attendance_list');
        Route::get('present_attendance_search/{employeeCode}/{typeId}/{departmentId}/{sectionId}/{lineId}/{designationId}/{startDate}/{endDate}/{pdf}', [AttendanceController::class, 'presentAttendanceSearch'])->name('present_attendance_search');

        // absent attendance search
        Route::get('absent_attendance_list', [AttendanceController::class, 'absentAttendanceList'])->name('absent_attendance_list');
        Route::get('absent_attendance_search/{employeeCode}/{typeId}/{departmentId}/{sectionId}/{lineId}/{designationId}/{startDate}/{endDate}/{pdf}', [AttendanceController::class, 'absentAttendanceSearch'])->name('absent_attendance_search');

        // attendance report
        Route::resource('attendance', AttendanceController::class);
        Route::get('daily_attendance_search/{employeeID}/{startDate}/{endDate}/{pdf}', [AttendanceController::class, 'dailyAttendanceSearch'])->name('daily_attendance_search');
        Route::get('code_wais_employee/{employeeCode}', [AttendanceController::class, 'codehWaisEmployee'])->name('code_wais_employee');
        Route::get('branch_wais_employee/{branchID}', [AttendanceController::class, 'branchWaisEmployee'])->name('branch_wais_employee');

        Route::get('all_employee_attendance_chart', [AttendanceController::class, 'allEmployeeAttendanceChart'])->name('all_employee_attendance_chart');

        Route::get('daily_all_attendance', [AttendanceController::class, 'dailyAllAttendance'])->name('daily_all_attendance');
        Route::get('single_employee_attendance_search/{employeeID}', [AttendanceController::class, 'singleEmployeeAttendanceSearch'])->name('single_employee_attendance_search');
        Route::get('single_employee_attendance_chart/{employeeID}', [AttendanceController::class, 'singleEmployeeAttendanceChart'])->name('single_employee_attendance_chart');
        Route::get('single_employee_all_attendance_search', [AttendanceController::class, 'singleEmployeeAllAttendanceSearch'])->name('single_employee_all_attendance_search');

        Route::resource('promotion', PromotionController::class);
        Route::resource('resignation', ResignationController::class);
        Route::resource('termination', TerminationController::class);
        Route::resource('announcement', AnnouncementController::class);
        Route::get('single_employee_promotion/{employeeID}', [PromotionController::class, 'singleEmployeePromotion'])->name('single_employee_promotion');

        Route::get('single_employee_Announcement/{employeeID}', [AnnouncementController::class, 'single_employee_Announcement'])->name('single_employee_Announcement');

        Route::get('downloadAnnouncement/{file_path}', [AnnouncementController::class, 'downloadAnnouncement'])->name('downloadAnnouncement');

        Route::get('allEmpBranch', [AnnouncementController::class, 'allEmpBranch'])->name('allEmpBranch');
        Route::get('allEmpDepartment/{branchName}', [AnnouncementController::class, 'allEmpDepartment'])->name('allEmpDepartment');
        Route::get('allEmpDesignation', [AnnouncementController::class, 'allEmpDesignation'])->name('allEmpDesignation');
        Route::get('allEmployee', [AnnouncementController::class, 'allEmployee'])->name('allEmployee');
        Route::resource('holiday', HolidayController::class);
        Route::resource('meeting', MeetingController::class);
    });

    // download database
    Route::prefix('dbbackup')->group(function () {
        Route::resource('dbbackups', DownloaddbController::class);
        Route::get('download_db', [DownloaddbController::class, 'download_db'])->name('downloaddb.download_db');
        Route::get('downloadFile/{file_name}', [DownloaddbController::class, 'downloadFile'])->name('downloadFile');
        Route::get('db_destroy/{name}', [DownloaddbController::class, 'dbDestroy'])->name('db_destroy');
    });

    // payroll pages
    Route::prefix('payroll')->group(function () {
        Route::resource('payroll_head', PayrollHeadController::class);
        Route::resource('payroll_formulas', PayrollFormulaController::class);



        //income expense

        Route::get('/reports/income-expense-detail', [IncomeController::class, 'incomeExpenseDetailReport'])->name('report.income-expense-detail');
        Route::get('/report/income-expense/pdf', [IncomeController::class, 'incomeExpenseDetailReportPdf'])->name('report.income-expense-detail.pdf');


        // Payroll input
        Route::resource('payroll', PayrollController::class);
        Route::post('payroll_employee_update', [PayrollController::class, 'payrollEmployeeUpdate'])->name('payroll_employee_update');
        Route::get('payroll_employee_edit/{employee_id}', [PayrollController::class, 'payrollEmployeeEdit'])->name('payroll_employee_edit');

        Route::resource('payslip_type', PayslipTypeController::class);
        Route::resource('income_head', IncomeHeadController::class);
        Route::resource('allowance_option', AllowanceOptionController::class);
        Route::resource('loan_option', LoanOptionController::class);
        Route::resource('deduction_head', DeductionHeadController::class);

        Route::resource('employee_salaries', EmployeeSalaryController::class);
        Route::resource('income', IncomeController::class);
        Route::resource('allowance', AllowanceController::class);
        Route::resource('commission', CommissionController::class);
        Route::resource('deduction', DeductionController::class);
        Route::resource('loan', LoanController::class);
        Route::resource('other_payment', OtherPaymentController::class);
        Route::resource('overtime', OvertimeController::class);

        Route::resource('set_salaries', SetSalaryController::class);
        Route::get('set_salaries_create/{id}', [SetSalaryController::class, 'setSalariesCreate'])->name('set_salaries.set_salaries_create');
        Route::resource('monthly_salaries', MonthlySalaryController::class);
        Route::get('monthly_salaries_list/{month}/{pdf}', [MonthlySalaryController::class, 'monthlySalariesList'])->name('monthly_salaries_list');
        // not need after
        Route::get('monthly_salaries/{branch_id}/{month}/{pdf}', [MonthlySalaryController::class, 'monthlySalaries'])->name('monthly_salaries');

        Route::resource('payslips', PayslipController::class);
        Route::get('/get-payslip/{employeeId}', [PayslipController::class, 'getPayslipData']);
        Route::get('/get-all-payslip/{employeeId}', [PayslipController::class, 'getAllPayslipData']);

        // Route::get('salary_sheet', [PayslipController::class,'salary_sheet'])->name('salary_sheet');

    });

    // finance
    Route::prefix('finance')->group(function () {
        Route::resource('finances', FinanceGroupController::class);
        Route::resource('accounts', FinanceAccountController::class);
        Route::get('/get-parentchild/{group_code}', [FinanceGroupController::class, 'getParentChild'])->name('get-parentchild');
        // FinanceTransactionController
        Route::resource('finances_transaction', FinanceTransactionController::class);
        // account_name_list 
        Route::get('account_name_list', [FinanceTransactionController::class, 'accountNameList'])->name('account_name_list');
        Route::get('account_list/{code}', [FinanceTransactionController::class, 'accountList'])->name('account_list');
        // received_voucher
        Route::get('received_voucher', [FinanceTransactionController::class, 'receivedVoucher'])->name('received_voucher');
        Route::post('received_voucher_store', [FinanceTransactionController::class, 'receivedVoucherStore'])->name('received_voucher_store');
        Route::get('received_voucher_date_search/{startDate}/{endDate}/{pdf}', [FinanceTransactionController::class, 'receivedVoucherDateSearch'])->name('received_voucher_date_search');

        // Route::get('received_voucher_report_pdf/{voucherNo}/{voucherType}', [FinanceTransactionController::class, 'receivedVoucherReportPdf'])->name('received_voucher_report_pdf');
        // Route::get('payment_voucher_report_pdf/{voucherNo}', [FinanceTransactionController::class, 'paymentVoucherReportPdf'])->name('payment_voucher_report_pdf');
        Route::get('voucher_report_pdf/{voucherNo}/{voucherType}', [FinanceTransactionController::class, 'voucherReportPdf'])->name('voucher_report_pdf');

        // payment_voucher
        Route::get('payment_voucher', [FinanceTransactionController::class, 'paymentVoucher'])->name('payment_voucher');
        Route::post('payment_voucher_store', [FinanceTransactionController::class, 'paymentVoucherStore'])->name('payment_voucher_store');
        Route::get('payment_voucher_date_search/{startDate}/{endDate}/{pdf}', [FinanceTransactionController::class, 'paymentVoucherDateSearch'])->name('payment_voucher_date_search');

        // journal voucher
        Route::get('journal_voucher', [FinanceTransactionController::class, 'journalVoucher'])->name('journal_voucher');
        Route::post('journal_voucher_store', [FinanceTransactionController::class, 'journalVoucherStore'])->name('journal_voucher_store');
        Route::get('journal_voucher_date_search/{startDate}/{endDate}/{pdf}', [FinanceTransactionController::class, 'journalVoucherDateSearch'])->name('journal_voucher_date_search');

        // general ledger
        Route::get('general_ledger', [FinanceTransactionController::class, 'generalLedger'])->name('general_ledger');
        Route::get('general_ledger_search/{accountHead}/{startDate}/{endDate}/{pdf}', [FinanceTransactionController::class, 'generalLedgerSearch'])->name('general_ledger_search');
        //all Cash Book Ledger
        Route::get('cash_book_ledger', [FinanceTransactionController::class, 'cashBookLedger'])->name('cash_book_ledger');
        Route::get('cash_book_ledger_search/{accountHead}/{startDate}/{endDate}/{pdf}', [FinanceTransactionController::class, 'cashBookLedgerSearch'])->name('cash_book_ledger_search');
        //all Bank Book Ledger
        Route::get('bank_book_ledger', [FinanceTransactionController::class, 'bankBookLedger'])->name('bank_book_ledger');
        Route::get('bank_book_ledger_search/{accountHead}/{startDate}/{endDate}/{pdf}', [FinanceTransactionController::class, 'bankBookLedgerSearch'])->name('bank_book_ledger_search');

        //all customer due list
        Route::get('total_customer_receivable_list', [FinanceTransactionController::class, 'totalCustomerReceivableList'])->name('total_customer_receivable_list');
        Route::get('total_customer_receivable_list_search/{pdf}', [FinanceTransactionController::class, 'totalCustomerReceivableListSearch'])->name('total_customer_receivable_list_search');
        //all supplier due list
        Route::get('total_supplier_payable_list', [FinanceTransactionController::class, 'totalSupplierPayableList'])->name('total_supplier_payable_list');
        Route::get('total_supplier_payable_list_search/{pdf}', [FinanceTransactionController::class, 'totalSupplierPayableListSearch'])->name('total_supplier_payable_list_search');


        Route::get('summary_report', [FinanceTransactionController::class, 'summaryReport'])->name('summary_report');
        Route::get('summary_report_search/{startDate}/{endDate}/{pdf}', [FinanceTransactionController::class, 'summaryReportSearch'])->name('summary_report_search');

        Route::get('summary_report_full', [FinanceTransactionController::class, 'summaryReportFull'])->name('summary_report_full');
        Route::get('summary_report_full_search/{startDate}/{endDate}/{pdf}', [FinanceTransactionController::class, 'summaryReportFullSearch'])->name('summary_report_full_search');
    });
});

Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

require __DIR__ . '/auth.php';
