<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Warehouse;
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
    public function warehouseWiseProductDetails($id, $warehouseId)
    {
        // Fetch the product details with present stock calculation
        $productDetails = DB::table('products as p')
        ->join('product_units as pu', 'pu.id', '=', 'p.unit_id')
        ->select(
            'p.id as product_id',
            'p.product_name',
            'p.product_code',
            'p.purchase_price',
            'p.sales_price',
            'p.pack_size',
            'pu.unit_name',
            DB::raw("
                (
                    SELECT 
                        COALESCE(SUM(s.stock_in_quantity), 0) - COALESCE(SUM(s.stock_out_quantity), 0)
                    FROM stocks as s
                    WHERE s.product_id = p.id AND s.warehouse_id = $warehouseId
                ) as present_stock
            ")
        )
        ->where('p.id', $id)
        ->first();
        return response()->json($productDetails);
    }
    // Product Details
    public function productDetails($id)
    {
        $productDetails = Product::select('products.id', 'products.product_name', 'products.product_code', 'products.unit_id', 'products.purchase_price', 'products.sales_price', 'products.pack_size')
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

    // stock_report_material_wise
    public function stockReportMaterialWise()
    {
        $warehouses = Warehouse::where('status', 1)->get();
        $products = Product::where('status', 1)->get();
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');
        return view('pages.product.stock.stock_report_material_wise', compact('products', 'warehouses','startDate', 'endDate'));
    }
    public function stockReportMaterialWiseSearch($startDate, $endDate, $warehouseId, $productId, $pdf)
    {
        // $stockReportMaterialWise = DB::connection()->select("CALL sp_StockReportMaterialWise(?, ?)", array($startDate, $endDate));
        $stockReportMaterialWise = DB::connection()->select("CALL sp_StockReportMaterialWise(?, ?, ?, ?)", array($startDate, $endDate, $warehouseId, $productId));
        if ($pdf == "list") {
            return response()->json($stockReportMaterialWise);
        }
        if ($pdf == "pdfurl") {
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;
            $data['company_address'] = $companySetting->company_address;
            $data['company_mobile'] = $companySetting->company_mobile;
            $data['company_logo_one'] = $companySetting->company_logo_one;
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $pdf = PDF::loadView('pages.pdf.stock_report_material_wise_pdf', array('stockReportMaterialWise' => $stockReportMaterialWise, 'data' => $data));
            // $pdf->setPaper('A4', 'landscape');
            return $pdf->stream(Carbon::now() . '-recentstat.pdf');
        }
    }

    // stockReportFinishGoodWise
    public function stockReportFinishGoodWise()
    {
        $warehouses = Warehouse::where('status', 1)->get();
        $products = Product::where('status', 1)->get();
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');
        return view('pages.product.stock.stock_report_finish_good_wise', compact('warehouses', 'products', 'startDate', 'endDate'));
    }
    public function stockReportFinishGoodWiseSearch($startDate, $endDate, $warehouseId, $productId, $pdf)
    {
        // $stockReportFinishGoodWise = DB::connection()->select("CALL sp_StockReportFinishGood(?, ?, ?)", array($startDate, $endDate, $warehouseId));
        $stockReportFinishGoodWise = DB::connection()->select("CALL sp_StockReportFinishGoodWise(?, ?, ?, ?)", array($startDate, $endDate, $warehouseId, $productId));
        if ($pdf == "list") {
            return response()->json($stockReportFinishGoodWise);
        }
        if ($pdf == "pdfurl") {
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;
            $data['company_address'] = $companySetting->company_address;
            $data['company_mobile'] = $companySetting->company_mobile;
            $data['company_logo_one'] = $companySetting->company_logo_one;
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $pdf = PDF::loadView('pages.pdf.stock_report_finish_good_wise_pdf', array('stockReportFinishGoodWise' => $stockReportFinishGoodWise, 'data' => $data));
            // $pdf->setPaper('A4', 'landscape');
            return $pdf->stream(Carbon::now() . '-stock_report_finish_good_wise_pdf.pdf');
        }
    }

    // stockReportFinishGoodWise
    public function stockReportItemWise()
    {
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');
        $products = Product::where('is_saleable', 1)->where('status', 1)->pluck('product_name', 'id')->all();

        return view('pages.product.stock.stock_report_item_wise', compact('products', 'startDate', 'endDate'));
    }
    public function stockReportItemWiseSearch($startDate, $endDate, $productID, $pdf)
    {
        $stockReportItemWise = DB::connection()->select("CALL sp_StockReportItemWise(?, ?, ?)", array($startDate, $endDate, $productID)); 

        if ($pdf == "list") {
            return response()->json($stockReportItemWise);
        }
        if ($pdf == "pdfurl") {
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;
            $data['company_address'] = $companySetting->company_address;
            $data['company_mobile'] = $companySetting->company_mobile;
            $data['company_logo_one'] = $companySetting->company_logo_one;
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $pdf = PDF::loadView('pages.pdf.stock_report_item_wise_pdf', array('stockReportItemWise' => $stockReportItemWise, 'data' => $data));
            // $pdf->setPaper('A4', 'landscape');
            return $pdf->stream(Carbon::now() . '-stock_report_item_wise_pdf.pdf');
        }
    }

}
