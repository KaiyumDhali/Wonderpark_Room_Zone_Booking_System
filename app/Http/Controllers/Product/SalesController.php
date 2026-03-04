<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\CustomerType;
use App\Models\CompanySetting;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Employee;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Validator;
use Carbon\Carbon;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProductService;
use App\Models\ProductServiceDetail;

use Illuminate\Support\Facades\View;
use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;

use NumberFormatter;
use Rmunate\Utilities\SpellNumber;
use Illuminate\Support\Facades\Storage;

use Illuminate\Pagination\LengthAwarePaginator;

class SalesController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:read sales|write sales|create sales', ['only' => ['index', 'show']]);
        $this->middleware('permission:create sales', ['only' => ['create', 'store']]);
        $this->middleware('permission:write sales', ['only' => ['edit', 'update', 'destroy']]);
        $this->middleware('permission:read sales report', ['only' => ['sales_report', 'salesDateSearch', 'salesSearch']]);
        $this->middleware('permission:read item wise profit', ['only' => ['salesProfitItemWise']]);
        $this->middleware('permission:read invoice wise profit', ['only' => ['salesProfitInvoiceWise']]);
    }

    // purchaseInvoiceReturn
    public function salesInvoiceReturn($invoiceNo)
    {
        $stocks = Stock::with(['product', 'unit'])->where('invoice_no', $invoiceNo)->get();
        $customerAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', '100020001')->get();
        return view('pages.product.stock.sales_invoice_return', compact('invoiceNo', 'stocks', 'customerAccounts'));
    }

    public function salesInvoiceReturnStore(Request $request, $invoiceNo)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'table_product_id' => 'required',
            'voucher_date' => 'required',
            'customer_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $salesDate = $request->input('voucher_date');
        $netTotalAmount = $request->input('netTotalAmount');
        $returnNetTotalAmount = $request->input('returnNetTotalAmount');
        $remarks = $request->input('remarks');
        $done_by = Auth::user()->name;
        // customer id and name
        $customerId = $request->input('customer_id');
        $customerName = FinanceAccount::where('account_status', 1)->where('id', $customerId)->value('account_name');
        // get invoice no
        $getSalesNumber = $invoiceNo;
        $salesNumber = str_replace('INV', 'RTS', $getSalesNumber);

        DB::beginTransaction();
        try {
            // get voucher_no
            $voucher = DB::table('invoiceno')->first('voucher_no');
            $getCrVoucherNo = $voucher->voucher_no;
            $voucherNo = '01SV' . str_pad($getCrVoucherNo, 6, '0', STR_PAD_LEFT);
            DB::table('invoiceno')->update([
                'voucher_no' => $getCrVoucherNo + 1,
            ]);

            $salesProductData = [];
            foreach ($request->get('table_product_id') as $key => $productId) {
                $stockOut = new Stock();

                $stockQuantity = $request->input('table_product_quantity')[$key];
                $stockReturnQuantity = $request->input('table_product_return_quantity')[$key];
                $stockPrice = $request->input('table_product_price')[$key];
                $stockDiscount = $request->input('table_product_discount')[$key];
                $stockTotal = $request->input('table_product_cart_amount')[$key];
                $stockReturnTotal = $request->input('table_product_return_cart_amount')[$key];

                $product = Product::find($productId);
                $productName = $product ? $product->product_name : 'Unknown Product';

                if ($stockReturnQuantity > 0) {
                    // $stockOut->warehouse_id = $request->input('warehouse_id');
                    // $stockOut->delivery_challan_no = $deliveryChallanNumber;
                    $stockOut->stock_date = $salesDate;
                    $stockOut->stock_type = 'Out';
                    $stockOut->invoice_no = $salesNumber; // invoice no
                    $stockOut->product_id = $productId;
                    $stockOut->customer_id = $customerId;
                    $stockOut->stock_out_quantity = 0;
                    $stockOut->stock_in_quantity = $stockReturnQuantity;
                    $stockOut->stock_in_unit_price = $stockPrice;
                    $stockOut->stock_in_discount = $stockDiscount;
                    $stockOut->stock_in_total_amount = $stockReturnTotal;
                    $stockOut->done_by = $done_by;
                    $stockOut->status = 1;
                    $stockOut->remarks = $remarks;
                    $stockOut->save();
                    $salesProductData[] = "Name:$productName, Qty:$stockReturnQuantity X $stockPrice, Total:$stockReturnTotal\n";
                }
            }
            $salesProductData_string = implode("\n", $salesProductData);
            echo $salesProductData_string;
            $salesNarration = 'Invoice No:' . $salesNumber . ', ' . $salesProductData_string;

            $financeTransaction = FinanceTransaction::create([
                'company_code' => '01',
                //'delivery_challan_no' => $deliveryChallanNumber,
                'invoice_no' => $salesNumber,
                'voucher_no' => $voucherNo, // $crVoucherNo
                'voucher_date' => $salesDate,
                'acid' => $customerId,
                'to_acc_name' => $GLOBALS['SalesAccountName'],
                'type' => 'SR',
                'amount' => $returnNetTotalAmount,
                'balance_type' => 'Cr',
                'narration' => $salesNarration,
                'transaction_date' => $salesDate,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
            ]);

            $financeTransaction2 = FinanceTransaction::create([
                'company_code' => '01',
                //'delivery_challan_no' => $deliveryChallanNumber,
                'invoice_no' => $salesNumber,
                'voucher_no' => $voucherNo,
                'voucher_date' => $salesDate,
                'acid' => $GLOBALS['SalesAccountID'],
                'to_acc_name' => $customerName,
                'type' => 'SR',
                'amount' => $returnNetTotalAmount,
                'balance_type' => 'Dr',
                'narration' => $salesNarration,
                'transaction_date' => $salesDate,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Ops ! Could Not add sales return history. DB transaction lost']);
        }
        return back()->with([
            'message' => 'Sales Return History Added Successfully!',
            'alert-type' => 'success',
            'invoice' => $salesNumber
        ]);
    }

    public function salesInvoiceReturnList()
    {
        return view('pages.product.stock.sales_return_invoice_wise');
    }

    public function salesInvoiceReturnListSearch($startDate, $endDate, $pdf)
    {
        $inv = 'RTS';
        $query = Stock::selectRaw('stocks.invoice_no, stocks.customer_id, fa.account_name, MIN(stocks.stock_date) as stock_date, SUM(stocks.stock_out_discount) as total_discount, SUM(stocks.stock_out_total_amount) as total_amount')
            ->join('finance_accounts as fa', function ($join) {
                $join->on('stocks.customer_id', '=', 'fa.id')
                    ->where('fa.account_group_code', '=', '100020001'); // Account Payable code 100020001 use customer
            })
            ->where('stocks.invoice_no', 'LIKE', '%' . $inv . '%');

        if ($startDate && $endDate) {
            // Assuming $startDate and $endDate are in the format 'YYYY-MM-DD'
            $query->whereRaw('DATE(stocks.stock_date) BETWEEN ? AND ?', [$startDate, $endDate]);
        }

        if ($pdf == "list") {
            $salesInvoices = $query->groupBy('stocks.invoice_no', 'stocks.customer_id', 'fa.account_name')
                ->orderBy('stocks.stock_date', 'DESC')
                ->get();
            return response()->json($salesInvoices);
        }
        if ($pdf == "pdfurl") {
            $salesInvoices = $query->groupBy('stocks.invoice_no', 'stocks.customer_id', 'fa.account_name')
                ->orderBy('stocks.stock_date', 'ASC')
                ->get();
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;
            $data['company_address'] = $companySetting->company_address;
            $data['company_logo_one'] = $companySetting->company_logo_one;
            $data['company_mobile'] = $companySetting->company_mobile;

            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $pdf = PDF::loadView('pages.pdf.sales_date_invoice_wise_return_report_pdf', array('dateWisePurchaseSearch' => $salesInvoices, 'data' => $data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream(Carbon::now() . '-purchase_date_invoice_wise_report_pdf.pdf');
        }
    }

    public function salesReturnInvoiceDetails($invoiceNo)
    {
        $stocks = Stock::with(['product', 'customer_finance_account'])->where('invoice_no', $invoiceNo)->get();
        $customer_ids = $stocks->pluck('customer_id')->unique();
        $acid = $customer_ids->first();
        // $supplierPayment = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $acid)->where('balance_type', 'Dr')->whereNull('payment_type')->pluck('amount')->first();
        // $paymentNarration = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $acid)->where('balance_type', 'Dr')->pluck('narration')->first();
        return view('pages.product.stock.sales_return_invoice_details', compact('stocks'));
    }

    public function purchaseReturnInvoiceDetailsPdf($invoices)
    {
        $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        $data['company_name'] = $companySetting->company_name;
        $data['company_address'] = $companySetting->company_address;
        $data['company_logo_one'] = $companySetting->company_logo_one;
        $data['company_mobile'] = $companySetting->company_mobile;

        $stocks = Stock::with(['product', 'customer_finance_account'])->where('invoice_no', $invoices)->get();
        $customer_ids = $stocks->pluck('customer_id')->unique();
        $acid = $customer_ids->first();
        // $supplierPayment = FinanceTransaction::where('invoice_no', $invoices)->where('acid', $acid)->where('balance_type', 'Dr')->pluck('amount')->first();
        // $paymentNarration = FinanceTransaction::where('invoice_no', $invoices)->where('acid', $acid)->where('balance_type', 'Dr')->pluck('narration')->first();

        $pdf = PDF::loadView('pages.pdf.sales_return_invoice_wise_pdf', array('stocks' => $stocks, 'invoices' => $invoices, 'data' => $data));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream(Carbon::now() . '-sales_return_invoice.pdf');
    }



    public function index()
    {
        $warehouses = Warehouse::where('status', 1)->get();
        $products = Product::where('is_saleable', 1)->where('status', 1)->get();
        $units = ProductUnit::get();
        $customerAccounts = Employee::where('status', 1)->get();
        // $customerAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', '100020001')->get();
        $toAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', '100020002')->get();
        $customerTypes = CustomerType::where('status', 1)->pluck('type_name', 'id')->all();
        return view('pages.product.stock._stock-out', compact('products', 'warehouses', 'units', 'customerAccounts', 'toAccounts', 'customerTypes'));
    }

    // customerDetails ----------------------------------------------
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

       // Sales Store ----------------------------------------------
    public function store(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'voucher_date' => 'required',
            'customer_id' => 'required',
            'givenAmount' => 'required',
            'table_product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $salesDate = $request->input('voucher_date');
        $netTotalAmount = 0;
        $givenAmount = $request->input('givenAmount');
        $remarks = $request->input('remarks');
        $done_by = Auth::user()->name;

        $customerId = $request->input('customer_id');
        $customerName = FinanceAccount::where('account_status', 1)->where('id', $customerId)->value('account_name');
        $payAccountId = $request->input('receive_account');
        $payAccName = FinanceAccount::where('account_status', 1)->where('id', $payAccountId)->value('account_name');

        $payAccGroupName = FinanceAccount::leftJoin('finance_groups', 'finance_accounts.account_group_code', '=', 'finance_groups.group_code')
            ->where('finance_accounts.account_status', 1)
            ->where('finance_accounts.id', $payAccountId)
            ->value('finance_groups.group_name');
        // dd($payAccGroupName);
        //$payment_type = $request->input('payment_type');
        $payment_type = $payAccGroupName;

        //payment info
        $bank_name = $request->input('bank_name');
        $branch_name = $request->input('branch_name');
        $ac_no = $request->input('ac_no');
        $cheque_type = $request->input('cheque_type');
        $cheque_no = $request->input('cheque_no');
        $cheque_date = $request->input('cheque_date');
        $mobile_bank_name = $request->input('mobile_bank_name');
        $mobile_number = $request->input('mobile_number');
        $transaction_id = $request->input('transaction_id');

        DB::beginTransaction();
        try {
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

            // get delivery_challan_no
            $deliveryChallanNo = DB::table('invoiceno')->first('delivery_challan_no');
            $getDeliveryChallanNo = $deliveryChallanNo->delivery_challan_no;
            $deliveryChallanNumber = 'CHA' . str_pad($getDeliveryChallanNo, 6, '0', STR_PAD_LEFT);
            DB::table('invoiceno')->update([
                'delivery_challan_no' => $getDeliveryChallanNo + 1,
            ]);

            $salesProductData = [];
            foreach ($request->get('table_product_id') as $key => $productId) {
                $stockOut = new Stock();
                $stockQuantity = $request->input('table_product_quantity')[$key];
                $stockPrice = 0;
                $stockDiscount = 0;
                $stockTotal = 0;
                $warehouse_id = $request->input('table_warehouse_id')[$key];
                $product = Product::find($productId);
                $productName = $product ? $product->product_name : 'Unknown Product';

                // Only update the sales_price for the specific product if it's different

                // if ($product && $stockPrice != $product->sales_price) {
                //     DB::table('products')
                //         ->where('id', $product->id) // Target the specific product by ID
                //         ->update(['sales_price' => $stockPrice]);
                // }

                // $stockOut->warehouse_id = $request->input('warehouse_id');
                $stockOut->warehouse_id = $warehouse_id;
                $stockOut->stock_date = $salesDate;
                $stockOut->stock_type = 'Out';
                $stockOut->delivery_challan_no = $deliveryChallanNumber;
                $stockOut->invoice_no = $salesNumber;
                $stockOut->customer_id = $customerId;
                $stockOut->product_id = $request->input('table_product_id')[$key];
                $stockOut->purchase_price = 0;
                $stockOut->stock_in_quantity = 0;
                $stockOut->stock_out_quantity = $stockQuantity;
                $stockOut->stock_out_unit_price = $stockPrice;
                $stockOut->stock_out_discount = $stockDiscount;
                $stockOut->stock_out_total_amount = $stockTotal;
                $stockOut->status = 1;
                $stockOut->remarks = $remarks;
                $stockOut->done_by = $done_by;
                $stockOut->save();
                $salesProductData[] = "Name:$productName, Qty:$stockQuantity X $stockPrice, Total:$stockTotal\n";
            }
            $salesProductData_string = implode("\n", $salesProductData);
            echo $salesProductData_string;
            $salesNarration = 'Invoice:' . $salesNumber . ', ' . $salesProductData_string;
            $financeTransaction = FinanceTransaction::create([
                'company_code' => '01',
                'delivery_challan_no' => $deliveryChallanNumber,
                'invoice_no' => $salesNumber,
                'voucher_no' => $voucherNo,
                'voucher_date' => $salesDate,
                'acid' => $customerId,
                'to_acc_name' => $GLOBALS['SalesAccountName'],
                'type' => 'SV',
                'amount' => $netTotalAmount,
                'balance_type' => 'Dr',
                'narration' => $salesNarration,
                'transaction_date' => $salesDate,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
            ]);

            $financeTransaction2 = FinanceTransaction::create([
                'company_code' => '01',
                'delivery_challan_no' => $deliveryChallanNumber,
                'invoice_no' => $salesNumber,
                'voucher_no' => $voucherNo,
                'voucher_date' => $salesDate,
                'acid' => $GLOBALS['SalesAccountID'],
                'to_acc_name' => $customerName,
                'type' => 'SV',
                'amount' => $netTotalAmount,
                'balance_type' => 'Cr',
                'narration' => $salesNarration,
                'transaction_date' => $salesDate,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
            ]);

            if ($givenAmount > 0) {
                $formatAmount = formatCurrency($givenAmount);

                if ($payAccGroupName == 'Bank Account') {
                    if ($cheque_type == 'Cheque') {
                        $narration[] = "Invoice:$salesNumber, $payAccName Received From: $customerName, Through Bank $cheque_type, Cheque No:$cheque_no, Cheque Date:$cheque_date, Received Amount:$formatAmount TK $remarks\n";
                    } else {
                        $narration[] = "Invoice:$salesNumber, $payAccName Received From: $customerName, Through Bank $cheque_type, Received Amount:$formatAmount TK $remarks\n";
                    }
                } else if ($payAccGroupName == 'Mobile Bank') {
                    $narration[] = "Invoice:$salesNumber, $payAccName Received From: $customerName, Through Mobile Bank $mobile_bank_name, Mobile Number:$mobile_number, Transaction ID:$transaction_id, Received Amount:$formatAmount TK $remarks\n";
                } else {
                    $narration[] = "Invoice:$salesNumber, $payAccName Received From: $customerName, Through Cash Received Amount:$formatAmount TK $remarks\n";
                }

                $narration = implode("\n", $narration);
                echo $narration;

                $financeTransaction3 = FinanceTransaction::create([
                    'company_code' => '01',
                    'delivery_challan_no' => $deliveryChallanNumber,
                    'invoice_no' => $salesNumber,
                    'voucher_no' => $voucherNo,
                    'voucher_date' => $salesDate,
                    'acid' => $customerId,
                    'to_acc_name' => $payAccName,
                    'type' => 'SV',
                    'amount' => $givenAmount,
                    'balance_type' => 'Cr',
                    'payment_type' => $payment_type,
                    'cheque_no' => $cheque_no,
                    'cheque_date' => $cheque_date,
                    'cheque_type' => $cheque_type,
                    'narration' => $narration,
                    'transaction_date' => $salesDate,
                    'transaction_by' => $done_by,
                    'done_by' => $done_by,
                ]);

                $financeTransaction4 = FinanceTransaction::create([
                    'company_code' => '01',
                    'delivery_challan_no' => $deliveryChallanNumber,
                    'invoice_no' => $salesNumber,
                    'voucher_no' => $voucherNo,
                    'voucher_date' => $salesDate,
                    'acid' => $payAccountId,
                    'to_acc_name' => $customerName,
                    'type' => 'SV',
                    'amount' => $givenAmount,
                    'balance_type' => 'Dr',
                    'payment_type' => $payment_type,
                    'cheque_no' => $cheque_no,
                    'cheque_date' => $cheque_date,
                    'cheque_type' => $cheque_type,
                    'narration' => $narration,
                    'transaction_date' => $salesDate,
                    'transaction_by' => $done_by,
                    'done_by' => $done_by,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
    DB::rollback();
    return back()->withErrors(['error' => 'DB Error: ' . $e->getMessage()]);
}
        return back()->with([
            'message' => 'Sales History Added Successfully!',
            'alert-type' => 'success',
            'invoice' => $salesNumber,
            'delivery_challan' => $deliveryChallanNumber,
        ]);
    }

    // Sales Challan Report ----------------------------------------------
    public function salesChallanReport(Request $request)
    {
        return view('pages.product.stock.sales_report_challan_wise');
    }
    public function salesChallanDateSearch($startDate, $endDate, $pdf)
    {
        $query = Stock::selectRaw('stocks.delivery_challan_no, stocks.invoice_no, stocks.customer_id, fa.account_name, fa.account_address, fa.account_mobile, MIN(stocks.stock_date) as stock_date')
            ->join('finance_accounts as fa', function ($join) {
                $join->on('stocks.customer_id', '=', 'fa.id')
                    ->where('fa.account_group_code', '=', '100020001');
            });
        if ($startDate && $endDate) {
            $query->whereRaw('DATE(stocks.stock_date) BETWEEN ? AND ?', [$startDate, $endDate]);
        }
        $salesInvoices = $query->groupBy('stocks.delivery_challan_no', 'stocks.invoice_no', 'stocks.customer_id', 'fa.account_name', 'fa.account_address', 'fa.account_mobile')
            ->orderBy('stocks.id', 'DESC')
            ->get();

        if ($pdf == "list") {
            return response()->json($salesInvoices);
        }
        if ($pdf == "pdfurl") {
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;
            $data['company_address'] = $companySetting->company_address;
            $data['company_logo_one'] = $companySetting->company_logo_one;
            $data['company_mobile'] = $companySetting->company_mobile;

            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $pdf = PDF::loadView('pages.pdf.sales_date_challan_wise_report_pdf', array('dateWiseSalesSearch' => $salesInvoices, 'data' => $data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream(Carbon::now() . '-recentstat.pdf');
        }
    }
    public function salesChallanDetails($challanNo)
    {
        $stocks = Stock::with(['product', 'customer_finance_account'])->where('delivery_challan_no', $challanNo)->get();
        // $customer_ids = $stocks->pluck('customer_id')->unique();
        // $acid = $customer_ids->first();
        return view('pages.product.stock.sales_report_challan_details', compact('stocks'));
    }
    public function salesChallanDetailsPdf($challanNo)
    {
        $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        $data['company_name'] = $companySetting->company_name;
        $data['company_address'] = $companySetting->company_address;
        $data['company_logo_one'] = $companySetting->company_logo_one;
        $data['company_mobile'] = $companySetting->company_mobile;

        $stocks = Stock::with(['product', 'customer_finance_account'])->where('delivery_challan_no', $challanNo)->get();

        $pdf = PDF::loadView('pages.pdf.sales_report_challan_wise_pdf', array('stocks' => $stocks, 'invoices' => $challanNo, 'data' => $data));
        $pdf->setPaper('A4', 'portrait');

        return view('pages.pdf.sales_report_challan_wise_pdf', array('stocks' => $stocks, 'invoices' => $challanNo, 'data' => $data));

        // return $pdf->stream(Carbon::now() . '-sales_report.pdf');

    }

    // Sales Invoice Report ----------------------------------------------
    public function salesInvoiceReport(Request $request)
    {
        return view('pages.product.stock.sales_report_invoice_wise');
    }

    // $startDate, $endDate, $pdf
   public function salesInvoiceDateSearch($startDate, $endDate, $pdf)
{
    $inv = 'INV';

    $query = Stock::selectRaw('
            stocks.invoice_no, 
            stocks.customer_id, 
            employees.employee_name as employee_name, 
            MIN(stocks.stock_date) as stock_date, 
            SUM(stocks.stock_out_discount) as total_discount, 
            SUM(stocks.stock_out_total_amount) as total_amount
        ')
        ->leftJoin('employees', 'stocks.customer_id', '=', 'employees.id')
        ->where('stocks.invoice_no', 'LIKE', '%' . $inv . '%');

    // Date filter
    if ($startDate && $endDate) {
        $query->whereRaw('DATE(stocks.stock_date) BETWEEN ? AND ?', [$startDate, $endDate]);
    }

    // Common groupBy
    $query->groupBy('stocks.invoice_no', 'stocks.customer_id', 'employees.employee_name')
        ->orderBy('stocks.stock_date', 'ASC');

    // JSON response
    if ($pdf == "list") {
        $salesInvoices = $query->get();
        return response()->json($salesInvoices);
    }

    // PDF response
    if ($pdf == "pdfurl") {
        $salesInvoices = $query->get();

        $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();

        $data['company_name'] = $companySetting->company_name ?? '';
        $data['company_address'] = $companySetting->company_address ?? '';
        $data['company_logo_one'] = $companySetting->company_logo_one ?? '';
        $data['company_mobile'] = $companySetting->company_mobile ?? '';
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;

        $pdf = PDF::loadView('pages.pdf.sales_date_invoice_wise_report_pdf', [
            'dateWiseSalesSearch' => $salesInvoices,
            'data' => $data
        ]);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream(Carbon::now() . '-sales_date_invoice_wise_report_pdf.pdf');
    }
}



    public function salesInvoiceEdit($invoiceNo)
    {
        $warehouses = Warehouse::where('status', 1)->get();
        $products = Product::where('is_saleable', 1)->where('status', 1)->get();
        $units = ProductUnit::get();
       $customerAccounts = Employee::where('status', 1)->get();
        $toAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', 'like', '10002%')->whereNotIn('account_group_code', ['100020001'])->get();
        $customerTypes = CustomerType::where('status', 1)->pluck('type_name', 'id')->all();

        $stocks = Stock::with(['product', 'unit', 'customer_finance_account'])->where('invoice_no', $invoiceNo)->get();
        $customerFinance = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $stocks[0]->customer_id)->select('voucher_no', 'delivery_challan_no', 'voucher_date')->first();
        $customerPayment = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $stocks[0]->customer_id)->where('balance_type', 'Cr')->select('amount', 'to_acc_name')->first();

        return view('pages.product.stock.sales_invoice_edit', compact('warehouses', 'invoiceNo', 'products', 'units', 'customerAccounts', 'toAccounts', 'customerTypes', 'stocks', 'customerPayment', 'customerFinance'));
    }
    public function salesInvoiceUpdate(Request $request, $invoiceNo)
    {
        // dd($invoiceNo);
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            // 'voucher_date' => 'required',
            'customer_id' => 'required',
            'givenAmount' => 'required',
            'table_product_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // get sales_no
        $salesNumber = $invoiceNo;
        // get voucher_no
        $voucherNo = $request->input('voucher_no');
        // get delivery_challan_no
        $deliveryChallanNumber = $request->input('delivery_challan_no');

        $salesDate = $request->input('voucher_date');
        $netTotalAmount = $request->input('netTotalAmount');
        $givenAmount = $request->input('givenAmount');
        $remarks = $request->input('remarks');
        $done_by = Auth::user()->name;

        $customerId = $request->input('customer_id');
        $customerName = FinanceAccount::where('account_status', 1)->where('id', $customerId)->value('account_name');
        $payAccountId = $request->input('receive_account');
        $payAccName = FinanceAccount::where('account_status', 1)->where('id', $payAccountId)->value('account_name');
        $payAccGroupName = FinanceAccount::leftJoin('finance_groups', 'finance_accounts.account_group_code', '=', 'finance_groups.group_code')
            ->where('finance_accounts.account_status', 1)
            ->where('finance_accounts.id', $payAccountId)
            ->value('finance_groups.group_name');
        // dd($payAccGroupName);
        $payment_type = $payAccGroupName;
        //payment info
        $bank_name = $request->input('bank_name');
        $branch_name = $request->input('branch_name');
        $ac_no = $request->input('ac_no');
        $cheque_type = $request->input('cheque_type');
        $cheque_no = $request->input('cheque_no');
        $cheque_date = $request->input('cheque_date');
        $mobile_bank_name = $request->input('mobile_bank_name');
        $mobile_number = $request->input('mobile_number');
        $transaction_id = $request->input('transaction_id');

        DB::beginTransaction();
        try {
            // Delete multiple rows from the stocks table where invoice_no matches
            Stock::where('invoice_no', $invoiceNo)->delete();

            // Delete multiple rows from the finance_transactions table where invoice_no matches
            FinanceTransaction::where('invoice_no', $invoiceNo)->delete();

            DB::commit(); // Commit the transaction if all deletions succeed
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction if any error occurs
            // Log the error if needed or handle exceptions (optional)
        }

        try {
            $salesProductData = [];
            foreach ($request->get('table_product_id') as $key => $productId) {
                $stockOut = new Stock();
                $warehouseId = $request->input('warehouse_id')[$key];
                $stockQuantity = $request->input('table_product_quantity')[$key];
                $stockPrice = 0;
                $stockDiscount = 0;
                $stockTotal = 0;
                $product = Product::find($productId);
                $productName = $product ? $product->product_name : 'Unknown Product';

                $stockOut->warehouse_id = $warehouseId;
                $stockOut->stock_date = $salesDate;
                $stockOut->stock_type = 'Out';
                $stockOut->delivery_challan_no = $deliveryChallanNumber;
                $stockOut->invoice_no = $salesNumber;
                $stockOut->product_id = $request->input('table_product_id')[$key];
                $stockOut->customer_id = $customerId;
                $stockOut->stock_in_quantity = 0;
                $stockOut->stock_out_quantity = $stockQuantity;
                $stockOut->stock_out_unit_price = $stockPrice;
                $stockOut->stock_out_discount = $stockDiscount;
                $stockOut->stock_out_total_amount = $stockTotal;
                $stockOut->status = 1;
                $stockOut->remarks = $remarks;
                $stockOut->done_by = $done_by;
                $stockOut->save();
                $salesProductData[] = "Name:$productName, Qty:$stockQuantity X $stockPrice, , Total:$stockTotal\n";
            }
            $salesProductData_string = implode("\n", $salesProductData);
            echo $salesProductData_string;
            $salesNarration = 'Invoice:' . $salesNumber . ', ' . $salesProductData_string;

            $financeTransaction = FinanceTransaction::create([
                'company_code' => '01',
                'delivery_challan_no' => $deliveryChallanNumber,
                'invoice_no' => $salesNumber,
                'voucher_no' => $voucherNo,
                'voucher_date' => $salesDate,
                'acid' => $customerId,
                'to_acc_name' => $GLOBALS['SalesAccountName'],
                'type' => 'SV',
                'amount' => $netTotalAmount,
                'balance_type' => 'Dr',
                'narration' => $salesNarration,
                'transaction_date' => $salesDate,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
            ]);

            $financeTransaction2 = FinanceTransaction::create([
                'company_code' => '01',
                'delivery_challan_no' => $deliveryChallanNumber,
                'invoice_no' => $salesNumber,
                'voucher_no' => $voucherNo,
                'voucher_date' => $salesDate,
                'acid' => $GLOBALS['SalesAccountID'],
                'to_acc_name' => $customerName,
                'type' => 'SV',
                'amount' => $netTotalAmount,
                'balance_type' => 'Cr',
                'narration' => $salesNarration,
                'transaction_date' => $salesDate,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
            ]);

            if ($givenAmount > 0) {
                $formatAmount = formatCurrency($givenAmount);

                // $narration[] = "Invoice:$purchaseNumber, $payAccName Payment To: $supplierName, Payment Amount:$formatAmount TK\n";
                // $payment_type = $payAccGroupName;
                // $narration = implode("\n", $narration);
                // echo $narration;

                if ($payAccGroupName == 'Bank Account') {
                    if ($cheque_type == 'Cheque') {
                        $narration[] = "Invoice:$salesNumber, $payAccName Received From: $customerName, Through Bank $cheque_type, Cheque No:$cheque_no, Cheque Date:$cheque_date, Received Amount:$formatAmount TK $remarks\n";
                    } else {
                        $narration[] = "Invoice:$salesNumber, $payAccName Received From: $customerName, Through Bank $cheque_type, Received Amount:$formatAmount TK $remarks\n";
                    }
                } else if ($payAccGroupName == 'Mobile Bank') {
                    $narration[] = "Invoice:$salesNumber, $payAccName Received From: $customerName, Through Mobile Bank $mobile_bank_name, Mobile Number:$mobile_number, Transaction ID:$transaction_id, Received Amount:$formatAmount TK $remarks\n";
                } else {
                    $narration[] = "Invoice:$salesNumber, $payAccName Received From: $customerName, Through Cash Received Amount:$formatAmount TK $remarks\n";
                }

                $narration = implode("\n", $narration);
                echo $narration;

                $financeTransaction3 = FinanceTransaction::create([
                    'company_code' => '01',
                    'delivery_challan_no' => $deliveryChallanNumber,
                    'invoice_no' => $salesNumber,
                    'voucher_no' => $voucherNo,
                    'voucher_date' => $salesDate,
                    'acid' => $customerId,
                    'to_acc_name' => $payAccName,
                    'type' => 'SV',
                    'amount' => $givenAmount,
                    'balance_type' => 'Cr',
                    'payment_type' => $payment_type,
                    'cheque_no' => $cheque_no,
                    'cheque_date' => $cheque_date,
                    'cheque_type' => $cheque_type,
                    'narration' => $narration,
                    'transaction_date' => $salesDate,
                    'transaction_by' => $done_by,
                    'done_by' => $done_by,
                ]);

                $financeTransaction4 = FinanceTransaction::create([
                    'company_code' => '01',
                    'delivery_challan_no' => $deliveryChallanNumber,
                    'invoice_no' => $salesNumber,
                    'voucher_no' => $voucherNo,
                    'voucher_date' => $salesDate,
                    'acid' => $payAccountId,
                    'to_acc_name' => $customerName,
                    'type' => 'SV',
                    'amount' => $givenAmount,
                    'balance_type' => 'Dr',
                    'payment_type' => $payment_type,
                    'cheque_no' => $cheque_no,
                    'cheque_date' => $cheque_date,
                    'cheque_type' => $cheque_type,
                    'narration' => $narration,
                    'transaction_date' => $salesDate,
                    'transaction_by' => $done_by,
                    'done_by' => $done_by,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Ops ! Could Not add Sales history. DB transaction lost']);
        }
        return back()->with([
            'message' => 'Sales History Added Successfully!',
            'alert-type' => 'success',
            'invoice' => $salesNumber,
            'delivery_challan' => $deliveryChallanNumber,
        ]);
    }
    public function salesInvoiceDetails($invoiceNo)
    {

        $stocks = Stock::with(['product', 'customer_finance_account','employee_finance_account'])->where('invoice_no', $invoiceNo)->get();
        $customerAccounts = Employee::where('status', 1)->get();
        // $customer_ids = $stocks->pluck('customer_id')->unique();
        // $acid = $customer_ids->first();
        $product_service_detail_id = $stocks[0]->product_service_detail_id;
        $productServiceDetail = ProductServiceDetail::select('product_service_details.id as product_service_detail_id', 'product_service_details.service_number', 'product_services.id as product_services_id', 'product_services.product_id', 'product_services.service_location', 'products.product_name')
            ->join('product_services', 'product_service_details.product_service_id', '=', 'product_services.id')
            ->join('products', 'product_services.product_id', '=', 'products.id')
            ->where('product_service_details.id', $product_service_detail_id)->first();

        // dd($productServiceDetail);
        $acid = $stocks[0]->customer_id;
        $customerPayment = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $acid)->where('balance_type', 'Cr')->pluck('amount')->first();
        $paymentNarration = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $acid)->where('balance_type', 'Cr')->pluck('narration')->first();

        return view('pages.product.stock.sales_report_invoice_details', compact('customerAccounts','stocks', 'customerPayment', 'paymentNarration', 'productServiceDetail'));
    }
    public function salesInvoiceDetailsPdf($invoices)
    {
        $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        $data['company_name'] = $companySetting->company_name;
        $data['company_address'] = $companySetting->company_address;
        $data['company_logo_one'] = $companySetting->company_logo_one;
        $data['company_mobile'] = $companySetting->company_mobile;

        $stocks = Stock::with(['product', 'customer_finance_account','employee_finance_account'])->where('invoice_no', $invoices)->get();
        $product_service_detail_id = $stocks[0]->product_service_detail_id;
        $productServiceDetail = ProductServiceDetail::select('product_service_details.id as product_service_detail_id', 'product_service_details.service_number', 'product_services.id as product_services_id', 'product_services.product_id', 'product_services.service_location', 'products.product_name')
            ->join('product_services', 'product_service_details.product_service_id', '=', 'product_services.id')
            ->join('products', 'product_services.product_id', '=', 'products.id')
            ->where('product_service_details.id', $product_service_detail_id)->first();

        // dd($productServiceDetail);
        $acid = $stocks[0]->customer_id;

        $customerPayment = FinanceTransaction::where('invoice_no', $invoices)->where('acid', $acid)->where('balance_type', 'Cr')->pluck('amount')->first();
        $paymentNarration = FinanceTransaction::where('invoice_no', $invoices)->where('acid', $acid)->where('balance_type', 'Cr')->pluck('narration')->first();

        $pdf = PDF::loadView('pages.pdf.sales_report_invoice_wise_pdf', array('productServiceDetail' => $productServiceDetail, 'stocks' => $stocks, 'invoices' => $invoices, 'customerPayment' => $customerPayment, 'paymentNarration' => $paymentNarration, 'data' => $data));
        $pdf->setPaper('A4', 'portrait');

        //return view('pages.pdf.sales_report_invoice_wise_pdf', array('productServiceDetail' => $productServiceDetail, 'stocks' => $stocks, 'invoices' => $invoices, 'customerPayment' => $customerPayment, 'paymentNarration' => $paymentNarration, 'data' => $data));

         return $pdf->stream(Carbon::now() . '-sales_report.pdf');


    }

    // Sales Customer Report ----------------------------------------------
    public function salesReportCustomerWise(Request $request)
    {
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');
        $acNames = FinanceAccount::where('account_status', 1)->where('account_group_code', '100020001')->orderBy('id', 'desc')->pluck('account_name', 'id')->all();
        return view('pages.product.stock.sales_report_customer_wise', compact('acNames', 'startDate', 'endDate'));
    }

    // customer wise sales search
    public function salesReportCustomerWiseSearch($startDate, $endDate, $acNameID, $pdf)
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
            $data['company_mobile'] = $companySetting->company_mobile;

            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $pdf = PDF::loadView('pages.pdf.sales_report_customer_wise_pdf', array('dateWiseSalesSearch' => $dateWiseSalesSearch, 'data' => $data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream(Carbon::now() . '-recentstat.pdf');
        }
    }

    // Sales Customer Report ----------------------------------------------
    public function salesReportItemWise(Request $request)
    {
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');
        $products = Product::where('is_saleable', 1)->where('status', 1)->pluck('product_name', 'id')->all();
        $acNames = FinanceAccount::where('account_status', 1)->where('account_group_code', '100020001')->orderBy('id', 'desc')->pluck('account_name', 'id')->all();
        return view('pages.product.stock.sales_report_item_wise', compact('products', 'acNames', 'startDate', 'endDate'));
    }

    // customer wise sales search
    public function salesReportItemWiseSearch($startDate, $endDate, $products, $acNameID, $pdf)
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
            $data['company_mobile'] = $companySetting->company_mobile;

            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $pdf = PDF::loadView('pages.pdf.sales_report_customer_wise_pdf', array('dateWiseSalesSearch' => $dateWiseSalesSearch, 'data' => $data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream(Carbon::now() . '-recentstat.pdf');
        }
    }


    // Sales Item Wise Profit Report ----------------------------------------------
    public function salesProfitItemWise(Request $request)
    {
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');
        $products = Product::where('is_saleable', 1)->where('status', 1)->pluck('product_name', 'id')->all();
        $invoices = Stock::where('invoice_no', 'like', 'INV%')->pluck('invoice_no');
        // dd($invoices);
        return view('pages.product.stock.sales_profit_item_wise', compact('products', 'invoices', 'startDate', 'endDate'));
    }
    public function salesProfitItemWiseSearch($startDate, $endDate, $productID, $pdf)
    {
        $SalesProfitItemWise = DB::connection()->select("CALL sp_SalesProfitItemWise(?, ?, ?)", array($startDate, $endDate, $productID));

        if ($pdf === "list") {
            return response()->json($SalesProfitItemWise);
        } elseif ($pdf === "pdfurl") {
            $data = [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ];
            $pdf = PDF::loadView('pages.pdf.sales_profit_item_wise_pdf', ['salesProfitItemWise' => $SalesProfitItemWise, 'data' => $data]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream(Carbon::now() . '-recentstat.pdf');
        }
    }

    // Sales Invoice Wise Profit Report ----------------------------------------------
    public function salesProfitInvoiceWise(Request $request)
    {
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');
        $products = Product::where('is_saleable', 1)->where('status', 1)->pluck('product_name', 'id')->all();
        $invoices = Stock::where('invoice_no', 'like', 'INV%')->pluck('invoice_no');
        // dd($invoices);
        return view('pages.product.stock.sales_profit_invoice_wise', compact('products', 'invoices', 'startDate', 'endDate'));
    }
    public function salesProfitInvoiceWiseSearch($startDate, $endDate, $pdf)
    {
        $SalesProfitInvoiceWise = DB::connection()->select("CALL sp_SalesProfitInvoiceWise(?, ?)", array($startDate, $endDate));

        if ($pdf === "list") {
            return response()->json($SalesProfitInvoiceWise);
        } elseif ($pdf === "pdfurl") {
            $data = [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ];
            $pdf = PDF::loadView('pages.pdf.sales_profit_invoice_wise_pdf', ['SalesProfitInvoiceWise' => $SalesProfitInvoiceWise, 'data' => $data]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream(Carbon::now() . '-recentstat.pdf');
        }
    }
    public function salesProfitInvoiceWiseDetails($invoiceNo)
    {
        $stocks = Stock::with(['product', 'customer_finance_account'])->where('invoice_no', $invoiceNo)->get();
        $customer_ids = $stocks->pluck('customer_id')->unique();
        $acid = $customer_ids->first();
        $customerPayment = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $acid)->where('balance_type', 'Cr')->pluck('amount')->first();
        $paymentNarration = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $acid)->where('balance_type', 'Cr')->pluck('narration')->first();

        return view('pages.product.stock.sales_profit_invoice_wise_details', compact('stocks', 'customerPayment', 'paymentNarration'));
    }
}
