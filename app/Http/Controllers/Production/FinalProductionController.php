<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Production;
use App\Models\ProductionBillOfMatrial;
use App\Models\FinalProduction;
use App\Models\ProductUnit;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;
use Validator;
use Auth;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf;

class FinalProductionController extends Controller {

    function __construct() {
        $this->middleware('permission:read finalproductions|write finalproductions|create finalproductions', ['only' => ['index', 'show']]);
        $this->middleware('permission:create finalproductions', ['only' => ['create', 'store']]);
        $this->middleware('permission:write finalproductions', ['only' => ['edit', 'update', 'destroy']]);

        $this->middleware('permission:read finalproduction report', ['only' => ['finalProductionReport', 'invoiceDateSearch', 'finalProductionReportSearch']]);
    }

    public function productionDetails($id) {
        $productDetails = Product::select('products.id', 'products.unit_id', 'products.purchase_price')
                ->with('unit')
                ->where('id', $id)
                ->first();

        $id2 = Production::where('product_id', $id)->value('id');
        $productionDetails = ProductionBillOfMatrial::with('production', 'product', 'unit')->where('production_id', $id2)->get();

        // Send JSON response
        return response()->json([
                    'productDetails' => $productDetails,
                    'productionDetails' => $productionDetails,
        ]);
    }

    public function index() {
        $produceable_products = Product::has('productions')->get();
        $consumable_products = Product::where('is_consumable', '=', 1)->get();
        $wastage_products = Product::get();
        $units = ProductUnit::get();
        return view('pages.production.final_productions._final_production-add', compact('produceable_products', 'consumable_products', 'wastage_products', 'units'));
    }

    public function store(Request $request) {
        //    $productionProductId = $request->input('production_product_id');
        //    $productionProductUnitId = $request->input('production_product_unit_id');
        //    $productionProductQuantity = $request->input('production_product_quantity');
        //    $productionProductUnitPrice = $request->input('production_product_unit_price');
        //    $productionProductTotalPrice = $request->input('production_product_total_price');
        $productionDate = Carbon::now();
        $done_by = Auth::user()->name;

        //    DB::beginTransaction();
        //         try{
        $batchNo = DB::table('invoiceno')->first('batch_no');
        $getBatchNo = $batchNo->batch_no;
        $batchNumber = 'REQ' . str_pad($getBatchNo, 6, '0', STR_PAD_LEFT);

        DB::table('invoiceno')->update([
            'batch_no' => $getBatchNo + 1,
        ]);
        // dd('done');
        // $productionIn = Stock::create([
        //     'stock_type'                   => "ProductionIn",
        //     'stock_date'                   => $productionDate,
        //     'product_id'                   => $productionProductId,
        //     'stock_in_quantity'            => $productionProductQuantity,
        //     'stock_out_quantity'           => 0,
        //     'stock_in_unit_price'          => $productionProductUnitPrice,
        //     'stock_in_total_amount'        => $productionProductTotalPrice,
        //     'invoice_no'                   => $batchNumber,
        //     'done_by'                      => $done_by,
        // ]);
        // dd($productionIn);
        foreach ($request->get('add_to_product_id') as $key => $productionProductId) {
            $productionIn = new Stock();
            $productionIn->stock_type = 'ProductionIn';
            $productionIn->stock_date = $productionDate;
            $productionIn->product_id = $request->input('add_to_product_id')[$key];
            $productionIn->stock_out_quantity = 0;
            $productionIn->stock_in_quantity = $request->input('add_to_quantity')[$key];
            $productionIn->stock_in_unit_price = $request->input('add_to_unit_cost')[$key];
            $productionIn->stock_in_total_amount = $request->input('add_to_total_price')[$key];
            $productionIn->invoice_no = $batchNumber;
            $productionIn->done_by = $done_by;

            $productionIn->save();
        }
        foreach ($request->get('add_consume_product_id') as $key => $consumeProductId) {
            $consumeOut = new Stock();
            $consumeOut->stock_type = 'ConsumeOut';
            $consumeOut->stock_date = $productionDate;
            $consumeOut->product_id = $request->input('add_consume_product_id')[$key];
            $consumeOut->stock_in_quantity = 0;
            $consumeOut->stock_out_quantity = $request->input('add_consume_product_quantity')[$key];
            $consumeOut->stock_out_unit_price = $request->input('add_consume_unit_price')[$key];
            $consumeOut->stock_out_total_amount = $request->input('add_consume_total_price')[$key];
            $consumeOut->invoice_no = $batchNumber;
            $consumeOut->done_by = $done_by;

            $consumeOut->save();
        }

        //     DB::commit();
        // }catch (\Exception $e) {
        //     DB::rollback();
        //     return back()->withErrors(['error' => 'Ops ! Could Not Add Final Production history. DB transaction lost']);
        // }
        return back()->with([
                    'message' => 'Final Production History Added Successfully!',
                    'alert-type' => 'success'
        ]);
    }

