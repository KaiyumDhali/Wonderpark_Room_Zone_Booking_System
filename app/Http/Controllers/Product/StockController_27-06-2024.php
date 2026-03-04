<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\SupplierLedger;
use App\Models\Customer;
use App\Models\CustomerLedger;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\ProductBrand;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductUnit;
use App\Models\FinanceAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;


class StockController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:read stock report|write stock report|create stock report', ['only' => ['index', 'show']]);
        $this->middleware('permission:create stock report', ['only' => ['create', 'store']]);
        $this->middleware('permission:write stock report', ['only' => ['edit', 'update', 'destroy']]);
        $this->middleware('permission:read stock report', ['only' => ['stock']]);
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

    // Product stock
    public function stock()
    {
        // $stocks = Stock::with(['product','product.category', 'product.subCategory'])->groupBy('product_id')
        //         ->selectRaw('SUM(stock_in_quantity) as stock_in_quantity , product_id')
        //         ->selectRaw('SUM(stock_out_quantity) as stock_out_quantity , product_id')
        //         ->get();
        $stocks = Stock::with(['product', 'product.category', 'product.subCategory'])->groupBy('product_id')
            ->selectRaw('SUM(stock_in_quantity) - SUM(stock_out_quantity) as total_quantity, product_id')
            ->get();
        // $stocks = Stock::with(['product','product.category', 'product.subCategory'])
        // ->withSum('productStock','stock_in_quantity')
        // ->withSum('productStock','stock_out_quantity')
        //         ->get();
        // dd($stocks);
        // $stocks = Stock::with(['product','product.category', 'product.subCategory'])->groupBy('product_id')
        // ->selectRaw('SUM(stock_in_quantity) as total_quantity , product_id')->whereHas('product', function($q){
        //     $q->where('status','=',1);
        // })->get();
        // dd($stocks);
        return view('pages.product.stock.stocks', compact('stocks'));
    }

    public function stock_pdf()
    {

        $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        $data['company_name'] = $companySetting->company_name;
        $data['company_logo_one'] = $companySetting->company_logo_one;

        $stocks = Stock::with(['product', 'product.category', 'product.subCategory'])->groupBy('product_id')
            ->selectRaw('SUM(stock_in_quantity) - SUM(stock_out_quantity) as total_quantity, product_id')
            ->get();

        // return view('pages.product.stock.stocks', compact('stocks'));

        $pdf = PDF::loadView('pages.pdf.stock_report_pdf', array('stocks' => $stocks, 'data' => $data));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream(Carbon::now() . '-stocks_report.pdf');
    }

    public function index(Request $request)
    {

    }

    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    public function show(Stock $stock)
    {

    }

    public function edit(Stock $stock)
    {

    }

    public function update(Request $request, Stock $stock)
    {

    }

    public function destroy(Stock $stock)
    {

    }


    public function stockReportDatewiseSearch($startDate, $endDate, $pdf)
    {
      
        $stockReportDatewise = DB::connection()->select("CALL sp_StockReportDatewise(?, ?)", array($startDate, $endDate));
        

        if ($pdf == "list") {
            return response()->json($stockReportDatewise);
        }
        if ($pdf == "pdfurl") {
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;
            $data['company_logo_one'] = $companySetting->company_logo_one;
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            // $data['customerInfo'] = FinanceAccount::where('id', $ac_id)->select('account_name', 'account_mobile', 'account_email', 'account_address')->first();

            $pdf = PDF::loadView('pages.pdf.stock_report_datewise_pdf', array('stockReportDatewise' => $stockReportDatewise, 'data' => $data));
            // $pdf->setPaper('A4', 'landscape');
            return $pdf->stream(Carbon::now() . '-recentstat.pdf');
        }



    }

    public function stockReportDatewise()
    {

        // $startDate='2024-06-25';
        // $endDate='2024-06-25';
        

        // $stockReportDatewise = DB::connection()->select("CALL sp_StockReportDatewise(?, ?)", array($startDate, $endDate));

        // dd($stockReportDatewise);

        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');
        return view('pages.product.stock.stocks_datewise', compact('startDate', 'endDate'));

    }


}
