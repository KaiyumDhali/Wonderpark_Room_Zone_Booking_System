<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\ProductType;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\ProductBrand;
use App\Models\ProductFragrance;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductUnit;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;
use Validator;

class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read product|write product|create product', ['only' => ['index', 'show']]);
        $this->middleware('permission:create product', ['only' => ['create', 'store']]);
        $this->middleware('permission:write product', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function index()
    {
        $products = Product::leftJoin('suppliers', 'suppliers.id', '=', 'products.supplier_id')
            ->leftJoin('product_types', 'product_types.id', '=', 'products.type_id')
            ->leftJoin('product_categories', 'product_categories.id', '=', 'products.category_id')
            ->leftJoin('product_sub_categories', 'product_sub_categories.id', '=', 'products.sub_category_id')
            ->leftJoin('product_brands', 'product_brands.id', '=', 'products.brand_id')
            ->leftJoin('product_colors', 'product_colors.id', '=', 'products.color_id')
            ->leftJoin('product_sizes', 'product_sizes.id', '=', 'products.size_id')
            ->leftJoin('product_units', 'product_units.id', '=', 'products.unit_id')
            ->select('products.*', 'suppliers.supplier_name', 'product_types.type_name', 'product_categories.category_name', 'product_sub_categories.sub_category_name', 'product_brands.brand_name', 'product_colors.color_name', 'product_sizes.size_name', 'product_units.unit_name')
            ->orderby('id', 'desc')->get();

        // dd($products);
        // $products = Product::orderby('id', 'desc')->get();
        return view('pages.product.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $allSuppliers = Supplier::pluck('supplier_name', 'id')->all();
        $allProductTypes = ProductType::pluck('type_name', 'id')->all();
        $allCategories = ProductCategory::pluck('category_name', 'id')->all();
        $allSubCategories = ProductSubCategory::pluck('sub_category_name', 'id')->all();
        $allProductFragrances = ProductFragrance::pluck('fragrance_name', 'id')->all();
        $allBrands = ProductBrand::pluck('brand_name', 'id')->all();
        $allColors = ProductColor::pluck('color_name', 'id')->all();
        $allSizes = ProductSize::pluck('size_name', 'id')->all();
        $allUnits = ProductUnit::pluck('unit_name', 'id')->all();
        return view('pages.product.products._product-add', compact('allSuppliers', 'allProductTypes', 'allProductFragrances', 'allCategories', 'allSubCategories', 'allBrands', 'allColors', 'allSizes', 'allUnits'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'product_name' => 'required',
            'unit_id' => 'required',
        ]);

        // $request->validator([
        //     'product_name' => 'required|string|max:255',
        //     'unit_id' => 'required',
        // ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $input = $request->all();

        // dd($input);
        if ($request->hasfile('product_image1')) {
            $image = $request->file('product_image1');
            $name = date('d-m-Y-H-i-s') . '_' . $image->getClientOriginalName();
            $image_path = $image->storeAs('public/images/products', $name);
            $input['product_image1'] = $image_path;
        }
        if ($request->hasfile('product_image2')) {
            $image = $request->file('product_image2');
            $name = date('d-m-Y-H-i-s') . '-' . $image->getClientOriginalName();
            $image_path = $image->storeAs('public/images/products', $name);
            $input['product_image2'] = $image_path;
        }

        $input['pack_size'] = empty($input['pack_size']) ? 0 : $input['pack_size'];
        $input['is_purchaseable'] = empty($input['is_purchaseable']) ? 0 : $input['is_purchaseable'];
        $input['is_saleable'] = empty($input['is_saleable']) ? 0 : $input['is_saleable'];
        $input['is_produceable'] = empty($input['is_produceable']) ? 0 : $input['is_produceable'];
        $input['is_consumable'] = empty($input['is_consumable']) ? 0 : $input['is_consumable'];
        //  $input['status'] = empty($input['status']) ? 1 : $input['status'];

        Product::create($input);

        return redirect()->route('products.index')->with([
            'message' => 'Product has been create successfully !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::leftJoin('suppliers', 'suppliers.id', '=', 'products.supplier_id')
            ->leftJoin('product_types', 'product_types.id', '=', 'products.type_id')
            ->leftJoin('product_fragrances', 'product_fragrances.id', '=', 'products.fragrance_id')
            ->leftJoin('product_categories', 'product_categories.id', '=', 'products.category_id')
            ->leftJoin('product_sub_categories', 'product_sub_categories.id', '=', 'products.sub_category_id')
            ->leftJoin('product_brands', 'product_brands.id', '=', 'products.brand_id')
            ->leftJoin('product_colors', 'product_colors.id', '=', 'products.color_id')
            ->leftJoin('product_sizes', 'product_sizes.id', '=', 'products.size_id')
            ->leftJoin('product_units', 'product_units.id', '=', 'products.unit_id')
            ->select('products.*', 'suppliers.supplier_name', 'product_types.type_name', 'product_fragrances.fragrance_name', 'product_categories.category_name', 'product_sub_categories.sub_category_name', 'product_brands.brand_name', 'product_colors.color_name', 'product_sizes.size_name', 'product_units.unit_name')
            ->orderby('id', 'desc')->find($id);

        // dd($product);

        return view('pages.product.products._product-show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $allSuppliers = Supplier::pluck('supplier_name', 'id')->all();
        $allProductTypes = ProductType::pluck('type_name', 'id')->all();
        $allCategories = ProductCategory::pluck('category_name', 'id')->all();
        $allSubCategories = ProductSubCategory::pluck('sub_category_name', 'id')->all();
        $allProductFragrances = ProductFragrance::pluck('fragrance_name', 'id')->all();
        $allBrands = ProductBrand::pluck('brand_name', 'id')->all();
        $allColors = ProductColor::pluck('color_name', 'id')->all();
        $allSizes = ProductSize::pluck('size_name', 'id')->all();
        $allUnits = ProductUnit::pluck('unit_name', 'id')->all();

        return view('pages.product.products._product-update', compact('product', 'allSuppliers', 'allProductTypes', 'allProductFragrances', 'allCategories', 'allSubCategories', 'allBrands', 'allColors', 'allSizes', 'allUnits'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'unit_id' => 'required',
        ]);

        // $request->validator([
        //     'product_name' => 'required|string|max:255',
        //     'unit_id' => 'required',
        // ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $input = $request->all();
        // dd($input);

        if ($request->hasfile('product_image1')) {
            $image = $request->file('product_image1');
            $name = date('d-m-Y-H-i-s') . '_' . $image->getClientOriginalName();
            $image_path = $image->storeAs('public/images/products', $name);
            $input['product_image1'] = $image_path;
        }
        if ($request->hasfile('product_image2')) {
            $image = $request->file('product_image2');
            $name = date('d-m-Y-H-i-s') . '-' . $image->getClientOriginalName();
            $image_path = $image->storeAs('public/images/products', $name);
            $input['product_image2'] = $image_path;
        }
        $input['pack_size'] = empty($input['pack_size']) ? 0 : $input['pack_size'];
        $input['is_purchaseable'] = empty($input['is_purchaseable']) ? 0 : $input['is_purchaseable'];
        $input['is_saleable'] = empty($input['is_saleable']) ? 0 : $input['is_saleable'];
        $input['is_produceable'] = empty($input['is_produceable']) ? 0 : $input['is_produceable'];
        $input['is_consumable'] = empty($input['is_consumable']) ? 0 : $input['is_consumable'];

        //  $input['order'] = empty($input['order']) ? 1 : $input['order'];
        //  $input['status'] = empty($input['status']) ? 1 : $input['status'];
        //  dd($input);

        $product->update($input);

        return redirect()->route('products.index')->with([
            'message' => 'Product has been updated successfully!',
            'alert-type' => 'success'
        ]);
    }

    // imageDestroy 1
    public function imageDestroy1($id)
    {
        $product = Product::find($id);
        if ($product->product_image1 != Null) {
            Storage::delete($product->product_image1);
            $product->product_image1 = Null;
            $product->save();
        }
        return redirect()->route('admin.products.edit', $product->id)->with([
            'message' => 'product image1 storage successfully deleted. !',
            'alert-type' => 'danger'
        ]);
    }
    // imageDestroy 2
    public function imageDestroy2($id)
    {
        $product = Product::find($id);
        if ($product->product_image2 != Null) {
            Storage::delete($product->product_image2);
            $product->product_image2 = Null;
            $product->save();
        }
        return redirect()->route('admin.products.edit', $product->id)->with([
            'message' => 'product image2 storage successfully deleted. !',
            'alert-type' => 'danger'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }

    // table select destroy
    public function massDestroy()
    {
        Product::whereIn('id', request('ids'))->delete();
        return response()->noContent();
    }
}
