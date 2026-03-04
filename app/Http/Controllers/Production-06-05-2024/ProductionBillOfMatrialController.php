<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Production;
use App\Models\ProductionBillOfMatrial;
use App\Models\ProductUnit;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use Auth;

class ProductionBillOfMatrialController extends Controller {

    function __construct()
    {
         $this->middleware('permission:read billofmaterials|write billofmaterials|create billofmaterials', ['only' => ['index','show']]);
         $this->middleware('permission:create billofmaterials', ['only' => ['create','store']]);
         $this->middleware('permission:write billofmaterials', ['only' => ['edit','update','destroy']]);
    }
    
    // Product Details
    public function productDetails($id)
    {
        $productDetails = Product::select('products.id', 'products.unit_id', 'products.purchase_price', 'products.sales_price')
        ->with('unit')
        ->where('id', $id)
        ->get();
        return response()->json($productDetails);

        // stock controller
        // $productDetails = Product::select('products.id', 'products.unit_id', 'products.purchase_price', 'products.sales_price')
        //         ->with('unit')
        //         ->withSum('productStock', 'stock_in_quantity') // "product_stock_sum_stock_in_quantity"
        //         ->withSum('productStock', 'stock_out_quantity') // "product_stock_sum_stock_out_quantity"
        //         ->where('id', $id)
        //         ->get();
        // return response()->json($productDetails);
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

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
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

            return redirect()->route('billofmaterials.index')->with([
                'message' => 'successfully updated !',
                'alert-type' => 'info'
            ]);
            
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
                'message' => 'successfully updated !',
                'alert-type' => 'info'
            ]);
        }
    }
    public function show($id) { 

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
            'message' => 'successfully updated !',
            'alert-type' => 'info'
        ]);
    }
    public function fileDestroy($id)
    {
        $fileDestroy = ProductionBillOfMatrial::find($id);
        // dd($fileDestroy);
        $fileDestroy->delete();
        return redirect()->route('billofmaterials.edit', $fileDestroy->production_id)->with([
            'message' => 'successfully deleted. !',
            'alert-type' => 'danger'
        ]);
    }
    
    public function destroy(Product $product) {
        
    }
}
