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


class PurchaseController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:read purchase|write purchase|create purchase', ['only' => ['index', 'show']]);
        $this->middleware('permission:create purchase', ['only' => ['create', 'store']]);
        $this->middleware('permission:write purchase', ['only' => ['edit', 'update', 'destroy']]);
        $this->middleware('permission:read purchase report', ['only' => ['purchase_report', 'purchaseDateSearch', 'purchaseSearch']]);
    }

    public function index()
    {
        $products = Product::where('is_purchaseable', 1)->get();
        $units = ProductUnit::get();
        $suppliers = Supplier::where('status', 1)->get();
        $supplierAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', '400010001')->get();
        // dd($supplierAccounts);
        $fromAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', 'like', '10002%')->whereNotIn('account_group_code', ['100020001'])->get();
        // dd($fromAccounts);
        return view('pages.product.stock._stock-add', compact('products', 'suppliers', 'units', 'supplierAccounts', 'fromAccounts'));
    }

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

    // Purchase Store -------start------
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required',
            'givenAmount' => 'required',
            'table_product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $supplierId = $request->input('supplier_id');

        $finAccId = FinanceAccount::where('account_status', 1)->where('account_name', $supplierId)->first();

        $supplierInvoiceNo = $request->input('supplier_invoice_no');
        $netTotalAmount = $request->input('netTotalAmount');
        $givenAmount = $request->input('givenAmount');
        $payAccount = $request->input('pay_account');

        $payAccName = FinanceAccount::where('account_status', 1)->where('id', $payAccount)->first();

        // $supplierDues = $request->input('table_supplier_due');
        $remarks = $request->input('remarks');
        $ledgerDate = Carbon::now();
        $done_by = Auth::user()->name;

        $crVoucher = DB::table('invoiceno')->first('cr_voucher_no');
        $getCrVoucherNo = $crVoucher->cr_voucher_no;
        $crVoucherNo = '01PV' . str_pad($getCrVoucherNo, 6, '0', STR_PAD_LEFT);
        DB::table('invoiceno')->update([
            'cr_voucher_no' => $getCrVoucherNo + 1,
        ]);

        DB::beginTransaction();
        try {

            $purchaseNo = DB::table('invoiceno')->first('purchase_no');
            $getPurchaseNo = $purchaseNo->purchase_no;
            $purchaseNumber = 'PUR' . str_pad($getPurchaseNo, 6, '0', STR_PAD_LEFT);

            DB::table('invoiceno')->update([
                'purchase_no' => $getPurchaseNo + 1,
            ]);

            $pro_all_data = [];

            foreach ($request->get('table_product_id') as $key => $productId) {

                $stockIn = new Stock();

                $stockQuantity = $request->input('table_product_quantity')[$key];
                $stockPrice = $request->input('table_product_price')[$key];
                $stockDiscount = $request->input('table_product_discount')[$key];
                $stockTotal = ($stockQuantity * $stockPrice) - $stockDiscount;

                $product = Product::find($productId);
                $productName = $product ? $product->product_name : 'Unknown Product';

                $stockIn->stock_type = 'In';
                $stockIn->stock_date = $ledgerDate;
                $stockIn->product_id = $request->input('table_product_id')[$key];
                $stockIn->supplier_id = $finAccId->id;
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
                $pro_all_data[] = "Invoice No: $purchaseNumber, Name: $productName, Qty: $stockQuantity, Unit Price: $stockPrice, Discount: $stockDiscount, Total: $stockTotal\n";
            }
            // $pro_all_data_string = implode("; ", $pro_all_data);
            $pro_all_data_string = implode("\n", $pro_all_data);
            echo $pro_all_data_string;

            $financeTransaction = FinanceTransaction::create([
                'company_code' => '01',
                'voucher_no' => $crVoucherNo,
                'voucher_date' => Carbon::now(),
                'to_acc_name' => $supplierId,
                'invoice_no' => $purchaseNumber,
                'amount' => $netTotalAmount,
                'balance_type' => 'Dr',
                'type' => 'PV',
                // 'givenAmount' => $givenAmount,
                'acid' => '13',
                'transaction_by' => $done_by,
                'narration' => $pro_all_data_string,
                'transaction_date' => $ledgerDate,
                'done_by' => $done_by,
            ]);

            $financeTransaction2 = FinanceTransaction::create([
                'company_code' => '01',
                'voucher_no' => $crVoucherNo,
                'voucher_date' => Carbon::now(),
                'to_acc_name' => 'Purchase Account',
                'invoice_no' => $purchaseNumber,
                'type' => 'PV',
                'amount' => $netTotalAmount,
                'balance_type' => 'Cr',
                // 'givenAmount' => $givenAmount,
                'acid' => $finAccId->id,
                'transaction_by' => $done_by,
                'narration' => $pro_all_data_string,
                'transaction_date' => $ledgerDate,
                'done_by' => $done_by,
            ]);


            if ($givenAmount > 0) {

                $financeTransaction3 = FinanceTransaction::create([
                    'company_code' => '01',
                    'voucher_no' => $crVoucherNo,
                    'voucher_date' => Carbon::now(),
                    'to_acc_name' => $payAccName->account_name,
                    'invoice_no' => $purchaseNumber,
                    'amount' => $givenAmount,
                    'balance_type' => 'Dr',
                    'type' => 'PV',
                    // 'givenAmount' => $givenAmount,
                    'acid' => $finAccId->id,
                    'transaction_by' => $done_by,
                    'narration' => $pro_all_data_string,
                    'transaction_date' => $ledgerDate,
                    'done_by' => $done_by,
                ]);

                $financeTransaction4 = FinanceTransaction::create([
                    'company_code' => '01',
                    'voucher_no' => $crVoucherNo,
                    'voucher_date' => Carbon::now(),
                    'to_acc_name' => $supplierId,
                    'invoice_no' => $purchaseNumber,
                    'type' => 'PV',
                    'amount' => $givenAmount,
                    'balance_type' => 'Cr',
                    // 'givenAmount' => $givenAmount,
                    'acid' => $payAccount,
                    'transaction_by' => $done_by,
                    'narration' => $pro_all_data_string,
                    'transaction_date' => $ledgerDate,
                    'done_by' => $done_by,
                ]);

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
    }
    // Purchase Store -------end------

    // purchaseInvoice List Report details pdf -------start------
    public function purchaseInvoiceReport()
    {
        return view('pages.product.stock.purchase_report_invoice_wise');
    }
    
    public function purchaseInvoiceDateSearch(Request $request)
    {
        $inv = 'PUR';
        $query = Stock::where('invoice_no', 'LIKE', '%' . $inv . '%');
        if ($request->startDate != 0 && $request->endDate != 0) {
            $query->whereBetween('stock_date', [$request->startDate, $request->endDate]);
        }
        $purchaseInvoices = $query->orderBy('id', 'DESC')->get()->groupBy('invoice_no');
        return response()->json($purchaseInvoices);
    }

    public function purchaseInvoiceDetails($invoiceNo)
    {        
        $stocks = Stock::with(['product', 'supplier_finance_account'])->where('invoice_no', $invoiceNo)->get();
        $supplier_ids = $stocks->pluck('supplier_id')->unique();
        $acid = $supplier_ids->first();
        $supplierPayment = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $acid)->where('balance_type', 'Dr')->pluck('amount')->first();

        return view('pages.product.stock.purchase_report_invoice_details', compact('stocks', 'supplierPayment',));
    }

    public function purchaseInvoiceDetailsPdf($invoices)
    {
        $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        $data['company_name'] = $companySetting->company_name;
        $data['company_address'] = $companySetting->company_address;
        $data['company_logo_one'] = $companySetting->company_logo_one;
        
        $stocks = Stock::with(['product', 'supplier_finance_account'])->where('invoice_no', $invoices)->get();
        $supplier_ids = $stocks->pluck('supplier_id')->unique();
        $acid = $supplier_ids->first();
        $supplierPayment = FinanceTransaction::where('invoice_no', $invoices)->where('acid', $acid)->where('balance_type', 'Dr')->pluck('amount')->first();
        
        $pdf = PDF::loadView('pages.pdf.purchase_report_invoice_wise_pdf', array('stocks' => $stocks, 'invoices' => $invoices, 'supplierPayment' => $supplierPayment, 'data' => $data));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream(Carbon::now() . '-purchase_report.pdf');
    }
    // purchaseInvoice List Report details pdf -------end------

    // Supplier Wise List Report details pdf -------start------
    public function purchaseReportSupplierWise(Request $request)
    {
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');
        $acNames = FinanceAccount::where('account_status', 1)->where('account_group_code', '400010001')->orderBy('id', 'desc')->pluck('account_name','id')->all();
        return view('pages.product.stock.purchase_report_supplier_wise', compact('acNames', 'startDate', 'endDate'));
    }
    // supplier wise sales report
    public function purchaseReportSupplierWiseSearch($startDate, $endDate, $acNameID, $pdf)
    {
        $query = "
            SELECT 
            ft.invoice_no as invoice_no,
            ft.voucher_no as voucher_no,
            ft.voucher_date as voucher_date,
            ft.transaction_date as transaction_date,
            fa.account_name as account_name,
            fa.account_mobile as account_mobile,
            fa.account_address as account_address,
            ft.narration as narration,
            ft.type as type,
            ft.balance_type as balance_type,
            ft.amount as amount
            FROM `finance_transactions` as ft 
            inner join finance_accounts as fa on ft.acid=fa.id and fa.account_group_code='400010001'
            WHERE ft.type='PV' AND ft.transaction_date BETWEEN '$startDate' and '$endDate' and ( fa.id= $acNameID or $acNameID=0)
            ORDER BY ft.transaction_date
        ";
        $dateWiseSalesSearch = DB::table(DB::raw("($query) AS subquery"))
                            ->select('invoice_no', 'voucher_no', 'voucher_date', 'transaction_date', 'account_name', 'account_mobile', 'account_address', 'narration', 'type', 'balance_type', 'amount')
                            ->get();
        if ($pdf == "list") {
            return response()->json($dateWiseSalesSearch);
        }
        if ($pdf == "pdfurl") {
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;
            $data['company_address'] = $companySetting->company_address;
            $data['company_logo_one'] = $companySetting->company_logo_one;
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $pdf = PDF::loadView('pages.pdf.purchase_report_supplier_wise_pdf', array('dateWiseSalesSearch' => $dateWiseSalesSearch, 'data'=>$data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream(Carbon::now().'-recentstat.pdf');
        }

    }
    // Supplier Wise List Report details pdf -------end------


}