    // report 
    public function finalProductionReport() {
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');
        // Parse start and end dates using Carbon
        $startDateTimeObj = Carbon::parse($startDate);
        $endDateTimeObj = Carbon::parse($endDate);
        // Set the start date to the second of the day
        $startDateObj = $startDateTimeObj->startOfDay();
        $endDateObj = $endDateTimeObj->endOfDay();
        // Invoice Type
        $inv = 'REQ';
        $invoices = Stock::whereBetween('stock_date', [$startDateObj, $endDateObj])->where('invoice_no', 'LIKE', '%' . $inv . '%')->orderBy('id', 'DESC')->get()->groupBy('invoice_no');
        $productionIn = NULL;
        return view('pages.production.finalproduction_report.invoice_report', compact('productionIn', 'invoices', 'startDate', 'endDate'));
    }

    public function invoiceDateSearch(Request $request) {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        // Parse start and end dates using Carbon
        $startDateTimeObj = Carbon::parse($startDate);
        $endDateTimeObj = Carbon::parse($endDate);
        // Set the start date to the second of the day
        $startDateObj = $startDateTimeObj->startOfDay();
        $endDateObj = $endDateTimeObj->endOfDay();
        // Invoice Type
        $inv = 'REQ';
        $invoices = Stock::whereBetween('stock_date', [$startDateObj, $endDateObj])->where('invoice_no', 'LIKE', '%' . $inv . '%')->orderBy('id', 'DESC')->get()->groupBy('invoice_no');
        // $stocks = NULL;
        return response()->json($invoices);
    }

    public function finalProductionReportSearch(Request $request) {
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
        $inv = 'REQ';
        $invoices = Stock::whereBetween('stock_date', [$startDateObj, $endDateObj])->where('invoice_no', 'LIKE', '%' . $inv . '%')->orderBy('id', 'DESC')->get()->groupBy('invoice_no');
        // production
        $productionIn = Stock::with(['product'])->where('invoice_no', $invoice_list)->where('stock_type', "ProductionIn")->get();
        $byProductIn = Stock::with(['product'])->where('invoice_no', $invoice_list)->where('stock_type', "ByProductIn")->get();
        // dd($byProductIn);
        // $consumeOut = Stock::with(['product'])->where('invoice_no', $invoice_list)->where('stock_type', "ConsumeOut")->get();

        $consumeOut = Stock::with(['product'])
                ->select('product_id', DB::raw('sum(stock_out_quantity) as total_quantity'), DB::raw('sum(stock_out_quantity * stock_out_unit_price) as total_price'))
                ->where('invoice_no', $invoice_list)
                ->where('stock_type', 'ConsumeOut')
                ->groupBy('product_id')
                ->get();

        return view('pages.production.finalproduction_report.invoice_report', compact('invoices', 'startDate', 'endDate', 'productionIn', 'byProductIn', 'consumeOut'));
    }

    public function finalProductionReportSearchPdf($invoices) {

        $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        $data['company_name'] = $companySetting->company_name;
        $data['company_logo_one'] = $companySetting->company_logo_one;
        $data['company_address'] = $companySetting->company_address;
        $data['title'] = 'Testing Page Number In Body';

        // production
        $productionIn = Stock::with(['product'])->where('invoice_no', $invoices)->where('stock_type', "ProductionIn")->get();
        $byProductIn = Stock::with(['product'])->where('invoice_no', $invoices)->where('stock_type', "ByProductIn")->get();
        // dd($byProductIn);
        // $consumeOut = Stock::with(['product'])->where('invoice_no', $invoices)->where('stock_type', "ConsumeOut")->get();

        $consumeOut = Stock::with(['product'])
                ->select('product_id', DB::raw('sum(stock_out_quantity) as total_quantity'), DB::raw('sum(stock_out_quantity * stock_out_unit_price) as total_price'))
                ->where('invoice_no', $invoices)
                ->where('stock_type', 'ConsumeOut')
                ->groupBy('product_id')
                ->get();

        // dd($consumeOut);
        // return view('pages.production.finalproduction_report.invoice_report', compact('invoices', 'startDate', 'endDate', 'productionIn', 'byProductIn', 'consumeOut'));

        $pdf = PDF::loadView('pages.pdf.finalproduction_report_pdf', array('invoices' => $invoices, 'productionIn' => $productionIn, 'byProductIn' => $byProductIn, 'consumeOut' => $consumeOut, 'data' => $data));
        $pdf->setPaper('A4', 'portrait');

//        $font = $pdf->getFontMetrics()->get_font("helvetica", "bold");
//        for ($pageNumber = 1; $pageNumber <= $pdf->get_canvas()->get_page_number(); $pageNumber++) {
//            $pdf->get_canvas()->page_text(34, 18, $pageNumber . ' / ' . $pdf->get_canvas()->get_page_number(), $font, 10, array(0, 0, 0), $pageNumber);
//        }
        
        return $pdf->stream('-finalproduction_report.pdf');
//        return $pdf->stream('-finalproduction_report.pdf', array('Attachment' => 0));
    }

}
