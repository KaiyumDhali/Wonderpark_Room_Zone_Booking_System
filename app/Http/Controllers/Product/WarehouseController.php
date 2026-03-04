<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Validator;
use Auth;

class WarehouseController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:read warehouse|write warehouse|create warehouse', ['only' => ['index', 'show']]);
        $this->middleware('permission:create warehouse', ['only' => ['create', 'store']]);
        $this->middleware('permission:write warehouse', ['only' => ['edit', 'update', 'destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function warehouseWiseProductList($warehouseId)
    {
        $stockType = 'in';
        $products = DB::table('stocks as s')
            ->join('products as p', 'p.id', '=', 's.product_id') // Corrected the join condition
            ->select('s.product_id', 'p.product_name', 'p.product_code')
            ->where('s.warehouse_id', $warehouseId)
            ->where('s.stock_type', $stockType)
            ->groupBy('s.product_id', 'p.product_name', 'p.product_code') // Include all selected columns in GROUP BY
            ->get();

        return response()->json($products);
    }

    public function stockTransfer()
    {
        $warehouses = Warehouse::where('status', 1)->get();
        return view('pages.product.warehouse.stock_transfer', compact('warehouses'));
    }

    public function warehouseTransferStore(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'voucher_date' => 'required',
            'from_warehouse_id' => 'required',
            'to_warehouse_id' => 'required',
            'table_product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $voucher_date = $request->input('voucher_date');
        $from_warehouse_id = $request->input('from_warehouse_id');
        $to_warehouse_id = $request->input('to_warehouse_id');
        $remarks = $request->input('remarks');
        $done_by = Auth::user()->name;

        DB::beginTransaction();
        try {

            // get transferInvoice
            $transferNo = DB::table('invoiceno')->first('transfer_no');
            $getTransferNo = $transferNo->transfer_no;
            $transferInvoice = 'TRN' . str_pad($getTransferNo, 6, '0', STR_PAD_LEFT);
            DB::table('invoiceno')->update([
                'transfer_no' => $getTransferNo + 1,
            ]);

            // get delivery_challan_no
            $deliveryChallanNo = DB::table('invoiceno')->first('delivery_challan_no');
            $getDeliveryChallanNo = $deliveryChallanNo->delivery_challan_no;
            $deliveryChallanNumber = 'CHA' . str_pad($getDeliveryChallanNo, 6, '0', STR_PAD_LEFT);
            DB::table('invoiceno')->update([
                'delivery_challan_no' => $getDeliveryChallanNo + 1,
            ]);

            foreach ($request->get('table_product_id') as $key => $productId) {
                $stockQuantity = $request->input('table_product_quantity')[$key];
                $stockPrice = $request->input('table_product_price')[$key];
                $stockPurchasePrice = $request->input('table_purchase_price')[$key];
                $stockDiscount = 0;
                $stockTotal = $request->input('table_product_cart_amount')[$key];

                $product = Product::find($productId);
                $productName = $product ? $product->product_name : 'Unknown Product';

                // Only update the sales_price for the specific product if it's different
                if ($product && $stockPrice != $product->sales_price) {
                    DB::table('products')
                        ->where('id', $product->id) // Target the specific product by ID
                        ->update(['sales_price' => $stockPrice]);
                }
                $stockTransferOut = new Stock();
                $stockTransferOut->warehouse_id = $from_warehouse_id;
                $stockTransferOut->to_warehouse_id = $to_warehouse_id;
                $stockTransferOut->stock_date = $voucher_date;
                $stockTransferOut->stock_type = 'Out';
                $stockTransferOut->delivery_challan_no = $deliveryChallanNumber;
                $stockTransferOut->invoice_no = $transferInvoice;
                $stockTransferOut->product_id = $productId;
                $stockTransferOut->purchase_price = $stockPurchasePrice;
                $stockTransferOut->stock_in_quantity = 0;
                $stockTransferOut->stock_out_quantity = $stockQuantity;
                $stockTransferOut->stock_out_unit_price = $stockPrice;
                $stockTransferOut->stock_out_discount = $stockDiscount;
                $stockTransferOut->stock_out_total_amount = $stockTotal;
                $stockTransferOut->remarks = $remarks;
                $stockTransferOut->status = 0;
                $stockTransferOut->done_by = $done_by;
                $stockTransferOut->save();
            }

            // foreach ($request->get('table_product_id') as $key => $productId) {
            //     $stockQuantity = $request->input('table_product_quantity')[$key];
            //     $stockPrice = $request->input('table_product_price')[$key];
            //     $stockPurchasePrice = $request->input('table_purchase_price')[$key];
            //     $stockDiscount = $request->input('table_product_discount')[$key];
            //     $stockTotal = $request->input('table_product_cart_amount')[$key];

            //     $product = Product::find($productId);
            //     $productName = $product ? $product->product_name : 'Unknown Product';

            //     // Only update the sales_price for the specific product if it's different
            //     if ($product && $stockPrice != $product->sales_price) {
            //         DB::table('products')
            //             ->where('id', $product->id) // Target the specific product by ID
            //             ->update(['sales_price' => $stockPrice]);
            //     }
            //     $stockTransferIn = new Stock();
            //     $stockTransferIn->to_warehouse_id = $to_warehouse_id;
            //     $stockTransferIn->stock_date = $voucher_date;
            //     $stockTransferIn->stock_type = 'In';
            //     $stockTransferIn->delivery_challan_no = $deliveryChallanNumber;
            //     $stockTransferIn->invoice_no = $transferInvoice;
            //     $stockTransferIn->product_id = $productId;
            //     $stockTransferIn->purchase_price = $stockPurchasePrice;
            //     $stockTransferIn->stock_out_quantity = 0;
            //     $stockTransferIn->stock_in_quantity = $stockQuantity;
            //     $stockTransferIn->stock_in_unit_price = $stockPrice;
            //     $stockTransferIn->stock_in_discount = $stockDiscount;
            //     $stockTransferIn->stock_in_total_amount = $stockTotal;
            //     $stockTransferIn->remarks = $remarks;
            //     $stockTransferIn->done_by = $done_by;
            //     $stockTransferIn->save();
            // }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Ops ! Could Not add stock transfer history. DB transaction lost']);
        }
        return back()->with([
            'message' => 'Stock Transfer History Added Successfully!',
            'alert-type' => 'success',
            'invoice' => $transferInvoice,
            'delivery_challan' => $deliveryChallanNumber,
        ]);
    }

    public function stockTransferList()
    {
        $warehouses = Warehouse::where('status', 1)->get();
        return view('pages.product.warehouse.stock_transfer_list', compact('warehouses'));
    }

    public function stockTransferListSearch($startDate, $endDate, $warehouseId, $statusId, $pdf)
    {
        // Call the stored procedure with the provided parameters
        $results = DB::select('CALL sp_GetStockTransferList(?, ?, ?, ?)', [$startDate, $endDate, $warehouseId, $statusId]);

        if ($pdf == "list") {
            return response()->json($results);
        }
        if ($pdf == "pdfurl") {
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;
            $data['company_address'] = $companySetting->company_address;
            $data['company_logo_one'] = $companySetting->company_logo_one;
            $data['company_mobile'] = $companySetting->company_mobile;

            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $pdf = PDF::loadView('pages.pdf.stock_transfer_list_pdf', array('results' => $results, 'data' => $data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream(Carbon::now() . '-stock_transfer_list_pdf.pdf');
        }
    }

    public function stockTransferInvoiceDetails($invoiceNo)
    {
        // Call the stored procedure with the provided parameters
        $results = DB::select('CALL sp_GetStockTransferDetailsByInvoice(?)', [$invoiceNo]);
        return view('pages.product.warehouse.stock_transfer_invoice_details', compact('results'));

        // if ($pdf == "list") {
        //     return response()->json($results);
        // }
        // if ($pdf == "pdfurl") {
        //     $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        //     $data['company_name'] = $companySetting->company_name;
        //     $data['company_address'] = $companySetting->company_address;
        //     $data['company_logo_one'] = $companySetting->company_logo_one;
        //     $data['company_mobile'] = $companySetting->company_mobile;

        //     $data['start_date'] = $startDate;
        //     $data['end_date'] = $endDate;
        //     $pdf = PDF::loadView('pages.pdf.stock_transfer_pending_list_pdf', array('results' => $results, 'data' => $data));
        //     $pdf->setPaper('A4', 'portrait');
        //     return $pdf->stream(Carbon::now() . '-purchase_date_invoice_wise_report_pdf.pdf');
        // }
    }

    public function invoiceWiseStockTransferIndividualApprovedStore(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'stock_id' => 'required',
            'stock_date' => 'required',
            'invoice_no' => 'required',
            'from_warehouse_id' => 'required',
            'to_warehouse_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            // 'unit_price' => 'required',
            // 'total_amount' => 'required',
            'product_status' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $from_warehouse_id = $request->input('from_warehouse_id');
        $to_warehouse_id = $request->input('to_warehouse_id');
        $stock_id = $request->input('stock_id');
        $stock_date = $request->input('stock_date');
        $invoice_no = $request->input('invoice_no');
        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity');
        $unit_price = $request->input('unit_price');
        $total_amount = $request->input('total_amount');
        $product_status = $request->input('product_status');
        // $remarks = $request->input('remarks');
        $done_by = Auth::user()->name;

        DB::beginTransaction();
        try {

            // get delivery_challan_no
            // $deliveryChallanNo = DB::table('invoiceno')->first('delivery_challan_no');
            // $getDeliveryChallanNo = $deliveryChallanNo->delivery_challan_no;
            // $deliveryChallanNumber = 'CHA' . str_pad($getDeliveryChallanNo, 6, '0', STR_PAD_LEFT);
            // DB::table('invoiceno')->update([
            //     'delivery_challan_no' => $getDeliveryChallanNo + 1,
            // ]);

            $stock = Stock::find($stock_id);
            // dd($stock->id);
            // Only update the stock_id status 0=1
            if ($stock) {
                DB::table('stocks')
                    ->where('id', $stock->id) // Target the specific stock product by ID
                    ->update(['status' => 1]);
            }

            $stockTransferIn = new Stock();
            $stockTransferIn->stock_date = $stock_date;
            $stockTransferIn->warehouse_id = $to_warehouse_id;
            $stockTransferIn->stock_type = 'In';
            // $stockTransferIn->delivery_challan_no = $deliveryChallanNumber;
            $stockTransferIn->invoice_no = $invoice_no;
            $stockTransferIn->product_id = $product_id;
            // $stockTransferIn->purchase_price = $stockPurchasePrice;
            $stockTransferIn->stock_out_quantity = 0;
            $stockTransferIn->stock_in_quantity = $quantity;
            $stockTransferIn->stock_in_unit_price = $unit_price;
            $stockTransferIn->stock_in_discount = 0; //$stockDiscount;
            $stockTransferIn->stock_in_total_amount = $total_amount;
            $stockTransferIn->status = 1;
            $stockTransferIn->done_by = $done_by;
            $stockTransferIn->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Ops ! Could Not add stock transfer history. DB transaction lost']);
        }
        return back()->with([
            'message' => 'Stock Transfer History Added Successfully!',
            'alert-type' => 'success',
            // 'invoice' => $transferInvoice,
            // 'delivery_challan' => $deliveryChallanNumber,
        ]);
    }

    public function index()
    {
        $warehouses = Warehouse::all();
        return view('pages.product.warehouse.index', compact('warehouses'));
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

    public function store(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'status' => 'required|integer',
        ]);

        // Sanitize all validated data fields by removing HTML tags
        $sanitizedData = array_map('strip_tags', $validatedData);

        // Add the user who performed the action
        $sanitizedData['done_by'] = Auth::user()->name;

        // Store the sanitized data in the warehouse
        Warehouse::create($sanitizedData);

        // Redirect back with a success message
        return redirect()->back()->with([
            'message' => 'Successfully created!',
            'alert-type' => 'success',
        ]);
    }



    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'status' => 'required|integer',
        ]);

        // Sanitize the input data by removing HTML tags
        $sanitizedData = array_map('strip_tags', $validatedData);

        // Update the warehouse with the sanitized data
        $warehouse->update($sanitizedData);

        // Redirect back with a success message
        return redirect()->back()->with([
            'message' => 'Warehouse updated successfully!',
            'alert-type' => 'success',
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        // Delete the warehouse record
        $warehouse->delete();

        // Redirect back with a success message
        return redirect()->back()->with([
            'message' => 'Warehouse deleted successfully!',
            'alert-type' => 'danger',
        ]);
    }
}
