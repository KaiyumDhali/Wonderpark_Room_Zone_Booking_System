<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\ProductService;
use App\Models\ProductServiceDetail;
use App\Models\Stock;
use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use App\Models\CompanySetting;
use App\Models\CustomerType;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Validator;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use GuzzleHttp\Psr7\Query;
use NumberFormatter;
use Rmunate\Utilities\SpellNumber;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;



class ProductServiceController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:read pending product service', ['only' => ['pendingProductServices', 'pendingProductServicesSearch', 'productServicesEntry', 'productServicesEntryStore']]);
        $this->middleware('permission:read complete product service', ['only' => ['completeProductServices', 'completeProductServicesSearch']]);

        $this->middleware('permission:read product service|write product service|create product service', ['only' => ['productServices', 'serviceInvoiceDateSearch']]);
        $this->middleware('permission:create product service', ['only' => ['serviceInvoice', 'store']]);
        $this->middleware('permission:write product service', ['only' => ['edit', 'update', 'destroy']]);
    }

    #region

    public function completeProductServices()
    {
        return view('pages.product.product_services.complete_service_list');
    }

    public function completeProductServicesSearch($startDate, $endDate, $pdf)
    {
        $completeServiceList = DB::connection()->select("CALL sp_CompleteServiceList(?, ?)", array($startDate, $endDate));

        if ($pdf == "list") {
            return response()->json($completeServiceList);
        }
        if ($pdf == "pdfurl") {
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;

            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $pdf = PDF::loadView('pages.pdf.complete_service_list_report_pdf', array('completeServiceList' => $completeServiceList, 'data' => $data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream(Carbon::now() . '-complete_service_list_report_pdf.pdf');
        }
    }

    public function pendingProductServices()
    {
        return view('pages.product.product_services.pending_service_list');
    }

    public function pendingProductServicesSearch($startDate, $endDate, $pdf)
    {
        $pendingServiceList = DB::connection()->select("CALL sp_PendingServiceList(?, ?)", array($startDate, $endDate));

        if ($pdf == "list") {
            return response()->json($pendingServiceList);
        }
        if ($pdf == "pdfurl") {
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;

            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $pdf = PDF::loadView('pages.pdf.pending_service_list_report_pdf', array('pendingServiceList' => $pendingServiceList, 'data' => $data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream(Carbon::now() . '-pending_service_list_report_pdf.pdf');
        }
    }

    public function productServicesEntry($id)
    {

        $warehouses = Warehouse::where('status', 1)->get();
        $products = Product::where('is_saleable', 1)->where('status', 1)->get();
        $toAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', '100020002')->get();
        // $toAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', 'like', '10002%')->whereNotIn('account_group_code', ['100020001'])->get();

        $query = "
        SELECT
        psd.id as id,
        ps.invoice_no as invoice_no,
        ps.product_id as product_id,
        p.product_name as product_name,
        ps.customer_id as customer_id,
        fa.account_name as customer_name,
        fa.account_mobile as customer_mobile,
        fa.account_address as customer_address,
        psd.service_date as service_date,
        psd.service_number as service_number
        FROM product_service_details as psd
        JOIN product_services as ps ON psd.product_service_id = ps.id
        JOIN products as p ON ps.product_id = p.id
        JOIN finance_accounts as fa ON ps.customer_id = fa.id
        WHERE psd.id = $id
        ";
        $getProductServicesEntry = DB::table(DB::raw("($query) AS subquery"))
            ->select('id', 'invoice_no', 'product_id', 'product_name', 'customer_id', 'customer_name', 'customer_mobile', 'customer_address', 'service_date', 'service_number')
            ->first();

        // dd($getProductServicesEntry);

        return view('pages.product.product_services.service_entry', compact('getProductServicesEntry', 'warehouses', 'products', 'toAccounts'));
    }


    public function productServicesEntryStore(Request $request, $id)
    {
        // dd($id);
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'voucher_date' => 'required',
            'customer_id' => 'required',
            'givenAmount' => 'required',
            'table_product_id' => 'required',

            'service_man_name' => 'required|string|max:100',
            'service_man_mobile' => 'nullable|string|max:20',

        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $service_man_name = $request->input('service_man_name');
        $service_man_mobile = $request->input('service_man_mobile');

        $serviceDate = $request->input('voucher_date');
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


            $productServiceDetail = ProductServiceDetail::find($id);

            // Update the product service detail fields with validated data
            $productServiceDetail->service_invoice = $salesNumber;
            $productServiceDetail->actual_service_date = $serviceDate;
            $productServiceDetail->service_man_name = $service_man_name;
            $productServiceDetail->service_man_mobile = $service_man_mobile;
            $productServiceDetail->remarks = $remarks;
            $productServiceDetail->service_status = 1;
            $productServiceDetail->done_by = $done_by;

            $productServiceDetail->save();


            $salesProductData = [];
            foreach ($request->get('table_product_id') as $key => $productId) {
                $stockOut = new Stock();
                $stockQuantity = $request->input('table_product_quantity')[$key];
                $stockPrice = $request->input('table_product_price')[$key];
                $stockDiscount = $request->input('table_product_discount')[$key];
                $stockTotal = $request->input('table_product_cart_amount')[$key];
                $tableRemarks = $request->input('table_remarks')[$key];
                $product = Product::find($productId);
                $productName = $product ? $product->product_name : 'Unknown Product';

                // Only update the sales_price for the specific product if it's different
                if ($product && $stockPrice != $product->sales_price) {
                    DB::table('products')
                        ->where('id', $product->id) // Target the specific product by ID
                        ->update(['sales_price' => $stockPrice]);
                }

                $stockOut->warehouse_id = $request->input('warehouse_id');
                $stockOut->stock_date = $serviceDate;
                $stockOut->stock_type = 'Out';
                $stockOut->delivery_challan_no = $deliveryChallanNumber;
                $stockOut->invoice_no = $salesNumber;
                $stockOut->customer_id = $customerId;
                $stockOut->product_id = $request->input('table_product_id')[$key];
                $stockOut->purchase_price = $request->input('table_purchase_price')[$key];
                $stockOut->stock_in_quantity = 0;
                $stockOut->stock_out_quantity = $stockQuantity;
                $stockOut->stock_out_unit_price = $stockPrice;
                $stockOut->stock_out_discount = $stockDiscount;
                $stockOut->stock_out_total_amount = $stockTotal;
                $stockOut->remarks = $tableRemarks;
                $stockOut->product_service_detail_id = $id;
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
                'voucher_date' => $serviceDate,
                'acid' => $customerId,
                'to_acc_name' => $GLOBALS['SalesAccountName'],
                'type' => 'SV',
                'amount' => $netTotalAmount,
                'balance_type' => 'Dr',
                'narration' => $salesNarration,
                'transaction_date' => $serviceDate,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
            ]);

            $financeTransaction2 = FinanceTransaction::create([
                'company_code' => '01',
                'delivery_challan_no' => $deliveryChallanNumber,
                'invoice_no' => $salesNumber,
                'voucher_no' => $voucherNo,
                'voucher_date' => $serviceDate,
                'acid' => $GLOBALS['SalesAccountID'],
                'to_acc_name' => $customerName,
                'type' => 'SV',
                'amount' => $netTotalAmount,
                'balance_type' => 'Cr',
                'narration' => $salesNarration,
                'transaction_date' => $serviceDate,
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
                    'voucher_date' => $serviceDate,
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
                    'transaction_date' => $serviceDate,
                    'transaction_by' => $done_by,
                    'done_by' => $done_by,
                ]);

                $financeTransaction4 = FinanceTransaction::create([
                    'company_code' => '01',
                    'delivery_challan_no' => $deliveryChallanNumber,
                    'invoice_no' => $salesNumber,
                    'voucher_no' => $voucherNo,
                    'voucher_date' => $serviceDate,
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
                    'transaction_date' => $serviceDate,
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


    public function notCompleteProductServices($id)
    {
        $productServiceDetail = ProductServiceDetail::find($id);
        $productServiceDetail->service_status = 0;
        $productServiceDetail->save();
        return redirect()->route('complete_product_services');
    }

    public function productServices()
    {
        $products = Product::where('is_saleable', 1)->where('is_serviceable', 1)->where('status', 1)->get();
        $customerAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', '100020001')->get();

        return view('pages.product.product_services.service_list', compact('products', 'customerAccounts'));
    }

    public function serviceInvoiceDateSearch($startDate, $endDate, $pdf)
    {
        $inv = 'INV';
        $query = Stock::selectRaw('
            stocks.invoice_no,
            stocks.customer_id,
            fa.account_name,
            MIN(stocks.stock_date) as stock_date,
            SUM(stocks.stock_out_discount) as total_discount,
            SUM(stocks.stock_out_total_amount) as total_amount,
            products.is_serviceable
        ')
            ->join('finance_accounts as fa', function ($join) {
                $join->on('stocks.customer_id', '=', 'fa.id')
                    ->where('fa.account_group_code', '=', '100020001');
            })
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->where('stocks.invoice_no', 'LIKE', '%' . $inv . '%')
            ->where('products.is_serviceable', '=', 1)
            ->groupBy('stocks.invoice_no', 'stocks.customer_id', 'fa.account_name', 'products.is_serviceable');


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
            $pdf = PDF::loadView('pages.pdf.sales_date_invoice_wise_report_pdf', array('dateWiseSalesSearch' => $salesInvoices, 'data' => $data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream(Carbon::now() . '-sales_date_invoice_wise_report_pdf.pdf');
        }
    }

    public function serviceInvoice($invoiceNo)
    {
        $warehouses = Warehouse::where('status', 1)->get();
        $products = Product::where('is_saleable', 1)->where('status', 1)->get();
        $units = ProductUnit::get();
        $customerAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', '100020001')->get();
        $toAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', 'like', '10002%')->whereNotIn('account_group_code', ['100020001'])->get();
        $customerTypes = CustomerType::where('status', 1)->pluck('type_name', 'id')->all();

        // $stocks = Stock::with(['product', 'unit', 'customer_finance_account'])->where('invoice_no', $invoiceNo)->get();

        $stocks = Stock::with(['product', 'unit', 'customer_finance_account'])
            ->where('invoice_no', $invoiceNo)
            ->whereHas('product', function ($query) {
                $query->where('is_serviceable', 1);
            })
            ->get();


        // dd($stocks);


        $customerFinance = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $stocks[0]->customer_id)->select('voucher_no', 'delivery_challan_no', 'voucher_date')->first();
        $customerPayment = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $stocks[0]->customer_id)->where('balance_type', 'Cr')->select('amount', 'to_acc_name')->first();

        return view('pages.product.product_services.service_assign', compact('warehouses', 'invoiceNo', 'products', 'units', 'customerAccounts', 'toAccounts', 'customerTypes', 'stocks', 'customerPayment', 'customerFinance'));
    }

    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'invoice_no' => 'required|string',
            'customer_id' => 'required|integer',
            'product_id' => 'required|array',
            'service_location' => 'required|array',
            'service_description' => 'required|array',
            'service_type' => 'required|array',
            'service_start_date' => 'required|array',
            'service_end_date' => 'required|array',
            'service_quantity' => 'required|array',
            'service_date.*' => 'required|array',  // Validate nested service dates
        ]);

        // Iterate over each product in the form data
        foreach ($request->product_id as $index => $productId) {
            // Create a new ProductService instance
            $productService = new ProductService();
            $productService->invoice_no = $request->invoice_no;
            $productService->customer_id = $request->customer_id;
            $productService->product_id = $productId;
            $productService->service_location = $request->service_location[$index];
            $productService->service_description = $request->service_description[$index];
            $productService->service_type = $request->service_type[$index];
            $productService->service_start_date = $request->service_start_date[$index];
            $productService->service_end_date = $request->service_end_date[$index];
            $productService->service_quantity = $request->service_quantity[$index];
            $productService->done_by = Auth::user()->name;

            // Save ProductService to get an ID for related service dates
            $productService->save();

            // Handle the multiple service dates for this product
            if (isset($request->service_date[$index])) {
                $serviceNumber = 1;  // Initialize service number counter

                foreach ($request->service_date[$index] as $serviceDate) {
                    // Create a ProductServiceDetail with an incremented service_number for each date
                    ProductServiceDetail::create([
                        'product_service_id' => $productService->id,
                        'service_date' => $serviceDate,
                        'service_number' => $serviceNumber,  // Incremented service number
                        'service_status' => 0,
                        'done_by' => Auth::user()->name,
                    ]);

                    $serviceNumber++;  // Increment service number for the next date
                }
            }
        }

        // Redirect or return success response
        return redirect()->route('product_services')->with('success', 'Services assigned successfully.');
    }
    #endregion


    public function index() {}

    public function create() {}

    public function show(ProductService $productService) {}

    public function edit(ProductService $productService) {}

    public function update(Request $request, ProductService $productService) {}

    public function destroy(ProductService $productService) {}
}
