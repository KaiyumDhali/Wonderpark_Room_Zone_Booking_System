<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;

use App\Models\SupplierLedger;
use App\Models\Supplier;
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
use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
//use Barryvdh\DomPDF\Facade\Pdf;


class PurchaseController extends Controller {

    function __construct() {
        $this->middleware('permission:read purchase|write purchase|create purchase', ['only' => ['index', 'show']]);
        $this->middleware('permission:create purchase', ['only' => ['create', 'store']]);
        $this->middleware('permission:write purchase', ['only' => ['edit', 'update', 'destroy']]);
        $this->middleware('permission:read purchase report', ['only' => ['purchase_report', 'purchaseDateSearch', 'purchaseSearch']]);
    }

    // public function index() {
    //     $products = Product::where('is_purchaseable', 1)->get();
    //     $units = ProductUnit::get();
    //     $suppliers = Supplier::where('status', 1)->get();

    //     return view('pages.product.stock._stock-add', compact('products', 'suppliers', 'units'));
    // }
    public function index()
    {
        $products = Product::where('is_purchaseable', 1)->get();
        $units = ProductUnit::get();
        $suppliers = Supplier::where('status', 1)->get();
        $supplierAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', '400010001')->get();
        // dd($supplierAccounts);
        $fromAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', 'like', '10002%')->get();
        // dd($fromAccounts);
        return view('pages.product.stock._stock-add', compact('products', 'suppliers', 'units', 'supplierAccounts', 'fromAccounts'));
    }

