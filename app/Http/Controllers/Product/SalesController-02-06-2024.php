<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;

use App\Models\CompanySetting;
use App\Models\Customer;
use App\Models\CustomerLedger;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Validator;
use Carbon\Carbon;
use Auth;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\View;


class SalesController extends Controller {

    function __construct() {
        $this->middleware('permission:read sales|write sales|create sales', ['only' => ['index', 'show']]);
        $this->middleware('permission:create sales', ['only' => ['create', 'store']]);
        $this->middleware('permission:write sales', ['only' => ['edit', 'update', 'destroy']]);
        $this->middleware('permission:read sales report', ['only' => ['sales_report', 'salesDateSearch', 'salesSearch']]);
    }

    public function index() {
        $products = Product::where('is_saleable', 1)->get();
        $units = ProductUnit::get();
        $customers = Customer::where('status', 1)->get();

        return view('pages.product.stock._stock-out', compact('products', 'units', 'customers'));
    }

    public function customerDetails($id) {
        // $id = 2;
        $customerDetails = Customer::select('customers.id', 'customers.customer_name', 'customers.customer_mobile')
                ->withSum('customerLedger', 'debit') // "customer_ledger_sum_debit"
                ->withSum('customerLedger', 'credit') // "customer_ledger_sum_credit"
                ->where('id', $id)
                ->get();
        return response()->json($customerDetails);
    }

