<?php
namespace App\Http\Controllers\Production;
use App\Http\Controllers\Controller;

use App\Models\CompanySetting;
use App\Models\Requisition;
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

class RequisitionController extends Controller
{
    function __construct() {
        $this->middleware('permission:read requisition|write requisition|create requisition', ['only' => ['index', 'show']]);
        $this->middleware('permission:create requisition', ['only' => ['create', 'store']]);
        $this->middleware('permission:write requisition', ['only' => ['edit', 'update', 'destroy']]);

        $this->middleware('permission:read requisition list', ['only' => ['requisitionList', 'requisitionInvoiceReportSearch', 'requisitionInvoiceReportPdf']]);
    }

    // requisitionInvoiceReportPdf
    public function requisitionInvoiceReportPdf($invoices) {
        $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        $data['company_name'] = $companySetting->company_name;
        $data['company_logo_one'] = $companySetting->company_logo_one;
        $data['title'] = 'Testing Page Number In Body';
        // production
        $productionIn = Requisition::with(['product'])->where('invoice_no', $invoices)->where('stock_type', "ProductionIn")->get();
        $byProductIn = Requisition::with(['product'])->where('invoice_no', $invoices)->where('stock_type', "ByProductIn")->get();
        // $consumeOut = Requisition::with(['product'])->where('invoice_no', $invoices)->where('stock_type', "ConsumeOut")->get();
        $consumeOut = Requisition::with(['product'])
                ->select('product_id', DB::raw('sum(stock_out_quantity) as total_quantity'), DB::raw('sum(stock_out_quantity * stock_out_unit_price) as total_price'))
                ->where('invoice_no', $invoices)
                ->where('stock_type', 'ConsumeOut')
                ->groupBy('product_id')
                ->get();
        $pdf = PDF::loadView('pages.pdf.requisition_report_pdf', array('invoices' => $invoices, 'productionIn' => $productionIn, 'byProductIn' => $byProductIn, 'consumeOut' => $consumeOut, 'data' => $data));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('-requisition_report_pdf.pdf');
    }

    // requisitionInvoiceReportSearch View
    public function requisitionInvoiceReportSearch($invoiceNo) {
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');
        // production
        $productionIn = Requisition::with(['product'])->where('invoice_no', $invoiceNo)->where('stock_type', "ProductionIn")->get();
        $byProductIn = Requisition::with(['product'])->where('invoice_no', $invoiceNo)->where('stock_type', "ByProductIn")->get();

        $consumeOut = Requisition::with(['product'])
                ->select('product_id', DB::raw('sum(stock_out_quantity) as total_quantity'), DB::raw('sum(stock_out_quantity * stock_out_unit_price) as total_price'))
                ->where('invoice_no', $invoiceNo)
                ->where('stock_type', 'ConsumeOut')
                ->groupBy('product_id')
                ->get();

        return view('pages.production.requisitions.requisitions_list_view', compact('productionIn', 'byProductIn', 'consumeOut', 'startDate', 'endDate'));
    }
    // requisitionInvoiceReport Status Approved
    public function requisitionInvoiceReportStatusApproved($invoiceNo, $statusValueApproved)
    {
        // Retrieve all requisitions with the given invoice number
        $requisitionInvoices = Requisition::where('invoice_no', $invoiceNo)->get();
        // $approved_level = Requisition::selecte('approved_level')->where('invoice_no', $invoiceNo)->first();

        // Update the status for all retrieved requisitions
        foreach ($requisitionInvoices as $requisition) {
            $requisition->status = $statusValueApproved;
            $requisition->save();
        }
        
        // Final Requisition Invoice Report Stock Out
        if ($statusValueApproved == 3) {
            $requisitionProductionIn = Requisition::where('invoice_no', $invoiceNo)->where('stock_type', 'ProductionIn')->get();
            $requisitionConsumeOut = Requisition::where('invoice_no', $invoiceNo)->where('stock_type', 'ConsumeOut')->get();

            $productionDate = Carbon::now();
            $done_by = Auth::user()->name;

            foreach ($requisitionProductionIn as $requisition) {
                $productionIn = new Stock();
                $productionIn->stock_type = 'ProductionIn';
                $productionIn->stock_date = $productionDate;
                $productionIn->product_id = $requisition->product_id;
                $productionIn->stock_out_quantity = 0;
                $productionIn->stock_in_quantity = $requisition->stock_in_quantity;
                $productionIn->stock_in_unit_price = $requisition->stock_in_unit_price;
                $productionIn->stock_in_total_amount = $requisition->stock_in_total_amount;
                $productionIn->invoice_no = $invoiceNo;
                $productionIn->done_by = $done_by;

                $productionIn->save();
            }
            foreach ($requisitionConsumeOut as $requisition) {
                $consumeOut = new Stock();
                $consumeOut->stock_type = 'ConsumeOut';
                $consumeOut->stock_date = $productionDate;
                $consumeOut->product_id = $requisition->product_id;
                $consumeOut->stock_in_quantity = 0;
                $consumeOut->stock_out_quantity = $requisition->stock_out_quantity;
                $consumeOut->stock_out_unit_price = $requisition->stock_out_unit_price;
                $consumeOut->stock_out_total_amount = $requisition->stock_out_total_amount;
                $consumeOut->invoice_no = $invoiceNo;
                $consumeOut->done_by = $done_by;

                $consumeOut->save();
            }
        }

        return back()->with([
            'message' => 'Status updated successfully for all requisitions!',
            'alert-type' => 'success'
        ]);
    }