    // public function supplierDetails($id) {
    //     // $id = 2;
    //     $supplierDetails = Supplier::select('suppliers.id', 'suppliers.supplier_name', 'suppliers.supplier_mobile')
    //             ->withSum('supplierLedger', 'debit') // "supplier_ledger_sum_debit"
    //             ->withSum('supplierLedger', 'credit') // "supplier_ledger_sum_credit"
    //             ->where('id', $id)
    //             ->get();
    //     return response()->json($supplierDetails);
    // }
    public function supplierDetails($ac_name)
    {
        $summary = DB::table('finance_accounts AS fa')
            ->join(DB::raw('(
            SELECT
                sl.to_acc_name,
                SUM(CASE WHEN sl.balance_type = "Dr" THEN sl.amount ELSE 0 END) AS total_debits,
                SUM(CASE WHEN sl.balance_type = "Cr" THEN sl.amount ELSE 0 END) AS total_credits,
                SUM(CASE WHEN sl.balance_type = "Cr" THEN sl.amount ELSE 0 END) -
                SUM(CASE WHEN sl.balance_type = "Dr" THEN sl.amount ELSE 0 END) AS net_balance
            FROM
                finance_transactions AS sl
            GROUP BY
                sl.to_acc_name
            ) AS balances'), 'fa.account_name', '=', 'balances.to_acc_name')
            ->where('fa.account_name', $ac_name)
            ->select('fa.*', 'balances.total_debits', 'balances.total_credits', 'balances.net_balance')
            ->first();

        if ($summary) {
            return response()->json($summary);
        } else {
            return response()->json(['error' => 'Not Found'], 404);
        }
    }

    public function store(Request $request) {
        // dd('Purchase Success');

        $validator = Validator::make($request->all(), [
                    'supplier_id' => 'required',
                    'givenAmount' => 'required',
                    'table_product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $supplierId = $request->input('supplier_id');
        $supplierInvoiceNo = $request->input('supplier_invoice_no');
        $netTotalAmount = $request->input('netTotalAmount');
        $givenAmount = $request->input('givenAmount');
        $supplierDues = $request->input('table_supplier_due');
        $remarks = $request->input('remarks');
        $ledgerDate = Carbon::now();
        $done_by = Auth::user()->name;

        if ($supplierDues < 0) {
            $isPreviousDue = 1;
        } else {
            $isPreviousDue = 0;
        }

        DB::beginTransaction();
        try {

            $purchaseNo = DB::table('invoiceno')->first('purchase_no');
            $getPurchaseNo = $purchaseNo->purchase_no;
            $purchaseNumber = 'PUR' . str_pad($getPurchaseNo, 6, '0', STR_PAD_LEFT);
            // if ($purchaseNo) {
            //          $purchaseNumber = 'PUR' . str_pad((int) substr($getPurchaseNo, 4) + 1, 5, '0', STR_PAD_LEFT);
            //          {{ str_pad($voters->id, 4, '0', STR_PAD_LEFT) }}
            //     } else {
            //         $invoiceNumber = 'PUR00001';
            // }
            DB::table('invoiceno')->update([
                'purchase_no' => $getPurchaseNo + 1,
            ]);
            //dd('done');
            $supplierLedgerIn = SupplierLedger::create([
                        'supplier_id' => $supplierId,
                        'supplier_invoice_no' => $supplierInvoiceNo,
                        'ledger_date' => $ledgerDate,
                        'invoice_no' => $purchaseNumber,
                        'debit' => 0,
                        'credit' => $netTotalAmount,
                        'remarks' => $remarks,
                        'is_previous_due' => $isPreviousDue,
                        'done_by' => $done_by,
                            // $service->servicevlans()->save($serviceVlan);
            ]);
            $supplierLedgerIn2 = SupplierLedger::create([
                        'supplier_id' => $supplierId,
                        'supplier_invoice_no' => $supplierInvoiceNo,
                        'ledger_date' => $ledgerDate,
                        'invoice_no' => $purchaseNumber,
                        'debit' => $givenAmount,
                        'credit' => 0,
                        'remarks' => $remarks,
                        'is_previous_due' => $isPreviousDue,
                        'done_by' => $done_by,
                            // $service->servicevlans()->save($serviceVlan);
            ]);
            foreach ($request->get('table_product_id') as $key => $productId) {
                $stockIn = new Stock();

                $stockQuantity = $request->input('table_product_quantity')[$key];
                $stockPrice = $request->input('table_product_price')[$key];
                $stockDiscount = $request->input('table_product_discount')[$key];
                $stockTotal = ( $stockQuantity * $stockPrice) - $stockDiscount;

                $stockIn->stock_type = 'In';
                $stockIn->stock_date = $ledgerDate;
                $stockIn->product_id = $request->input('table_product_id')[$key];
                $stockIn->supplier_id = $supplierId;
                //    $stockIn->purchase_price = NULL;
                $stockIn->supplier_invoice_no = $supplierInvoiceNo;
                $stockIn->stock_in_quantity = $stockQuantity;
                $stockIn->stock_out_quantity = 0;
                $stockIn->stock_in_unit_price = $stockPrice;
                $stockIn->stock_in_discount = $stockDiscount;
                $stockIn->stock_in_total_amount = $stockTotal;
                $stockIn->invoice_no = $purchaseNumber;
                $stockIn->remarks = $remarks;
                $stockIn->done_by = $done_by;
                $stockIn->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Ops ! Could Not add purchase history. DB transaction lost']);
        }
        return back()->with([
                    'message' => 'Purchase History Added Successfully!',
                    'alert-type' => 'success'
        ]);

        // $latestInvoice = SupplierLedger::latest()->first();
        // if ($latestInvoice) {
        //     $invoiceNumber = 'INV' . str_pad((int) substr($latestInvoice->invoice_no, 4) + 1, 5, '0', STR_PAD_LEFT);
        // } else {
        //     $invoiceNumber = 'INV00001';
        // }
        // $input['invoice_no'] =  $invoiceNumber;
        // $input['ledger_date'] =  Date::now();
        // $input['status'] = empty($input['status']) ? 1 : $input['status'];
        // SupplierLedger::create($input);
        //  return redirect()->route('supplier_ledgers.index')->with([
        //     'message' => 'successfully created !',
        //     'alert-type' => 'success'
        // ]);
    }

    public function purchase_report(Request $request) {
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');
        // Parse start and end dates using Carbon
        $startDateTimeObj = Carbon::parse($startDate);
        $endDateTimeObj = Carbon::parse($endDate);
        // Set the start date to the second of the day
        $startDateObj = $startDateTimeObj->startOfDay();
        $endDateObj = $endDateTimeObj->endOfDay();
        // Invoice Type
        $inv = 'PUR';
        $invoices = Stock::whereBetween('stock_date', [$startDateObj, $endDateObj])->where('invoice_no', 'LIKE', '%' . $inv . '%')->orderBy('id', 'DESC')->get()->groupBy('invoice_no');
        // dd($invoices);
        $stocks = NULL;
        return view('pages.product.stock.purchase_report', compact('invoices', 'stocks', 'startDate', 'endDate'));
    }

    public function purchaseDateSearch(Request $request) {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        // Parse start and end dates using Carbon
        $startDateTimeObj = Carbon::parse($startDate);
        $endDateTimeObj = Carbon::parse($endDate);
        // Set the start date to the second of the day
        $startDateObj = $startDateTimeObj->startOfDay();
        $endDateObj = $endDateTimeObj->endOfDay();

        $inv = 'PUR';
        $invoices = Stock::whereBetween('stock_date', [$startDateObj, $endDateObj])->where('invoice_no', 'LIKE', '%' . $inv . '%')->orderBy('id', 'DESC')->get()->groupBy('invoice_no');
        // $stocks = NULL;
        return response()->json($invoices);
    }

    public function purchaseSearch(Request $request) {
        
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
        $inv = 'PUR';
        $invoices = Stock::whereBetween('stock_date', [$startDateObj, $endDateObj])->where('invoice_no', 'LIKE', '%' . $inv . '%')->orderBy('id', 'DESC')->get()->groupBy('invoice_no');

        $stocks = Stock::with(['product', 'supplier'])->where('invoice_no', $invoice_list)->get();
        $supplierPayment = SupplierLedger::where('invoice_no', $invoice_list)->sum('debit');
        // $supplierLedger = SupplierLedger::where([['invoice_no', $purchaseId],['debit', '>', 0]])->get(['debit']);
        //  dd($supplierLedger);
        //  
        return view('pages.product.stock.purchase_report', compact('stocks', 'invoices', 'supplierPayment', 'startDate', 'endDate'));

        
//        $html = View::make('pages.pdf.purchase_report_pdf', compact('stocks', 'invoices', 'supplierPayment', 'startDate', 'endDate'))->render();
//        $dompdf = new Dompdf();
//        $dompdf->loadHtml($html);
//        $dompdf->setPaper('A4', 'landscape');
//        $dompdf->render();
//        $dompdf->stream('purchase_report.pdf');
    }



    public function purchaseSearchPdf($invoices) {
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;
            $data['company_logo_one'] = $companySetting->company_logo_one;

            $stocks = Stock::with(['product', 'supplier'])->where('invoice_no', $invoices)->get();
            $supplierPayment = SupplierLedger::where('invoice_no', $invoices)->sum('debit');

            $pdf = PDF::loadView('pages.pdf.purchase_report_pdf', array('stocks' => $stocks, 'invoices' => $invoices, 'supplierPayment' => $supplierPayment, 'data' => $data));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream(Carbon::now().'-purchase_report.pdf');

    }

}
