<?php

namespace App\Http\Controllers\Production;
use App\Http\Controllers\Controller;
use App\Models\ProductOrder;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\Customer;
use App\Models\ProductOrderDetail;
use App\Models\ProductOrderDetailsChain;

use App\Models\Supplier;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\ProductBrand;
use App\Models\ProductUnit;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Support\Facades\DB;

class ProductOrderController extends Controller
{
    
    function __construct()
    {
         $this->middleware('permission:read product order|write product order|create product order', ['only' => ['index','show']]);
         $this->middleware('permission:create product order', ['only' => ['create','store']]);
         $this->middleware('permission:write product order', ['only' => ['edit','update','destroy']]);
    }

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
        return view('pages.production.product_order.index',compact('productOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $allSuppliers = Supplier::pluck('supplier_name','id')->all();
        // $allCategories = ProductCategory::pluck('category_name','id')->all();
        // $allSubCategories = ProductSubCategory::pluck('sub_category_name','id')->all();
        // $allBrands = ProductBrand::pluck('brand_name','id')->all();
        // $allUnits = ProductUnit::pluck('unit_name','id')->all();

        $allCustomers = Customer::pluck('customer_name','id')->all();
        $allColors = ProductColor::pluck('color_name','id')->all();
        $allSizes = ProductSize::pluck('size_name','id')->all();

        return view('pages.production.product_order.create',compact('allCustomers', 'allColors', 'allSizes'));
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

        $customer_id = $request->input('customer_id');
        $order_category = $request->input('order_category');
        $order_type = $request->input('order_type');
        $order_number = $request->input('order_number');
        $manufacturer_article_no = $request->input('manufacturer_article_no');
        $customer_article_no = $request->input('customer_article_no');
        $last_no = $request->input('last_no');
        $order_date = $request->input('order_date');
        $delivery_date = $request->input('delivery_date');
        $description = $request->input('description');
        $remarks = $request->input('remarks');
        $status = $request->input('status');
        // $ledgerDate = Carbon::now();
        // $done_by = Auth::user()->first_name . ' ' . Auth::user()->last_name;


        //  $input = $request->all();
        //  dd($input);
        // DB::beginTransaction();
        // try {

            // $purchaseNo = DB::table('invoiceno')->first('purchase_no');
            // $getPurchaseNo = $purchaseNo->purchase_no;
            // $purchaseNumber = 'PUR' . str_pad($getPurchaseNo, 6, '0', STR_PAD_LEFT);
           
            // DB::table('invoiceno')->update([
            //     'purchase_no' => $getPurchaseNo + 1,
            // ]);
            //dd('done');
            $productOrder = ProductOrder::create([
                        'customer_id' => $customer_id,
                        'order_category' => $order_category,
                        'order_type' => $order_type,
                        'order_number' => $order_number,
                        'manufacturer_article_no' => $manufacturer_article_no,
                        'customer_article_no' => $customer_article_no,
                        'last_no' => $last_no,
                        'order_date' => $order_date,
                        'delivery_date' => $delivery_date,
                        'description' => $description,
                        'remarks' => $remarks,
                        'status' => $status,
            ]);
            //dd('done');

            foreach ($request->get('kt_docs_repeater_nested_outer') as $key => $outer) {
                $productOrderDetail = new ProductOrderDetail();
                // dd($outer);

                if ($request->hasFile('kt_docs_repeater_nested_outer.' . $key . '.image')) {
                    $image = $request->file('kt_docs_repeater_nested_outer.' . $key . '.image');
                    $name = date('d-m-Y-H-i-s') . '_' . $image->getClientOriginalName();
                    $image_path = $image->storeAs('public/images/product_order', $name);
                    $productOrderDetail->image = $image_path;
                }

                // $productOrderDetail->product_order_id = 1;
                $productOrderDetail->product_order_id = $productOrder->id;
                // $productOrderDetail->color = $outer["color"];

                $jsonString = $outer["color"];
                // Decode the JSON string and extract the color values
                $decodedData = json_decode($jsonString);

                if ($decodedData) {
                    $colors = array();
                    foreach ($decodedData as $item) {
                        $colors[] = $item->value;
                    }
                    // Convert the collected colors into a comma-separated string
                    $colorString = implode(', ', $colors);
                    // Output the color string
                    // echo $colorString;
                    $productOrderDetail->color = $colorString;

                } else {
                    echo "Invalid JSON data.";
                }
                // dd($productOrderDetail);

                $productOrderDetail->save();

                foreach ($outer["kt_docs_repeater_nested_inner"] as $key => $inner) {
                    $productOrderDetailsChain = new ProductOrderDetailsChain();
            
                    $productOrderDetailsChain->product_order_details_id  = $productOrderDetail->id;
                    $productOrderDetailsChain->size_id = $inner['size_id'];
                    $productOrderDetailsChain->quantity = $inner['quantity'];
                    $productOrderDetailsChain->unit_price =$inner['unit_price'];
                    $productOrderDetailsChain->total_price = $inner['total_price'];
                    $productOrderDetailsChain->save();

                }
            }

        //     DB::commit();
        //     // dd('done');

        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return back()->withErrors(['error' => 'Ops ! Could not add order. DB transaction lost']);
        // }
        return redirect()->route('product_order.index')->with([
                    'message' => 'Order Added Successfully!',
                    'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {

        $productOrder = ProductOrder::leftJoin('customers', 'customers.id', '=', 'product_orders.customer_id')
        ->select('product_orders.*', 'customers.customer_name')->find($id);

        $productOrderDetails = ProductOrderDetail::where('product_order_id', $id)
        ->orderby('id', 'desc')->get();

        if($productOrderDetails){
            foreach ($productOrderDetails as $key => $productOrderDetail) {
                $productOrderDetailID = $productOrderDetail["id"];

                $productOrderDetailsChains = ProductOrderDetailsChain::leftJoin('product_sizes', 'product_sizes.id', '=', 'product_order_details_chains.size_id')
                                            ->select('product_order_details_chains.*', 'product_sizes.size_name')
                                            ->where('product_order_details_id', $productOrderDetailID)
                                            ->orderBy('id', 'desc')
                                            ->get();
            }
        }
        
        return view('pages.production.product_order.show',compact('productOrder', 'productOrderDetails', 'productOrderDetailsChains'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $productOrder = ProductOrder::leftJoin('customers', 'customers.id', '=', 'product_orders.customer_id')
        ->select('product_orders.*', 'customers.customer_name')->find($id);

        $productOrderDetails = ProductOrderDetail::where('product_order_id', $id)
        ->orderby('id', 'desc')->get();

        if($productOrderDetails){
            foreach ($productOrderDetails as $key => $productOrderDetail) {
                $productOrderDetailID = $productOrderDetail["id"];

                $productOrderDetailsChains = ProductOrderDetailsChain::leftJoin('product_sizes', 'product_sizes.id', '=', 'product_order_details_chains.size_id')
                                            ->select('product_order_details_chains.*', 'product_sizes.size_name')
                                            ->where('product_order_details_id', $productOrderDetailID)
                                            ->orderBy('id', 'desc')
                                            ->get();
            }
        }


        $allCustomers = Customer::pluck('customer_name','id')->all();
        $allColors = ProductColor::pluck('color_name','id')->all();
        $allSizes = ProductSize::pluck('size_name','id')->all();
        // return view('pages.production.product_order.edit',compact('productOrder','allColors'));
        return view('pages.production.product_order.edit',compact('allCustomers', 'allColors', 'allSizes', 'productOrder', 'productOrderDetails', 'productOrderDetailsChains'));
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