    // report list
    public function requisitionList() {
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');
        $productionIn = NULL;
        return view('pages.production.requisitions.requisitions_list', compact('productionIn', 'startDate', 'endDate'));
    }
    // Date Between Search invoice list
    public function invoiceDateSearch(Request $request) {
         // Fetch the authenticated user's approved_index
        $approvedIndex = auth()->user()->approved_index;

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        // Invoice Type
        $inv = 'REQ';
        $invoices = Requisition::where(function ($query) use ($startDate, $endDate) {
                if ($startDate && $endDate && $startDate !== '0' && $endDate !== '0') {
                    $query->whereRaw("DATE(stock_date) BETWEEN '$startDate' AND '$endDate'");
                }
            })
            ->where('invoice_no', 'LIKE', '%' . $inv . '%')
            ->where('status', '<', 3)
            ->orderBy('id', 'DESC')
            ->get()
            ->groupBy('invoice_no');

        // return response()->json($invoices);

        // Create the response data
        $response = [
            'approved_index' => $approvedIndex,
            'invoices' => $invoices
        ];

        // Return the response as JSON
        return response()->json($response);
    }

    // report Approved list
    public function requisitionApprovedList() {
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');
        $productionIn = NULL;
        return view('pages.production.requisitions.requisitions_approved_list', compact('productionIn', 'startDate', 'endDate'));
    }
    // Date Between Search invoice list
    public function invoiceApprovedDateSearch(Request $request) {
         // Fetch the authenticated user's approved_index
        $approvedIndex = auth()->user()->approved_index;

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        // Invoice Type
        $inv = 'REQ';
        $invoices = Requisition::where(function ($query) use ($startDate, $endDate) {
                if ($startDate && $endDate && $startDate !== '0' && $endDate !== '0') {
                    $query->whereRaw("DATE(stock_date) BETWEEN '$startDate' AND '$endDate'");
                }
            })
            ->where('invoice_no', 'LIKE', '%' . $inv . '%')
            ->where('status', 3)
            ->orderBy('id', 'DESC')
            ->get()
            ->groupBy('invoice_no');

        // return response()->json($invoices);

        // Create the response data
        $response = [
            'approved_index' => $approvedIndex,
            'invoices' => $invoices
        ];

        // Return the response as JSON
        return response()->json($response);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produceable_products = Product::has('productions')->get();
        $consumable_products = Product::where('is_consumable', '=', 1)->get();
        $wastage_products = Product::get();
        $units = ProductUnit::get();
        return view('pages.production.requisitions.requisitions_add', compact('produceable_products', 'consumable_products', 'wastage_products', 'units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
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
            $productionIn = new Requisition();
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
            $consumeOut = new Requisition();
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

    /**
     * Display the specified resource.
     */
    public function show(Requisition $requisition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Requisition $requisition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Requisition $requisition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Requisition $requisition)
    {
        //
    }
}
