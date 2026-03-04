<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Warehouse;
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
        $warehouses = Warehouse::where('status', 1)->get();
        $products = Product::where('is_purchaseable', 1)->where('status', 1)->get();
        $units = ProductUnit::get();
        $suppliers = Supplier::where('status', 1)->get();
        $supplierAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', '400010001')->get();
        // dd($supplierAccounts);
        $fromAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', '100020002')->get();
        // $fromAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', 'like', '10002%')->whereNotIn('account_group_code', ['100020001'])->get();
        // dd($fromAccounts);
        return view('pages.product.stock._stock-add', compact('products', 'warehouses', 'suppliers', 'units', 'supplierAccounts', 'fromAccounts'));
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
            ->where('fa.id', $ac_name)
            // ->where('fa.account_name', $ac_name)
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
            'voucher_date' => 'required',
            'supplier_id' => 'required',
            'givenAmount' => 'required',
            'table_product_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $purchaseDate = $request->input('voucher_date');
        $warehouseId = $request->input('warehouse_id');
        $supplierInvoiceNo = $request->input('supplier_invoice_no');
        $netTotalAmount = $request->input('netTotalAmount');
        $givenAmount = $request->input('givenAmount');
        $remarks = $request->input('remarks');
        $done_by = Auth::user()->name;

        $supplierId = $request->input('supplier_id');
        $supplierName = FinanceAccount::where('account_status', 1)->where('id', $supplierId)->value('account_name');
        $payAccountId = $request->input('pay_account');
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
            $crVoucher = DB::table('invoiceno')->first('cr_voucher_no');
            $getCrVoucherNo = $crVoucher->cr_voucher_no;
            $crVoucherNo = '01PV' . str_pad($getCrVoucherNo, 6, '0', STR_PAD_LEFT);
            DB::table('invoiceno')->update([
                'cr_voucher_no' => $getCrVoucherNo + 1,
            ]);
        
            $purchaseNo = DB::table('invoiceno')->first('purchase_no');
            $getPurchaseNo = $purchaseNo->purchase_no;
            $purchaseNumber = 'PUR' . str_pad($getPurchaseNo, 6, '0', STR_PAD_LEFT);
            DB::table('invoiceno')->update([
                'purchase_no' => $getPurchaseNo + 1,
            ]);

            foreach ($request->get('table_product_id') as $key => $productId) {
                $stockIn = new Stock();
                $stockQuantity = $request->input('table_product_quantity')[$key];
                $stockPrice = $request->input('table_product_price')[$key];
                $stockDiscount = $request->input('table_product_discount')[$key];
                $stockTotal = $request->input('table_product_cart_amount')[$key];
                $product = Product::find($productId);
                $productName = $product ? $product->product_name : 'Unknown Product';

                // Only update the purchase_price for the specific product if it's different
                if ($product && $stockPrice != $product->purchase_price) {
                    DB::table('products')
                        ->where('id', $product->id) // Target the specific product by ID
                        ->update(['purchase_price' => $stockPrice]);
                }
                
                $stockIn->invoice_no = $purchaseNumber;
                $stockIn->stock_type = 'In';
                $stockIn->stock_date = $purchaseDate;
                $stockIn->warehouse_id = $warehouseId;
                $stockIn->product_id = $request->input('table_product_id')[$key];
                $stockIn->supplier_id = $supplierId;
                $stockIn->supplier_invoice_no = $supplierInvoiceNo;
                $stockIn->stock_in_quantity = $stockQuantity;
                $stockIn->stock_out_quantity = 0;
                $stockIn->stock_in_unit_price = $stockPrice;
                $stockIn->stock_in_discount = $stockDiscount;
                $stockIn->stock_in_total_amount = $stockTotal;
                $stockIn->status = 1;
                $stockIn->remarks = $remarks;
                $stockIn->done_by = $done_by;
                $stockIn->save();
                $purchaseProductData[] = "Name:$productName, Qty:$stockQuantity X $stockPrice, Total:$stockTotal\n";
            }
            $purchaseProductData_string = implode("\n", $purchaseProductData);
            echo $purchaseProductData_string;
            $purchaseNarration ='Invoice No:'.$purchaseNumber.', '.$purchaseProductData_string;

            $financeTransaction = FinanceTransaction::create([
                'company_code' => '01',
                'invoice_no' => $purchaseNumber,
                'voucher_no' => $crVoucherNo,
                'voucher_date' => $purchaseDate,
                'acid' => $GLOBALS['PurchaseAccountID'],
                'to_acc_name' => $supplierName,
                'type' => 'PV',
                'amount' => $netTotalAmount,
                'balance_type' => 'Dr',
                'narration' => $purchaseNarration,
                'transaction_date' => $purchaseDate,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
            ]);

            $financeTransaction2 = FinanceTransaction::create([
                'company_code' => '01',
                'invoice_no' => $purchaseNumber,
                'voucher_no' => $crVoucherNo,
                'voucher_date' => $purchaseDate,
                'acid' => $supplierId,
                'to_acc_name' => $GLOBALS['PurchaseAccountName'],
                'type' => 'PV',
                'amount' => $netTotalAmount,
                'balance_type' => 'Cr',
                'narration' => $purchaseNarration,
                'transaction_date' => $purchaseDate,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
            ]);

            if ($givenAmount > 0) {
                $formatAmount = formatCurrency($givenAmount);

                // $narration[] = "Invoice No:$purchaseNumber, $payAccName Payment To: $supplierName, Payment Amount:$formatAmount TK\n";
                // $payment_type = $payAccGroupName;

                if ($payAccGroupName == 'Bank Account') {
                    if ($cheque_type == 'Cheque') {
                        $narration[] = "Invoice No:$purchaseNumber, $payAccName Payment To: $supplierName, Through Bank $cheque_type, Cheque No:$cheque_no, Cheque Date:$cheque_date, Payment Amount:$formatAmount TK $remarks\n";
                    } else {
                        $narration[] = "Invoice No:$purchaseNumber, $payAccName Payment To: $supplierName, Through Bank $cheque_type, Payment Amount:$formatAmount TK $remarks\n";
                    }
                } else if ($payAccGroupName == 'Mobile Bank') {
                    $narration[] = "Invoice No:$purchaseNumber, $payAccName Payment To: $supplierName, Through Mobile Bank $mobile_bank_name, Mobile Number:$mobile_number, Transaction ID:$transaction_id, Payment Amount:$formatAmount TK $remarks\n";
                } else {
                    $narration[] = "Invoice No:$purchaseNumber, $payAccName Payment To: $supplierName, Through Cash, Payment Amount:$formatAmount TK $remarks\n";
                }
                $narration = implode("\n", $narration);
                echo $narration;

                $financeTransaction3 = FinanceTransaction::create([
                    'company_code' => '01',
                    'invoice_no' => $purchaseNumber,
                    'voucher_no' => $crVoucherNo,
                    'voucher_date' => $purchaseDate,
                    'acid' => $supplierId,
                    'to_acc_name' => $payAccName,
                    'type' => 'PV',
                    'amount' => $givenAmount,
                    'balance_type' => 'Dr',
                    'payment_type' => $payment_type,
                    'cheque_no' => $cheque_no,
                    'cheque_date' => $cheque_date,
                    'cheque_type' => $cheque_type,
                    'narration' => $narration,
                    'transaction_date' => $purchaseDate,
                    'transaction_by' => $done_by,
                    'done_by' => $done_by,
                ]);

                $financeTransaction4 = FinanceTransaction::create([
                    'company_code' => '01',
                    'invoice_no' => $purchaseNumber,
                    'voucher_no' => $crVoucherNo,
                    'voucher_date' => $purchaseDate,
                    'acid' => $payAccountId,
                    'to_acc_name' => $supplierName,
                    'type' => 'PV',
                    'amount' => $givenAmount,
                    'balance_type' => 'Cr',
                    'payment_type' => $payment_type,
                    'cheque_no' => $cheque_no,
                    'cheque_date' => $cheque_date,
                    'cheque_type' => $cheque_type,
                    'narration' => $narration,
                    'transaction_date' => $purchaseDate,
                    'transaction_by' => $done_by,
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
            'alert-type' => 'success',
            'invoice' => $purchaseNumber
        ]);
    }
    // Purchase Store -------end------

    // purchaseInvoice List Report details pdf -------start------
    public function purchaseInvoiceReport()
    {
        return view('pages.product.stock.purchase_report_invoice_wise');
    }
    
    public function purchaseInvoiceDateSearch($startDate, $endDate, $pdf)
    {
        $inv = 'PUR';
        
        $query = Stock::selectRaw('stocks.invoice_no, stocks.supplier_id, fa.account_name, MIN(stocks.stock_date) as stock_date, SUM(stocks.stock_in_discount) as total_discount, SUM(stocks.stock_in_total_amount) as total_amount')
            ->join('finance_accounts as fa', function ($join) {
                $join->on('stocks.supplier_id', '=', 'fa.id')
                    ->where('fa.account_group_code', '=', '400010001'); // Account Payable code 400010001 use supplier
            })
            ->where('stocks.invoice_no', 'LIKE', '%' . $inv . '%');

        if ($startDate && $endDate) {
            // Assuming $startDate and $endDate are in the format 'YYYY-MM-DD'
            $query->whereRaw('DATE(stocks.stock_date) BETWEEN ? AND ?', [$startDate, $endDate]);
        }

        if ($pdf == "list") {
            $purchaseInvoices = $query->groupBy('stocks.invoice_no', 'stocks.supplier_id', 'fa.account_name')
            ->orderBy('stocks.stock_date', 'ASC')
            ->get();
            return response()->json($purchaseInvoices);
        }
        if ($pdf == "pdfurl") {
            $purchaseInvoices = $query->groupBy('stocks.invoice_no', 'stocks.supplier_id', 'fa.account_name')
            ->orderBy('stocks.id', 'ASC')
            ->get();
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;
            $data['company_address'] = $companySetting->company_address;
            $data['company_logo_one'] = $companySetting->company_logo_one;
            $data['company_mobile'] = $companySetting->company_mobile;

            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $pdf = PDF::loadView('pages.pdf.purchase_date_invoice_wise_report_pdf', array('dateWisePurchaseSearch' => $purchaseInvoices, 'data' => $data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream(Carbon::now() . '-purchase_date_invoice_wise_report_pdf.pdf');
        }
    }

    // purchaseInvoiceReturn
    public function purchaseInvoiceReturn($invoiceNo)
    {   
        $products = Product::where('is_purchaseable', 1)->where('status', 1)->get();
        $units = ProductUnit::get();
        // $suppliers = Supplier::where('status', 1)->get();
        $supplierAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', '400010001')->get();
        $fromAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', 'like', '10002%')->whereNotIn('account_group_code', ['100020001'])->get();
        $stocks = Stock::with(['product', 'unit', 'supplier_finance_account'])->where('invoice_no', $invoiceNo)->get();
        $supplierFinance = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $stocks[0]->supplier_id)->select('invoice_no','voucher_no', 'voucher_date')->first();
        $supplierPayment = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $stocks[0]->supplier_id)->where('balance_type', 'Dr')->select('to_acc_name','amount')->first();
        return view('pages.product.stock.purchase_invoice_return', compact('invoiceNo','stocks', 'products', 'units', 'supplierAccounts', 'fromAccounts', 'supplierPayment', 'supplierFinance'));
    }

    public function purchaseInvoiceReturnStore(Request $request, $invoiceNo)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'table_product_id' => 'required',
            'voucher_date' => 'required',
            'supplier_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $purchaseDate = $request->input('voucher_date');
        $returnNetTotalAmount = $request->input('returnNetTotalAmount');
        $netTotalAmount = $request->input('netTotalAmount');
        $givenAmount = $request->input('givenAmount');
        $supplierInvoiceNo = $request->input('supplier_invoice_no');
        $remarks = $request->input('remarks');
        $done_by = Auth::user()->name;

        $supplierId = $request->input('supplier_id');
        $supplierName = FinanceAccount::where('account_status', 1)->where('id', $supplierId)->value('account_name');
        $payAccountId = $request->input('pay_account');
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

        // $crVoucherNo = $request->input('voucher_no');
        // $getPurchaseNumber = $request->input('invoice_no'); // Assuming value is 'PUR000002'
        $getPurchaseNumber = $invoiceNo; // Assuming value is 'PUR000002'
        $purchaseNumber = str_replace('PUR', 'RTP', $getPurchaseNumber);
        // dd($purchaseNumber);


        DB::beginTransaction();
        try {
            // get voucher_no
            $crVoucher = DB::table('invoiceno')->first('cr_voucher_no');
            $getCrVoucherNo = $crVoucher->cr_voucher_no;
            $crVoucherNo = '01PV' . str_pad($getCrVoucherNo, 6, '0', STR_PAD_LEFT);
            DB::table('invoiceno')->update([
                'cr_voucher_no' => $getCrVoucherNo + 1,
            ]);
        
            // $purchaseNo = DB::table('invoiceno')->first('purchase_no');
            // $getPurchaseNo = $purchaseNo->purchase_no;
            // $purchaseNumber = 'PUR' . str_pad($getPurchaseNo, 6, '0', STR_PAD_LEFT);
            // DB::table('invoiceno')->update([
            //     'purchase_no' => $getPurchaseNo + 1,
            // ]);

            foreach ($request->get('table_product_id') as $key => $productId) {
                $stockIn = new Stock();
                $stockQuantity = $request->input('table_product_quantity')[$key];
                $stockReturnQuantity = $request->input('table_product_return_quantity')[$key];
                $stockPrice = $request->input('table_product_price')[$key];
                $stockDiscount = $request->input('table_product_discount')[$key];
                $stockTotal = $request->input('table_product_cart_amount')[$key];
                $stockReturnTotal = $request->input('table_product_return_cart_amount')[$key];
                // dd($stockReturnTotal);
                $product = Product::find($productId);
                $productName = $product ? $product->product_name : 'Unknown Product';

                if ($stockReturnQuantity > 0 ) {
                    $stockIn->invoice_no = $purchaseNumber;
                    $stockIn->stock_type = 'Out';
                    $stockIn->stock_date = $purchaseDate;
                    $stockIn->product_id = $request->input('table_product_id')[$key];
                    $stockIn->supplier_id = $supplierId;
                    $stockIn->supplier_invoice_no = $supplierInvoiceNo;
                    $stockIn->stock_in_quantity = 0;
                    $stockIn->stock_out_quantity = $stockReturnQuantity;
                    $stockIn->stock_out_unit_price = $stockPrice;
                    $stockIn->stock_out_discount = $stockDiscount;
                    $stockIn->stock_out_total_amount = $stockReturnTotal; //$stockTotal;
                    $stockIn->status = 1;
                    $stockIn->remarks = $remarks;
                    $stockIn->done_by = $done_by;

                    // dd($stockIn);
                    $stockIn->save();
                    $purchaseProductData[] = "Name:$productName, Qty:$stockReturnQuantity X $stockPrice, Total:$stockReturnTotal\n";
                }
            }
            $purchaseProductData_string = implode("\n", $purchaseProductData);
            echo $purchaseProductData_string;
            $purchaseNarration ='Invoice No:'.$purchaseNumber.', '.$purchaseProductData_string;

            $financeTransaction = FinanceTransaction::create([
                'company_code' => '01',
                'invoice_no' => $purchaseNumber,
                'voucher_no' => $crVoucherNo,
                'voucher_date' => $purchaseDate,
                'acid' => $GLOBALS['PurchaseAccountID'],
                'to_acc_name' => $supplierName,
                'type' => 'PR',
                'amount' => $returnNetTotalAmount,
                'balance_type' => 'Cr',
                'narration' => $purchaseNarration,
                'transaction_date' => $purchaseDate,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
            ]);

            $financeTransaction2 = FinanceTransaction::create([
                'company_code' => '01',
                'invoice_no' => $purchaseNumber,
                'voucher_no' => $crVoucherNo,
                'voucher_date' => $purchaseDate,
                'acid' => $supplierId,
                'to_acc_name' => $GLOBALS['PurchaseAccountName'],
                'type' => 'PR',
                'amount' => $returnNetTotalAmount,
                'balance_type' => 'Dr',
                'narration' => $purchaseNarration,
                'transaction_date' => $purchaseDate,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
            ]);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Ops ! Could Not add purchase history. DB transaction lost']);
        }
        return back()->with([
            'message' => 'Purchase History Added Successfully!',
            'alert-type' => 'success',
            'invoice' => $purchaseNumber
        ]);
    }

    public function purchaseInvoiceReturnList()
    {
        return view('pages.product.stock.purchase_return_invoice_wise');  
    }
    
    public function purchaseInvoiceReturnListSearch($startDate, $endDate, $pdf)
    {
        $inv = 'RTP';
        $query = Stock::selectRaw('stocks.invoice_no, stocks.supplier_id, fa.account_name, MIN(stocks.stock_date) as stock_date, SUM(stocks.stock_out_discount) as total_discount, SUM(stocks.stock_out_total_amount) as total_amount')
            ->join('finance_accounts as fa', function ($join) {
                $join->on('stocks.supplier_id', '=', 'fa.id')
                    ->where('fa.account_group_code', '=', '400010001'); // Account Payable code 400010001 use supplier
            })
            ->where('stocks.invoice_no', 'LIKE', '%' . $inv . '%');

        if ($startDate && $endDate) {
            // Assuming $startDate and $endDate are in the format 'YYYY-MM-DD'
            $query->whereRaw('DATE(stocks.stock_date) BETWEEN ? AND ?', [$startDate, $endDate]);
        }

        if ($pdf == "list") {
            $purchaseInvoices = $query->groupBy('stocks.invoice_no', 'stocks.supplier_id', 'fa.account_name')
            ->orderBy('stocks.stock_date', 'ASC')
            ->get();
            return response()->json($purchaseInvoices);
        }
        if ($pdf == "pdfurl") {
            $purchaseInvoices = $query->groupBy('stocks.invoice_no', 'stocks.supplier_id', 'fa.account_name')
            ->orderBy('stocks.id', 'ASC')
            ->get();
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;
            $data['company_address'] = $companySetting->company_address;
            $data['company_logo_one'] = $companySetting->company_logo_one;
            $data['company_mobile'] = $companySetting->company_mobile;

            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $pdf = PDF::loadView('pages.pdf.purchase_date_invoice_wise_return_report_pdf', array('dateWisePurchaseSearch' => $purchaseInvoices, 'data' => $data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream(Carbon::now() . '-purchase_date_invoice_wise_report_pdf.pdf');
        }
    }

    public function purchaseReturnInvoiceDetails($invoiceNo)
    {
        $stocks = Stock::with(['product', 'supplier_finance_account'])->where('invoice_no', $invoiceNo)->get();
        $supplier_ids = $stocks->pluck('supplier_id')->unique();
        $acid = $supplier_ids->first();
        // $supplierPayment = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $acid)->where('balance_type', 'Dr')->whereNull('payment_type')->pluck('amount')->first();
        // $paymentNarration = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $acid)->where('balance_type', 'Dr')->pluck('narration')->first();
        return view('pages.product.stock.purchase_return_invoice_details', compact('stocks'));
    }

    public function purchaseReturnInvoiceDetailsPdf($invoices)
    {
        $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        $data['company_name'] = $companySetting->company_name;
        $data['company_address'] = $companySetting->company_address;
        $data['company_logo_one'] = $companySetting->company_logo_one;
        $data['company_mobile'] = $companySetting->company_mobile;

        $stocks = Stock::with(['product', 'supplier_finance_account'])->where('invoice_no', $invoices)->get();
        $supplier_ids = $stocks->pluck('supplier_id')->unique();
        $acid = $supplier_ids->first();
        // $supplierPayment = FinanceTransaction::where('invoice_no', $invoices)->where('acid', $acid)->where('balance_type', 'Dr')->pluck('amount')->first();
        // $paymentNarration = FinanceTransaction::where('invoice_no', $invoices)->where('acid', $acid)->where('balance_type', 'Dr')->pluck('narration')->first();

        $pdf = PDF::loadView('pages.pdf.purchase_return_invoice_wise_pdf', array('stocks' => $stocks, 'invoices' => $invoices, 'data' => $data));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream(Carbon::now() . '-purchase_report.pdf');
    }

    // purchaseInvoiceEdit
    public function purchaseInvoiceEdit($invoiceNo)
    {   
        $warehouses = Warehouse::where('status', 1)->get();
        $products = Product::where('is_purchaseable', 1)->where('status', 1)->get();
        $units = ProductUnit::get();
        // $suppliers = Supplier::where('status', 1)->get();
        $supplierAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', '400010001')->get();
        $fromAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', 'like', '10002%')->whereNotIn('account_group_code', ['100020001'])->get();
        $stocks = Stock::with(['product', 'unit', 'supplier_finance_account'])->where('invoice_no', $invoiceNo)->get();
        $supplierFinance = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $stocks[0]->supplier_id)->select('voucher_no', 'voucher_date')->first();
        $supplierPayment = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $stocks[0]->supplier_id)->where('balance_type', 'Dr')->select('to_acc_name','amount')->first();
        // dd($supplierPayment);
        // dd($supplierAccounts);
        return view('pages.product.stock.purchase_invoice_edit', compact('invoiceNo', 'warehouses', 'stocks', 'products', 'units', 'supplierAccounts', 'fromAccounts', 'supplierPayment', 'supplierFinance'));
    }

    public function purchaseInvoiceUpdate(Request $request, $invoiceNo)
    {
        // dd($invoiceNo);
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required',
            'givenAmount' => 'required',
            'table_product_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $purchaseNumber = $invoiceNo;
        $crVoucherNo = $request->input('voucher_no');
        $purchaseDate = $request->input('voucher_date');
        $supplierInvoiceNo = $request->input('supplier_invoice_no');
        $supplierId = $request->input('supplier_id');
        $supplierName = FinanceAccount::where('account_status', 1)->where('id', $supplierId)->value('account_name');
        $payAccountId = $request->input('pay_account');
        $payAccName = FinanceAccount::where('account_status', 1)->where('id', $payAccountId)->value('account_name');
       
        $payAccGroupName = FinanceAccount::leftJoin('finance_groups', 'finance_accounts.account_group_code', '=', 'finance_groups.group_code')
            ->where('finance_accounts.account_status', 1)
            ->where('finance_accounts.id', $payAccountId)
            ->value('finance_groups.group_name');
        // dd($payAccGroupName);
        $payment_type = $payAccGroupName;

        $netTotalAmount = $request->input('netTotalAmount');
        $givenAmount = $request->input('givenAmount');
        $remarks = $request->input('remarks');
        $done_by = Auth::user()->name;

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
            foreach ($request->get('table_product_id') as $key => $productId) {
                $stockIn = new Stock();
                $warehouseId = $request->input('warehouse_id')[$key];
                $stockQuantity = $request->input('table_product_quantity')[$key];
                $stockPrice = $request->input('table_product_price')[$key];
                $stockDiscount = $request->input('table_product_discount')[$key];
                // $stockTotal = ($stockQuantity * $stockPrice) - $stockDiscount;
                $stockTotal = $request->input('table_product_cart_amount')[$key];
                $product = Product::find($productId);
                $productName = $product ? $product->product_name : 'Unknown Product';

                // Only update the purchase_price for the specific product if it's different
                if ($product && $stockPrice != $product->purchase_price) {
                    DB::table('products')
                        ->where('id', $product->id) // Target the specific product by ID
                        ->update(['purchase_price' => $stockPrice]);
                }

                $stockIn->warehouse_id = $warehouseId;
                $stockIn->invoice_no = $purchaseNumber;
                $stockIn->stock_type = 'In';
                $stockIn->stock_date = $purchaseDate;
                $stockIn->product_id = $request->input('table_product_id')[$key];
                $stockIn->supplier_id = $supplierId;
                $stockIn->supplier_invoice_no = $supplierInvoiceNo;
                $stockIn->stock_in_quantity = $stockQuantity;
                $stockIn->stock_out_quantity = 0;
                $stockIn->stock_in_unit_price = $stockPrice;
                $stockIn->stock_in_discount = $stockDiscount;
                $stockIn->stock_in_total_amount = $stockTotal;
                $stockIn->status = 1;
                $stockIn->remarks = $remarks;
                $stockIn->done_by = $done_by;
                $stockIn->save();
                $purchaseProductData[] = "Name:$productName, Qty:$stockQuantity X $stockPrice, Total:$stockTotal\n";
            }
            $purchaseProductData_string = implode("\n", $purchaseProductData);
            echo $purchaseProductData_string;
            $purchaseNarration ='Invoice No:'.$purchaseNumber.', '.$purchaseProductData_string;

            $financeTransaction = FinanceTransaction::create([
                'company_code' => '01',
                'invoice_no' => $purchaseNumber,
                'voucher_no' => $crVoucherNo,
                'voucher_date' => $purchaseDate,
                'acid' => $GLOBALS['PurchaseAccountID'],
                'to_acc_name' => $supplierName,
                'type' => 'PV',
                'amount' => $netTotalAmount,
                'balance_type' => 'Dr',
                'narration' => $purchaseNarration,
                'transaction_date' => $purchaseDate,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
            ]);

            $financeTransaction2 = FinanceTransaction::create([
                'company_code' => '01',
                'invoice_no' => $purchaseNumber,
                'voucher_no' => $crVoucherNo,
                'voucher_date' => $purchaseDate,
                'acid' => $supplierId,
                'to_acc_name' => $GLOBALS['PurchaseAccountName'],
                'type' => 'PV',
                'amount' => $netTotalAmount,
                'balance_type' => 'Cr',
                'narration' => $purchaseNarration,
                'transaction_date' => $purchaseDate,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
            ]);

            if ($givenAmount > 0) {
                $formatAmount = formatCurrency($givenAmount);

                // $narration[] = "Invoice No:$purchaseNumber, $payAccName Payment To: $supplierName, Payment Amount:$formatAmount TK\n";
                // $narration = implode("\n", $narration);
                // echo $narration;

                if ($payAccGroupName == 'Bank Account') {
                    if ($cheque_type == 'Cheque') {
                        $narration[] = "Invoice No:$purchaseNumber, $payAccName Payment To: $supplierName, Through Bank $cheque_type, Cheque No:$cheque_no, Cheque Date:$cheque_date, Payment Amount:$formatAmount TK $remarks\n";
                    } else {
                        $narration[] = "Invoice No:$purchaseNumber, $payAccName Payment To: $supplierName, Through Bank $cheque_type, Payment Amount:$formatAmount TK $remarks\n";
                    }
                } else if ($payAccGroupName == 'Mobile Bank') {
                    $narration[] = "Invoice No:$purchaseNumber, $payAccName Payment To: $supplierName, Through Mobile Bank $mobile_bank_name, Mobile Number:$mobile_number, Transaction ID:$transaction_id, Payment Amount:$formatAmount TK $remarks\n";
                } else {
                    $narration[] = "Invoice No:$purchaseNumber, $payAccName Payment To: $supplierName, Through Cash, Payment Amount:$formatAmount TK $remarks\n";
                }
                $narration = implode("\n", $narration);
                echo $narration;

                $financeTransaction3 = FinanceTransaction::create([
                    'company_code' => '01',
                    'invoice_no' => $purchaseNumber,
                    'voucher_no' => $crVoucherNo,
                    'voucher_date' => $purchaseDate,
                    'acid' => $supplierId,
                    'to_acc_name' => $payAccName,
                    'type' => 'PV',
                    'amount' => $givenAmount,
                    'balance_type' => 'Dr',
                    'payment_type' => $payment_type,
                    'narration' => $narration,
                    'transaction_date' => $purchaseDate,
                    'cheque_no' => $cheque_no,
                    'cheque_date' => $cheque_date,
                    'cheque_type' => $cheque_type,
                    'transaction_by' => $done_by,
                    'done_by' => $done_by,
                ]);

                $financeTransaction4 = FinanceTransaction::create([
                    'company_code' => '01',
                    'invoice_no' => $purchaseNumber,
                    'voucher_no' => $crVoucherNo,
                    'voucher_date' => $purchaseDate,
                    'acid' => $payAccountId,
                    'to_acc_name' => $supplierName,
                    'type' => 'PV',
                    'amount' => $givenAmount,
                    'balance_type' => 'Cr',
                    'payment_type' => $payment_type,
                    'narration' => $narration,
                    'transaction_date' => $purchaseDate,
                    'cheque_no' => $cheque_no,
                    'cheque_date' => $cheque_date,
                    'cheque_type' => $cheque_type,
                    'transaction_by' => $done_by,
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
            'alert-type' => 'success',
            'invoice' => $purchaseNumber
        ]);
    }
    
    public function purchaseInvoiceSingleRowDestroy($id) {

        $stock = Stock::find($id);
        $invoice_no= $stock->invoice_no;
        $financeTransaction = FinanceTransaction::where('invoice_no', $invoice_no)->first();
        dd($financeTransaction);
        if ($stock) {
            $stock->delete();
            return back()->with([
                        'message' => 'Successfully deleted!',
                        'alert-type' => 'success'
            ]);
        }
    }
   
    public function purchaseInvoiceDetails($invoiceNo)
    {        
        $stocks = Stock::with(['product', 'supplier_finance_account'])->where('invoice_no', $invoiceNo)->get();
        $supplier_ids = $stocks->pluck('supplier_id')->unique();
        $acid = $supplier_ids->first();
        $supplierPayment = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $acid)->where('balance_type', 'Dr')->pluck('amount')->first();
        $paymentNarration = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $acid)->where('balance_type', 'Dr')->pluck('narration')->first();

        return view('pages.product.stock.purchase_report_invoice_details', compact('stocks', 'supplierPayment', 'paymentNarration'));
    }

    public function purchaseInvoiceDetailsPdf($invoices)
    {
        $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        $data['company_name'] = $companySetting->company_name;
        $data['company_address'] = $companySetting->company_address;
        $data['company_logo_one'] = $companySetting->company_logo_one;
        $data['company_mobile'] = $companySetting->company_mobile;

        $stocks = Stock::with(['product', 'supplier_finance_account'])->where('invoice_no', $invoices)->get();
        $supplier_ids = $stocks->pluck('supplier_id')->unique();
        $acid = $supplier_ids->first();
        $supplierPayment = FinanceTransaction::where('invoice_no', $invoices)->where('acid', $acid)->where('balance_type', 'Dr')->pluck('amount')->first();
        $paymentNarration = FinanceTransaction::where('invoice_no', $invoices)->where('acid', $acid)->where('balance_type', 'Dr')->pluck('narration')->first();

        $pdf = PDF::loadView('pages.pdf.purchase_report_invoice_wise_pdf', array('stocks' => $stocks, 'invoices' => $invoices, 'supplierPayment' => $supplierPayment, 'paymentNarration' => $paymentNarration, 'data' => $data));
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
    // supplier wise purchase report
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
        $dateWisePurchaseSearch = DB::table(DB::raw("($query) AS subquery"))
                            ->select('invoice_no', 'voucher_no', 'voucher_date', 'transaction_date', 'account_name', 'account_mobile', 'account_address', 'narration', 'type', 'balance_type', 'amount')
                            ->get();
        if ($pdf == "list") {
            return response()->json($dateWisePurchaseSearch);
        }
        if ($pdf == "pdfurl") {
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;
            $data['company_address'] = $companySetting->company_address;
            $data['company_mobile'] = $companySetting->company_mobile;
            $data['company_logo_one'] = $companySetting->company_logo_one;
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $pdf = PDF::loadView('pages.pdf.purchase_report_supplier_wise_pdf', array('dateWisePurchaseSearch' => $dateWisePurchaseSearch, 'data'=>$data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream(Carbon::now().'-purchase_report_supplier_wise_pdf.pdf');
        }

    }
    // Supplier Wise List Report details pdf -------end------
}
