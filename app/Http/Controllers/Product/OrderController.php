<?php

namespace App\Http\Controllers\Product;

use App\Models\CustomerType;
use App\Models\CompanySetting;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\Customer;
use App\Models\CustomerLedger;
use App\Models\ProductOrderDetail;
use App\Models\ProductOrderDetailsChain;
use App\Models\Supplier;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\ProductBrand;
use App\Models\ProductUnit;
use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:read order|write order|create order', ['only' => ['index', 'show']]);
        $this->middleware('permission:create order', ['only' => ['create', 'store']]);
        $this->middleware('permission:write order', ['only' => ['edit', 'update', 'destroy']]);
    }

    // Product Details

    public function productDetails($id)
    {
        $productDetails = Product::select('products.id', 'products.unit_id', 'products.purchase_price', 'products.sales_price')
            ->with('unit')
            ->withSum('productStock', 'stock_in_quantity') // "product_stock_sum_stock_in_quantity"
            ->withSum('productStock', 'stock_out_quantity') // "product_stock_sum_stock_out_quantity"
            ->where('id', $id)
            ->get();
        return response()->json($productDetails);
    }

    public function getOrderList($sdate, $edate, $type, $pdf)
    {

        $query = "SELECT * from
        (SELECT orders.*,orders.status as _status, finance_accounts.account_name, finance_accounts.account_mobile FROM orders
        LEFT JOIN  finance_accounts ON finance_accounts.id = orders.customer_id)v
        WHERE (v.order_date BETWEEN '$sdate' AND '$edate' OR v.delivery_date BETWEEN '$sdate' AND '$edate') AND (v._status= $type OR $type='2')
        ORDER BY v.id DESC";

        $orderList = DB::table(DB::raw("($query) AS subquery"))
            ->select('id', 'order_no', 'order_date', 'delivery_date', 'account_name', '_status')
            ->orderBy('id', 'DESC')
            ->get();
        if ($pdf == "list") {
            return response()->json($orderList);
        }
        if ($pdf == "pdfurl") {
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;
            $data['company_address'] = $companySetting->company_address;
            $data['company_mobile'] = $companySetting->company_mobile;
            $data['company_logo_one'] = $companySetting->company_logo_one;
            $data['start_date'] = $sdate;
            $data['end_date'] = $edate;
            $pdf = PDF::loadView('pages.pdf.order_report_date_wise_pdf', array('orderList' => $orderList, 'data' => $data));
            // $pdf->setPaper('A4', 'landscape');
            return $pdf->stream(Carbon::now() . '-order_report_date_wise_pdf.pdf');
        }
    }

    public function orderSummary()
    {

        return view('pages.product.order.order_summary_report');
    }


    public function orderSummaryReport($sdate, $edate, $pdf)
    {

        $query = "SELECT 
            od.product_id, 
            p.product_name, 
            COUNT(od.order_no) AS order_count,
            SUM(od.stock_out_quantity) AS total_stock_out,
            pu.unit_name
        FROM 
            order_details od
        JOIN
            orders o
        ON
            od.order_id = o.id
        JOIN 
            products p
        ON 
            od.product_id = p.id
        JOIN 
            product_units pu
        ON 
            p.unit_id = pu.id
        WHERE 
            DATE(od.stock_date) BETWEEN '$sdate' AND '$edate'
            AND o.status = 0
        GROUP BY 
            od.product_id, p.product_name, pu.unit_name";

        $orderSummaryReport = DB::table(DB::raw("($query) AS subquery"))
            ->select('product_id', 'product_name', 'order_count', 'total_stock_out', 'unit_name')
            ->orderBy('product_id', 'ASC')
            ->get();
        if ($pdf == "list") {
            return response()->json($orderSummaryReport);
        }
        if ($pdf == "pdfurl") {
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;
            $data['company_address'] = $companySetting->company_address;
            $data['company_mobile'] = $companySetting->company_mobile;
            $data['company_logo_one'] = $companySetting->company_logo_one;
            $data['start_date'] = $sdate;
            $data['end_date'] = $edate;
            $pdf = PDF::loadView('pages.pdf.order_summary_report_pdf', array('orderSummaryReport' => $orderSummaryReport, 'data' => $data));
            // $pdf->setPaper('A4', 'landscape');
            return $pdf->stream(Carbon::now() . '-order_summary_report_pdf.pdf');
        }
    }

    public function index()
    {

        $productOrders = Order::leftJoin('finance_accounts', 'finance_accounts.id', '=', 'orders.customer_id')
            ->select('orders.*', 'finance_accounts.account_name')
            ->orderby('id', 'desc')->get();

        //        dd($productOrders);

        return view('pages.product.order.index', compact('productOrders'));
    }

    public function create()
    {
        $products = Product::where('is_saleable', 1)->where('status', 1)->get();
        $units = ProductUnit::get();
        $customerAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', '100020001')->get();
        $toAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', 'like', '10002%')->whereNotIn('account_group_code', ['100020001'])->get();
        $customerTypes = CustomerType::where('status', 1)->pluck('type_name', 'id')->all();
        return view('pages.product.order.create', compact('products', 'units', 'customerAccounts', 'toAccounts', 'customerTypes'));
    }


    public function store(Request $request)
    {

        //    dd($request->all());
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            // 'givenAmount' => 'required',
            'table_product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $netTotalAmount = $request->input('netTotalAmount');
        $customerId = $request->input('customer_id');

        $orderDate = $request->input('order_date');
        $deliveryDate = $request->input('delivery_date');

        // $customerInvoiceNo = $request->input('customer_invoice_no');
        $givenAmount = $request->input('givenAmount') ?: 0;
        $customerDues = $request->input('table_customer_due');
        $remarks = $request->input('remarks');
        $ledgerDate = Carbon::now();
        $done_by = Auth::user()->name;
        //        $done_by = Auth::user()->first_name . ' ' . Auth::user()->last_name;

        if ($customerDues < 0) {
            $isPreviousDue = 1;
        } else {
            $isPreviousDue = 0;
        }

        DB::beginTransaction();
        try {
            $orderNo = DB::table('invoiceno')->first('order_no');
            $getOrderNo = $orderNo->order_no;
            $orderNumber = 'ORD' . str_pad($getOrderNo, 6, '0', STR_PAD_LEFT);

            DB::table('invoiceno')->update(['order_no' => $getOrderNo + 1,]);
            //            dd('done');

            $order = new Order();
            $order->order_no = $orderNumber;
            $order->customer_id = $customerId;
            $order->order_date = $orderDate;
            $order->delivery_date = $deliveryDate;
            $order->remarks = $remarks;
            $order->done_by = $done_by;
            $order->status = 0;
            //            dd($order->attributesToArray());

            if ($request->hasfile('file')) {
                $image = $request->file('file');
                $name = date('d-m-Y-H-i-s') . '_' . $image->getClientOriginalName();
                $image_path = $image->storeAs('public/images/orders', $name);
                $order->file = $image_path;
            }

            $order->save();

            // $CustomerLedgerIn2 = CustomerLedger::create([
            //             'customer_id' => $customerId,
            //             // 'customer_invoice_no'           => $customerInvoiceNo,
            //             'ledger_date' => $ledgerDate,
            //             'order_no' => $orderNumber,
            //             'debit' => $netTotalAmount,
            //             'credit' => 0,
            //             'remarks' => $remarks,
            //             'is_previous_due' => $isPreviousDue,
            //             // $service->servicevlans()->save($serviceVlan);
            //             'done_by' => $done_by,
            // ]);

            // $CustomerLedgerIn = CustomerLedger::create([
            //             'customer_id' => $customerId,
            //             // 'customer_invoice_no'           => $customerInvoiceNo,
            //             'ledger_date' => $ledgerDate,
            //             'order_no' => $orderNumber,
            //             'debit' => 0,
            //             'credit' => $givenAmount,
            //             'remarks' => $remarks,
            //             'is_previous_due' => $isPreviousDue,
            //             // $service->servicevlans()->save($serviceVlan);
            //             'done_by' => $done_by,
            // ]);

            foreach ($request->get('table_product_id') as $key => $productId) {


                $orderCreate = new OrderDetail();

                $orderQuantity = $request->input('table_product_quantity')[$key];
                $orderPrice = $request->input('table_product_price')[$key];
                $orderDiscount = $request->input('table_product_discount')[$key];
                $orderTotal = ($orderQuantity * $orderPrice) - $orderDiscount;

                $orderCreate->order_id = $order->id;
                $orderCreate->stock_type = 'Order';
                $orderCreate->stock_date = $ledgerDate;
                $orderCreate->product_id = $request->input('table_product_id')[$key];
                $orderCreate->customer_id = $customerId;
                $orderCreate->order_date = $orderDate;
                $orderCreate->delivery_date = $deliveryDate;
                $orderCreate->order_no = $orderNumber;
                $orderCreate->stock_in_quantity = 0;
                $orderCreate->stock_out_quantity = $orderQuantity;
                $orderCreate->stock_out_unit_price = $orderPrice;
                $orderCreate->stock_out_discount = $orderDiscount;
                $orderCreate->stock_out_total_amount = $orderTotal;
                $orderCreate->remarks = $remarks;
                $orderCreate->done_by = $done_by;

                //                dd($orderCreate->attributesToArray());

                $orderCreate->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // Log the error message and stack trace
            Log::error('Order creation failed: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'Ops ! Could Not add Order history. DB transaction lost. Error: ' . $e->getMessage()]);
        }

        //        return back()->with([
        //                    'message' => 'Sales History Added Successfully!',
        //                    'alert-type' => 'success'
        //        ]);

        return redirect()->route('order.index')->with([
            'message' => 'Sales History Added Successfully!',
            'alert-type' => 'success'
        ]);
    }


    public function orderApproved($id)
    {
        $order = Order::where('orders.id', $id)->first();
        $order->status = 1;
        $order->save();

        return redirect()->route('order.index')->with([
            'message' => 'Order History Approved Successfully!',
            'alert-type' => 'success'
        ]);
    }
    public function orderDetailsPdf($id)
    {
        $orders = Order::select('orders.*')
            ->with(['orderdetail:*'])
            ->with(['customer:id,account_name,account_address,account_mobile'])
            ->where('orders.id', $id)
            ->first();

        // dd($orders);

        // $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        // $data['company_name'] = $companySetting->company_name;
        // $data['company_address'] = $companySetting->company_address;
        // $data['company_mobile'] = $companySetting->company_mobile;
        // $data['company_logo_one'] = $companySetting->company_logo_one;
        $pdf = PDF::loadView('pages.pdf.order_details_report_pdf', array('orders' => $orders));
        // $pdf->setPaper('A4', 'landscape');
        return $pdf->stream(Carbon::now() . '-order_details_report_pdf.pdf');
    }
    public function orderView($id)
    {
        $orders = Order::select('orders.*')
            ->with(['orderdetail:*'])
            ->with(['customer:id,account_name,account_address,account_mobile'])
            ->where('orders.id', $id)
            ->first();
        return view('pages.product.order.show', compact('orders'));
    }
    public function show($id)
    {
        $orders = Order::select('orders.*')
            ->with(['orderdetail:*'])
            ->with(['customer:id,account_name,account_address,account_mobile'])
            ->where('orders.id', $id)
            ->first();
        return view('pages.product.order.show', compact('orders'));
    }
    public function orderEdit($id)
    {
        // $products = Product::where('status', 1)->get();
        // $units = ProductUnit::get();

        $products = Product::where('is_saleable', 1)->where('status', 1)->get();

        $orders = Order::select('orders.*')
            ->with(['orderdetail:*'])
            ->with(['customer:id,account_name,account_address,account_mobile'])
            ->where('orders.id', $id)
            ->first();
        return view('pages.product.order.edit', compact('products', 'orders'));
    }
    public function edit($id)
    {

        //        $orders = Order::find($id);
        //        $orders = OrderDetail::find($id);

        $products = Product::where('status', 1)->get();

        $orders = Order::select('orders.*')
            ->with(['orderdetail:*'])
            ->with(['customer:id,customer_name,customer_address,customer_mobile'])
            ->where('orders.id', $id)
            ->first();

        $units = ProductUnit::get();
        $customers = Customer::where('status', 1)->get();

        //        dd($orders);

        return view('pages.product.order.edit', compact('products', 'orders', 'units', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {

        //        dd($request->all());

        $validator = Validator::make($request->all(), [
            //                    'customer_id' => 'required',
            'order_date' => 'required',
            'delivery_date' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        //        DB::beginTransaction();
        //        try {

        $order->order_date = $request->input('order_date');
        $order->delivery_date = $request->input('delivery_date');
        $order->remarks = $request->input('remarks');
        $order->status = $request->input('status');
        $order->approveby = Auth::user()->name;

        //            dd($order->attributesToArray());
        //            
        //            if ($request->hasfile('file')) {
        //                $image = $request->file('file');
        //                $name = date('d-m-Y-H-i-s') . '_' . $image->getClientOriginalName();
        //                $image_path = $image->storeAs('public/images/orders', $name);
        //                $order->file = $image_path;
        //            }

        $order->save();

        foreach ($request->get('id') as $key => $id) {

            $orderCreate = OrderDetail::find($id);

            $orderCreate->product_id = $request->input('product_id')[$key];
            $orderQuantity = $orderCreate->stock_out_quantity = $request->input('orderquantity')[$key];
            $orderPrice = $orderCreate->stock_out_unit_price = $request->input('stock_out_unit_price')[$key];
            $orderDiscount = $orderCreate->stock_out_discount = $request->input('stock_out_discount')[$key];
            $orderCreate->stock_out_total_amount = ($orderQuantity * $orderPrice) - $orderDiscount;

            //                dd($orderCreate);
            $orderCreate->save();
        }


        //            DB::commit();
        //        } catch (\Exception $e) {
        //            DB::rollback();
        //            return back()->withErrors(['error' => 'Ops ! Could Not add Sales history. DB transaction lost']);
        //        }


        return redirect()->route('order.index')->with([
            'message' => 'Sales History Added Successfully!',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function detailsDestroy($id)
    {

        $order = OrderDetail::find($id);

        if ($order) {
            $order->delete();
            return back()->with([
                'message' => 'Product successfully deleted!',
                'alert-type' => 'success'
            ]);
        }
    }
}
