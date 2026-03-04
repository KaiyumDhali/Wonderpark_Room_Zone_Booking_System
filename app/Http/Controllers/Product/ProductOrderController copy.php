<?php

namespace App\Http\Controllers\Product;
use App\Http\Controllers\Controller;
use App\Models\ProductOrder;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\Customer;

use App\Models\Supplier;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\ProductBrand;
use App\Models\ProductUnit;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class ProductOrderController extends Controller
{
    
    // All Color
    public function allColor() {
        $allColors = ProductColor::pluck('color_name')->all();
        return response()->json($allColors);
    }
    
    public function index()
    {
        // $productOrders = ProductOrder::leftJoin('suppliers', 'suppliers.id', '=', 'products.supplier_id')
        // ->leftJoin('product_categories', 'product_categories.id', '=', 'products.category_id')
        // ->leftJoin('product_sub_categories', 'product_sub_categories.id', '=', 'products.sub_category_id')
        // ->leftJoin('product_brands', 'product_brands.id', '=', 'products.brand_id')
        // ->leftJoin('product_colors', 'product_colors.id', '=', 'products.color_id')
        // ->leftJoin('product_sizes', 'product_sizes.id', '=', 'products.size_id')
        // ->leftJoin('product_units', 'product_units.id', '=', 'products.unit_id')
        // ->select('products.*', 'suppliers.supplier_name', 'product_categories.category_name', 'product_sub_categories.sub_category_name', 'product_brands.brand_name', 'product_colors.color_name', 'product_sizes.size_name', 'product_units.unit_name')
        // ->orderby('id', 'desc')->get();

        // $allColors = ProductColor::pluck('color_name')->all();
        // dd($allColors);
        $productOrders = ProductOrder::leftJoin('customers', 'customers.id', '=', 'product_orders.customer_id')
        ->select('product_orders.*', 'customers.customer_name')
        ->orderby('id', 'desc')->get();
        // $productOrders = ProductOrder::orderby('id', 'desc')->get();
        return view('pages.product.product_order.index',compact('productOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $allSuppliers = Supplier::pluck('supplier_name','id')->all();
        $allCategories = ProductCategory::pluck('category_name','id')->all();
        $allSubCategories = ProductSubCategory::pluck('sub_category_name','id')->all();
        $allBrands = ProductBrand::pluck('brand_name','id')->all();
        $allUnits = ProductUnit::pluck('unit_name','id')->all();

        $allCustomers = Customer::pluck('customer_name','id')->all();
        $allColors = ProductColor::pluck('color_name','id')->all();
        $allSizes = ProductSize::pluck('size_name','id')->all();

        return view('pages.product.product_order.create',compact('allCustomers', 'allColors', 'allSizes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $input = $request->all();

        dd($input);

        if($request->hasfile('image')) {
            $image = $request->file('image');
            $name = date('d-m-Y-H-i-s') .'_'. $image->getClientOriginalName();
            $image_path= $image->storeAs('public/images/product_order', $name);
            $input['image'] = $image_path;
        }

        // dd($input);

        ProductOrder::create($input);

        return redirect()->route('product_order.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $productOrder = ProductOrder::leftJoin('product_colors', 'product_colors.id', '=', 'product_orders.color_id')
        ->select('product_orders.*', 'product_colors.color_name')
        ->orderby('id', 'desc')->find($id);

        return view('pages.product.product_order.show',compact('productOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductOrder $productOrder)
    {
        $allColors = ProductColor::pluck('color_name','id')->all();
        return view('pages.product.product_order.edit',compact('productOrder','allColors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductOrder $productOrder)
    {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required',
            'product_name' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $input = $request->all();

        if($request->hasfile('image')) {
            $image = $request->file('image');
            $name = date('d-m-Y-H-i-s') .'_'. $image->getClientOriginalName();
            $image_path= $image->storeAs('public/images/product_order', $name);
            $input['image'] = $image_path;
        }

        // dd($input);
        $productOrder->update($input);


        return redirect()->route('product_order.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function imageDestroy($id)
     {
         $productOrder = ProductOrder::find($id);
         if($productOrder->image != Null)
         {
            Storage::delete($productOrder->image);
            $productOrder->image = Null;
            $productOrder->save();
         }
         return redirect()->route('product_order.edit', $productOrder->id)->with([
             'message' => 'Product order image storage successfully deleted. !',
             'alert-type' => 'danger'
         ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductOrder $productOrder)
    {
        //
    }
}