    public function store(Request $request) {
        // dd('Purchase Success');
        $validator = Validator::make($request->all(), [
                    'customer_id' => 'required',
                    'givenAmount' => 'required',
                    'table_product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $netTotalAmount = $request->input('netTotalAmount');
        $customerId = $request->input('customer_id');
        // $customerInvoiceNo = $request->input('customer_invoice_no');
        $givenAmount = $request->input('givenAmount');
        $customerDues = $request->input('table_customer_due');
        $remarks = $request->input('remarks');
        $ledgerDate = Carbon::now();
        $done_by = Auth::user()->first_name . ' ' . Auth::user()->last_name;

        if ($customerDues < 0) {
            $isPreviousDue = 1;
        } else {
            $isPreviousDue = 0;
        }

        DB::beginTransaction();
        try {
            $salesNo = DB::table('invoiceno')->first('sales_no');
            $getSalesNo = $salesNo->sales_no;
            $salesNumber = 'INV' . str_pad($getSalesNo, 6, '0', STR_PAD_LEFT);
            DB::table('invoiceno')->update([
                'sales_no' => $getSalesNo + 1,
            ]);
            //dd('done');

            $CustomerLedgerIn2 = CustomerLedger::create([
                        'customer_id' => $customerId,
                        // 'customer_invoice_no'           => $customerInvoiceNo,
                        'ledger_date' => $ledgerDate,
                        'invoice_no' => $salesNumber,
                        'debit' => $netTotalAmount,
                        'credit' => 0,
                        'remarks' => $remarks,
                        'is_previous_due' => $isPreviousDue,
                        // $service->servicevlans()->save($serviceVlan);
                        'done_by' => $done_by,
            ]);

            $CustomerLedgerIn = CustomerLedger::create([
                        'customer_id' => $customerId,
                        // 'customer_invoice_no'           => $customerInvoiceNo,
                        'ledger_date' => $ledgerDate,
                        'invoice_no' => $salesNumber,
                        'debit' => 0,
                        'credit' => $givenAmount,
                        'remarks' => $remarks,
                        'is_previous_due' => $isPreviousDue,
                        // $service->servicevlans()->save($serviceVlan);
                        'done_by' => $done_by,
            ]);

            foreach ($request->get('table_product_id') as $key => $productId) {
                $stockOut = new Stock();

                $stockQuantity = $request->input('table_product_quantity')[$key];
                $stockPrice = $request->input('table_product_price')[$key];
                $stockDiscount = $request->input('table_product_discount')[$key];
                $stockTotal = ( $stockQuantity * $stockPrice) - $stockDiscount;

                $stockOut->stock_type = 'Out';
                $stockOut->stock_date = $ledgerDate;
                $stockOut->product_id = $request->input('table_product_id')[$key];
                $stockOut->customer_id = $customerId;
                $stockOut->invoice_no = $salesNumber;
                $stockOut->stock_in_quantity = 0;
                $stockOut->stock_out_quantity = $stockQuantity;
                $stockOut->stock_out_unit_price = $stockPrice;
                $stockOut->stock_out_discount = $stockDiscount;
                $stockOut->stock_out_total_amount = $stockTotal;
                $stockOut->remarks = $remarks;
                $stockOut->done_by = $done_by;
                $stockOut->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Ops ! Could Not add Sales history. DB transaction lost']);
        }
        return back()->with([
                    'message' => 'Sales History Added Successfully!',
                    'alert-type' => 'success'
        ]);
    }

    public function sales_report(Request $request) {
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');
        // Parse start and end dates using Carbon
        $startDateTimeObj = Carbon::parse($startDate);
        $endDateTimeObj = Carbon::parse($endDate);
        // Set the start date to the second of the day
        $startDateObj = $startDateTimeObj->startOfDay();
        $endDateObj = $endDateTimeObj->endOfDay();
        // Invoice Type
        $inv = 'INV';
        $invoices = Stock::whereBetween('stock_date', [$startDateObj, $endDateObj])->where('invoice_no', 'LIKE', '%' . $inv . '%')->orderBy('id', 'DESC')->get()->groupBy('invoice_no');
        $stocks = NULL;
        // dd($invoices);
        return view('pages.product.stock.sales_report', compact('invoices', 'stocks', 'startDate', 'endDate'));
    }

    public function salesDateSearch(Request $request) {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        // Parse start and end dates using Carbon
        $startDateTimeObj = Carbon::parse($startDate);
        $endDateTimeObj = Carbon::parse($endDate);
        // Set the start date to the second of the day
        $startDateObj = $startDateTimeObj->startOfDay();
        $endDateObj = $endDateTimeObj->endOfDay();

        $inv = 'INV';
        $invoices = Stock::whereBetween('stock_date', [$startDateObj, $endDateObj])->where('invoice_no', 'LIKE', '%' . $inv . '%')->orderBy('id', 'DESC')->get()->groupBy('invoice_no');
        // $stocks = NULL;
        return response()->json($invoices);
    }

    public function salesSearch(Request $request) {
        $invoice_list = $request->input('invoice_list');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        // Parse start and end dates using Carbon
        $startDateTimeObj = Carbon::parse($startDate);
        $endDateTimeObj = Carbon::parse($endDate);
        // Set the start date to the second of the day
        $startDateObj = $startDateTimeObj->startOfDay();
        $endDateObj = $endDateTimeObj->endOfDay();
        // Invoice Type
        $inv = 'INV';
        $invoices = Stock::whereBetween('stock_date', [$startDateObj, $endDateObj])->where('invoice_no', 'LIKE', '%' . $inv . '%')->orderBy('id', 'DESC')->get()->groupBy('invoice_no');

        $stocks = Stock::with(['product', 'customer'])->where('invoice_no', $invoice_list)->get();
        $customerPayment = CustomerLedger::where('invoice_no', $invoice_list)->sum('credit');
        // dd($customerLedger);
        return view('pages.product.stock.sales_report', compact('stocks', 'invoices', 'customerPayment', 'startDate', 'endDate'));
    }

    public function salesSearchPdf($invoices) {
              
        $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        $data['company_name'] = $companySetting->company_name;
        $data['company_logo_one'] = $companySetting->company_logo_one;

        $stocks = Stock::with(['product', 'customer'])->where('invoice_no', $invoices)->get();
        $customerPayment = CustomerLedger::where('invoice_no', $invoices)->sum('credit');
        
        $pdf = PDF::loadView('pages.pdf.sales_report_pdf', array('stocks' => $stocks, 'invoices' => $invoices, 'customerPayment' => $customerPayment, 'data' => $data));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream(Carbon::now() . '-sales_report.pdf');
        
    }



}
