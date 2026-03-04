<?php

namespace App\Http\Controllers\Product;

use App\Models\ProductBrand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use validator;

class BrandController extends Controller {

    function __construct() {
        $this->middleware('permission:read brand|write brand|create brand', ['only' => ['index', 'show']]);
        $this->middleware('permission:create brand', ['only' => ['create', 'store']]);
        $this->middleware('permission:write brand', ['only' => ['edit', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $brands = ProductBrand::all();

        return view('pages.product.brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('pages.product.brand._brand-add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'brand_name' => 'required',
        ]);

        $input = $request->all();
        $type = $input['sumite_type'] ?? 0;

        ProductBrand::create($input);

        if ($type == 1) {
            return redirect()->back()->with([
            'message' => 'Successfully created!',
            'alert-type' => 'success'
            ]);
        }
        if ($type == 0) {
            return redirect()->route('brands.index')->with([
                'message' => 'successfully created !',
                'alert-type' => 'success'
            ]);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductBrand $brand) {
        return view('pages.product.brand._brand-update', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductBrand $brand) {
        // $brand->update($request->validated());
        // $category=ProductCategory::find($id);
        $brand->brand_name = $request->brand_name;
        $brand->status = $request->status;
        $brand->update();

        return redirect()->route('brands.index')->with([
                    'message' => 'successfully updated !',
                    'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductBrand $brand) {
        //$category=ProductCategory::find($id);
        $brand->delete();

        return redirect()->route('brands.index')->with([
                    'message' => 'successfully deleted !',
                    'alert-type' => 'danger'
        ]);
    }

    // table select destroy
    public function massDestroy() {
        ProductBrand::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }

}
