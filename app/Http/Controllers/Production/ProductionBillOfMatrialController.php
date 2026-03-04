<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Production;
use App\Models\ProductionBillOfMatrial;
use App\Models\ProductUnit;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductionBillOfMatrialController extends Controller {

    function __construct()
    {
         $this->middleware('permission:read billofmaterials|write billofmaterials|create billofmaterials', ['only' => ['index','show']]);
         $this->middleware('permission:create billofmaterials', ['only' => ['create','store']]);
         $this->middleware('permission:write billofmaterials', ['only' => ['edit','update','destroy']]);
    }
    
    // Product Details
    public function billOfMatrialProductDetails($id)
    {
        $productDetails = Product::select('products.id', 'products.unit_id', 'products.purchase_price', 'products.sales_price')
        ->with('unit')
        ->where('id', $id)
        ->get();
        return response()->json($productDetails);
    }

    public function index() {
        $productions = Production::with('unit', 'product')->get();
        // dd($productions);
        return view('pages.production.bill_of_materials.index', compact('productions'));

        //  $produceable_products = Product::with(['productions'])
        //  ->where('is_produceable', '=', 1)->get();
        //  $consumable_products = Product::where('is_consumable', '=', 1)->get();
        //  $wastage_products = Product::get();
        //  $units = ProductUnit::get();
        //  return view('pages.production.bill_of_materials._bill_of_material-add', compact('produceable_products', 'consumable_products', 'wastage_products', 'units'));
    }

    public function create() {
        $produceable_products = Product::with(['productions'])
        ->where('is_produceable', '=', 1)->get();
        $consumable_products = Product::where('is_consumable', '=', 1)->get();
        $wastage_products = Product::get();
        $units = ProductUnit::get();
        return view('pages.production.bill_of_materials._bill_of_material-add', compact('produceable_products', 'consumable_products', 'wastage_products', 'units'));
    }

    public function store(Request $request) {
        $produced_quantity = $request->input('produced_quantity');
        if($produced_quantity){
            $validator = Validator::make($request->all(), [
                'produced_product_id' => 'required',
                'produced_quantity' => 'required',
            ]);

            // if ($validator->fails()) {
            //     return back()->withErrors($validator)->withInput();
            // }

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $produced_product_id = $request->input('produced_product_id');
            $produced_quantity = $request->input('produced_quantity');
            $produced_unit_id = $request->input('produced_unit_id');
            $done_by = Auth::user()->first_name.' '.Auth::user()->last_name;
            $Production = Production::create([
                        'product_id' => $produced_product_id,
                        'quantity' => $produced_quantity,
                        'unit_id' => $produced_unit_id,
                        'done_by' => $done_by,
            ]);

            $consume_product_id = $request->input('consume_product_id');
            if($consume_product_id){
                foreach ($request->get('consume_product_id') as $key => $productId) {
                    $productionBillOfMatrial = new ProductionBillOfMatrial();
                    $consume_product_quantity = $request->input('consume_product_quantity')[$key];
                    $consume_product_unit = $request->input('consume_product_unit')[$key];
                    $productionBillOfMatrial->production_id = $Production->id;
                    $productionBillOfMatrial->product_id = $request->input('consume_product_id')[$key];
                    $productionBillOfMatrial->quantity = $consume_product_quantity;
                    $productionBillOfMatrial->product_unit = $consume_product_unit;
                    $productionBillOfMatrial->type = 1;
                    $productionBillOfMatrial->done_by = Auth::user()->first_name.' '.Auth::user()->last_name;
                    $productionBillOfMatrial->save();
                }
            }
            
            $wastage_product_id = $request->input('wastage_product_id');
            if($wastage_product_id){
                foreach ($request->get('wastage_product_id') as $key => $productId) {
                    $productionBillOfMatrial = new ProductionBillOfMatrial();
                    $wastage_product_quantity = $request->input('wastage_product_quantity')[$key];
                    $wastage_product_unit = $request->input('wastage_product_unit')[$key];
                    $productionBillOfMatrial->production_id = $Production->id;
                    $productionBillOfMatrial->product_id = $request->input('wastage_product_id')[$key];
                    $productionBillOfMatrial->quantity = $wastage_product_quantity;
                    $productionBillOfMatrial->product_unit = $wastage_product_unit;
                    $productionBillOfMatrial->type = 2;
                    $productionBillOfMatrial->done_by = Auth::user()->first_name.' '.Auth::user()->last_name;
                    $productionBillOfMatrial->save();
                }
            }

            // return redirect()->route('billofmaterials.index')->with([
            //     'message' => 'successfully add !',
            //     'alert-type' => 'success'
            // ]);
            return redirect()->route('billofmaterials.index')->with(['message' => 'Bill of Materials has been saved successfully.', 'alert-type' => 'success']);
        }else{
        //    $input = $request->all();
        //    dd($input);
            $production_id = $request->input('production_id');
            $productionBillOfMatrial = new ProductionBillOfMatrial();
            $productionBillOfMatrial->production_id = $production_id;
            $productionBillOfMatrial->product_id = $request->input('product_id');
            $productionBillOfMatrial->product_unit = $request->input('product_unit');
            $productionBillOfMatrial->quantity = $request->input('quantity');
            $productionBillOfMatrial->type = $request->input('type');
            $productionBillOfMatrial->done_by = Auth::user()->first_name.' '.Auth::user()->last_name;
            $productionBillOfMatrial->save();
            return redirect()->route('billofmaterials.edit', $production_id)->with([
                'message' => 'Requisition item has been added successfully!',
                'alert-type' => 'success'
            ]);
        }
    }
    public function billOfMaterialsPdf($id) { 
        $productionIn=Production::find($id);
        $consumeDetails = $productionIn->productionDetails()->with('product', 'unit')->where('type', 1)->get();
        $wastageDetails = $productionIn->productionDetails()->with('product', 'unit')->where('type', 2)->get();

        $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        $data['company_name'] = $companySetting->company_name;
        $data['company_address'] = $companySetting->company_address;
        $data['company_logo_one'] = $companySetting->company_logo_one;

        $pdf = PDF::loadView('pages.pdf.billOfMaterials_pdf', array('productionIn' => $productionIn, 'consumeDetails' => $consumeDetails, 'wastageDetails' => $wastageDetails, 'data' => $data));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('-finalproduction_report.pdf');
    }

    public function show($id) { 
        $production=Production::find($id);
        $consumeDetails = $production->productionDetails()->with('product', 'unit')->where('type', 1)->get();
        $wastageDetails = $production->productionDetails()->with('product', 'unit')->where('type', 2)->get();
        return view('pages.production.bill_of_materials._bill_of_material_view', compact('production', 'consumeDetails', 'wastageDetails'));
    }
    public function edit($id) {
        $production=Production::find($id);
        $consumeDetails = $production->productionDetails()->with('product', 'unit')->where('type', 1)->get();
        $wastageDetails = $production->productionDetails()->with('product', 'unit')->where('type', 2)->get();
        // dd($consumeDetails);
        $consumable_products = Product::where('is_consumable', '=', 1)->get();
        $wastage_products = Product::get();
        return view('pages.production.bill_of_materials._bill_of_material-update', compact('production', 'consumeDetails', 'wastageDetails', 'consumable_products', 'wastage_products'));
    }
    public function update(Request $request, ProductionBillOfMatrial $productionBillOfMatrial) {
        // $input = $request->all();
        // dd($input);
        foreach ($request->get('id') as $key => $id) {
            $quantity = $request->input('quantity')[$key];
            ProductionBillOfMatrial::where('id', $id)->first()->update([
                'quantity' => $quantity,
            ]);
        }
        $production_id = $request->input('production_id');
        return redirect()->route('billofmaterials.edit', $production_id)->with([
            'message' => 'Requisition all item record has been updated successfully!',
            'alert-type' => 'info'
        ]);
    }
    
    public function fileDestroy($id)
    {
        $fileDestroy = ProductionBillOfMatrial::find($id);
        if (!$fileDestroy) {
            return redirect()->back()->with([
                'message' => 'Record not found!',
                'alert-type' => 'error'
            ]);
        }
        $fileDestroy->delete();
        return redirect()->route('billofmaterials.edit', $fileDestroy->production_id)->with([
            'message' => 'Requisition item record has been deleted successfully!',
            'alert-type' => 'danger'
        ]);
    }

    public function destroy(Product $product) {
        
    }
}
