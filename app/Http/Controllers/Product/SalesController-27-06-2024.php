<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\CustomerType;
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
use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;

use NumberFormatter;
use Rmunate\Utilities\SpellNumber;
use Illuminate\Support\Facades\Storage;

class SalesController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:read sales|write sales|create sales', ['only' => ['index', 'show']]);
        $this->middleware('permission:create sales', ['only' => ['create', 'store']]);
        $this->middleware('permission:write sales', ['only' => ['edit', 'update', 'destroy']]);
        $this->middleware('permission:read sales report', ['only' => ['sales_report', 'salesDateSearch', 'salesSearch']]);
    }

    public function index()
    {
        $products = Product::where('is_saleable', 1)->get();
        $units = ProductUnit::get();
        $customers = Customer::where('status', 1)->get();
        $customerAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', '100020001')->get();
        $toAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', 'like', '10002%')->whereNotIn('account_group_code', ['100020001'])->get();
        $customerTypes = CustomerType::where('status', 1)->pluck('type_name','id')->all();
        return view('pages.product.stock._stock-out', compact('products', 'units', 'customers', 'customerAccounts', 'toAccounts', 'customerTypes'));
    }

    public function customerDetails($id)
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
            ->where('fa.id', $id)
            ->select('fa.*', 'balances.total_debits', 'balances.total_credits', 'balances.net_balance')
            ->first();
        if ($summary) {
            return response()->json($summary);
        } else {
            return response()->json(['error' => 'Not Found'], 404);
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'givenAmount' => 'required',
            'table_product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $customerId = $request->input('customer_id');
        $finAccId = FinanceAccount::where('account_status', 1)->where('id', $customerId)->first();

        $netTotalAmount = $request->input('netTotalAmount');
        // $customerInvoiceNo = $request->input('customer_invoice_no');
        $givenAmount = $request->input('givenAmount');
        $customerDues = $request->input('table_customer_due');
        $remarks = $request->input('remarks');
        $ledgerDate = Carbon::now();
        $done_by = Auth::user()->name;

        $payAccount = $request->input('receive_account');
        $payAccName = FinanceAccount::where('account_status', 1)->where('id', $payAccount)->first();

        if ($customerDues < 0) {
            $isPreviousDue = 1;
        } else {
            $isPreviousDue = 0;
        }

        // get voucher_no
        $voucher = DB::table('invoiceno')->first('voucher_no');
        $getCrVoucherNo = $voucher->voucher_no;
        $voucherNo = '01SV' . str_pad($getCrVoucherNo, 6, '0', STR_PAD_LEFT);
        DB::table('invoiceno')->update([
            'voucher_no' => $getCrVoucherNo + 1,
        ]);

        // get sales_no
        $salesNo = DB::table('invoiceno')->first('sales_no');
        $getSalesNo = $salesNo->sales_no;
        $salesNumber = 'INV' . str_pad($getSalesNo, 6, '0', STR_PAD_LEFT);
        DB::table('invoiceno')->update([
            'sales_no' => $getSalesNo + 1,
        ]);


        // $customerData = Customer::where('status', 1)->where('customer_name', $finAccId->account_name)->first();
        // // dd($customerData);
        // $customerIdLedger = $customerData->id;

        // // dd($customerIdLedger);

        // $CustomerLedgerIn2 = CustomerLedger::create([
        //     'customer_id' => $customerIdLedger,
        //     // 'customer_invoice_no'           => $customerInvoiceNo,
        //     'ledger_date' => $ledgerDate,
        //     'invoice_no' => $salesNumber,
        //     'debit' => $netTotalAmount,
        //     'credit' => 0,
        //     'remarks' => $remarks,
        //     'is_previous_due' => $isPreviousDue,
        //     // $service->servicevlans()->save($serviceVlan);
        //     'done_by' => $done_by,
        // ]);

        // $CustomerLedgerIn = CustomerLedger::create([
        //     'customer_id' => $customerIdLedger,
        //     // 'customer_invoice_no'           => $customerInvoiceNo,
        //     'ledger_date' => $ledgerDate,
        //     'invoice_no' => $salesNumber,
        //     'debit' => 0,
        //     'credit' => $givenAmount,
        //     'remarks' => $remarks,
        //     'is_previous_due' => $isPreviousDue,
        //     // $service->servicevlans()->save($serviceVlan);
        //     'done_by' => $done_by,
        // ]);

        foreach ($request->get('table_product_id') as $key => $productId) {
            $stockOut = new Stock();

            $stockQuantity = $request->input('table_product_quantity')[$key];
            $stockPrice = $request->input('table_product_price')[$key];
            $stockDiscount = $request->input('table_product_discount')[$key];
            $stockTotal = ($stockQuantity * $stockPrice) - $stockDiscount;

            $product = Product::find($productId);
            $productName = $product ? $product->product_name : 'Unknown Product';

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
            $pro_all_data[] = "Invoice No: $salesNumber, Name: $productName, Qty: $stockQuantity, Unit Price: $stockPrice, Discount: $stockDiscount, Total: $stockTotal\n";
        }

        $pro_all_data_string = implode("\n", $pro_all_data);
        echo $pro_all_data_string;

        $financeTransaction = FinanceTransaction::create([
            'company_code' => '01',
            'voucher_no' => $voucherNo,
            'voucher_date' => Carbon::now(),
            'to_acc_name' => 'Sales Account',
            // 'to_acc_name' => $finAccId->account_name,
            'invoice_no' => $salesNumber,
            'amount' => $netTotalAmount,
            'balance_type' => 'Dr',
            'type' => 'SV',
            // 'givenAmount' => $givenAmount,
            'acid' => $customerId,
            'transaction_by' => $done_by,
            'narration' => $pro_all_data_string,
            'transaction_date' => $ledgerDate,
            'done_by' => $done_by,
        ]);

        $financeTransaction2 = FinanceTransaction::create([
            'company_code' => '01',
            'voucher_no' => $voucherNo,
            'voucher_date' => Carbon::now(),
            'to_acc_name' => $finAccId->account_name,
            'invoice_no' => $salesNumber,
            'type' => 'SV',
            'amount' => $netTotalAmount,
            'balance_type' => 'Cr',
            // 'givenAmount' => $givenAmount,
            'acid' => '14',
            'transaction_by' => $done_by,
            'narration' => $pro_all_data_string,
            'transaction_date' => $ledgerDate,
            'done_by' => $done_by,
        ]);

        if ($givenAmount > 0) {

            $financeTransaction3 = FinanceTransaction::create([
                'company_code' => '01',
                'voucher_no' => $voucherNo,
                'voucher_date' => Carbon::now(),
                'to_acc_name' => $payAccName->account_name,
                'invoice_no' => $salesNumber,
                'amount' => $givenAmount,
                'balance_type' => 'Cr',
                'type' => 'SV',
                // 'givenAmount' => $givenAmount,
                'acid' => $finAccId->id,
                'transaction_by' => $done_by,
                'narration' => $pro_all_data_string,
                'transaction_date' => $ledgerDate,
                'done_by' => $done_by,
            ]);

            $financeTransaction4 = FinanceTransaction::create([
                'company_code' => '01',
                'voucher_no' => $voucherNo,
                'voucher_date' => Carbon::now(),
                'to_acc_name' => $finAccId->account_name,
                'invoice_no' => $salesNumber,
                'type' => 'SV',
                'amount' => $givenAmount,
                'balance_type' => 'Dr',
                // 'givenAmount' => $givenAmount,
                'acid' => $payAccount,
                'transaction_by' => $done_by,
                'narration' => $pro_all_data_string,
                'transaction_date' => $ledgerDate,
                'done_by' => $done_by,
            ]);
        }
        return back()->with([
            'message' => 'Sales History Added Successfully!',
            'alert-type' => 'success'
        ]);
    }

    public function salesInvoiceReport(Request $request)
    {

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

    public function salesDateSearch(Request $request)
    {

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

    public function salesSearch(Request $request)
    {
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

        // $stocks = Stock::with(['product', 'customer'])->where('invoice_no', $invoice_list)->get();

        $stocks = Stock::with(['product', 'customer_finance_account'])->where('invoice_no', $invoice_list)->get();
        $customer_ids = $stocks->pluck('customer_id')->unique();
        $acid = $customer_ids->first();

        // dd($acid);

        // $customerPayment = CustomerLedger::where('invoice_no', $invoice_list)->sum('credit');

        $customerPayment = FinanceTransaction::where('invoice_no', $invoice_list)->where('acid', $acid)->where('balance_type', 'Cr')->pluck('amount')->first();

        return view('pages.product.stock.sales_report', compact('stocks', 'invoices', 'customerPayment', 'startDate', 'endDate'));
    }

    public function salesSearchPdf($invoices)
    {

        $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        $data['company_name'] = $companySetting->company_name;
        $data['company_logo_one'] = $companySetting->company_logo_one;

        // $stocks = Stock::with(['product', 'customer'])->where('invoice_no', $invoices)->get();

        $stocks = Stock::with(['product', 'customer_finance_account'])->where('invoice_no', $invoices)->get();
        $customer_ids = $stocks->pluck('customer_id')->unique();
        $acid = $customer_ids->first();

        // $customerPayment = CustomerLedger::where('invoice_no', $invoices)->sum('credit');

        $customerPayment = FinanceTransaction::where('invoice_no', $invoices)->where('acid', $acid)->where('balance_type', 'Cr')->pluck('amount')->first();

        $pdf = PDF::loadView('pages.pdf.sales_report_pdf', array('stocks' => $stocks, 'invoices' => $invoices, 'customerPayment' => $customerPayment, 'data' => $data));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream(Carbon::now() . '-sales_report.pdf');
    }


    // new date wise search 
    public function salesReportDateWise(Request $request)
    {
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');
        $acNames = FinanceAccount::where('account_status', 1)->where('account_group_code', '100020001')->orderBy('id', 'desc')->pluck('account_name','id')->all();
        return view('pages.product.stock.sales_report_datewise', compact('acNames', 'startDate', 'endDate'));
    }

    // date wise sales report
    public function salesReportDateWiseSearch($startDate, $endDate, $acNameID, $pdf)
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
            inner join finance_accounts as fa on ft.acid=fa.id and fa.account_group_code='100020001'
            WHERE ft.type='SV' AND ft.transaction_date BETWEEN '$startDate' and '$endDate' and ( fa.id= $acNameID or $acNameID=0)
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
            $pdf = PDF::loadView('pages.pdf.sales_report_datewise_pdf', array('dateWiseSalesSearch' => $dateWiseSalesSearch, 'data'=>$data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream(Carbon::now().'-recentstat.pdf');
        }

    }

    // public function ___salesReportDateWiseSearch($startDate, $endDate, $acName)
    // {

    //     $startDate = Carbon::now()->format('d-m-Y');
    //     $endDate = Carbon::now()->format('d-m-Y');

    //     $startDateTimeObj = Carbon::parse($startDate);
    //     $endDateTimeObj = Carbon::parse($endDate);

    //     $startDateObj = $startDateTimeObj->startOfDay();
    //     $endDateObj = $endDateTimeObj->endOfDay();

    //     $transactions = DB::table('finance_transactions as ft')
    //         ->join('finance_accounts as fa', function ($join) {
    //             $join->on('ft.acid', '=', 'fa.id')
    //                 ->where('fa.account_group_code', '=', '100020001');
    //         })
    //         ->whereBetween('ft.transaction_date', [$startDateObj, $endDateObj])
    //         ->where(function ($query) {
    //             $query->where('fa.id', '=', $acName)
    //                 ->orWhere('', '=', 0);
    //         })
    //         ->select('ft.*', 'fa.*')
    //         ->get();

    //     // dd($transactions);

    //     return view('pages.product.stock.sales_report_datewise', compact('transactions', 'invoices', 'stocks', 'startDate', 'endDate'));

    // }
    

}
